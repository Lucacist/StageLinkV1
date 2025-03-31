<?php
require_once ROOT_PATH . '/src/Controllers/Controller.php';
require_once ROOT_PATH . '/src/Models/OfferModel.php';
require_once ROOT_PATH . '/src/Models/ApplicationModel.php';

class ApplicationController extends Controller {
    private $applicationModel;
    private $offerModel;
    
    public function __construct() {
        parent::__construct();
        require_once ROOT_PATH . '/src/Models/Database.php';
        $this->applicationModel = new ApplicationModel();
        $this->offerModel = new OfferModel();
    }
    
    public function index() {
        $this->checkPageAccess('VIEW_APPLICATIONS');
        
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
        
        $isAdmin = $this->hasPermission('MANAGE_APPLICATIONS');
        
        if ($isAdmin) {
            $sql = "SELECT c.id, c.user_id, c.offer_id, c.cover_letter, c.resume, c.date_application, o.title as offer_title, e.name as company_name, u.name as student_name, u.firstname as student_firstname
                    FROM Applications c
                    JOIN Offers o ON c.offer_id = o.id
                    JOIN Companies e ON o.company_id = e.id
                    JOIN User u ON c.user_id = u.id
                    ORDER BY c.date_application DESC";
            $stmt = $this->db->prepare($sql);
            $stmt->execute();
        } else {
            $sql = "SELECT c.id, c.user_id, c.offer_id, c.cover_letter, c.resume, c.date_application, o.title as offer_title, e.name as company_name
                    FROM Applications c
                    JOIN Offers o ON c.offer_id = o.id
                    JOIN Companies e ON o.company_id = e.id
                    WHERE c.user_id = ?
                    ORDER BY c.date_application DESC";
            $stmt = $this->db->prepare($sql);
            $stmt->bind_param("i", $_SESSION['user_id']);
            $stmt->execute();
        }
        
        $result = $stmt->get_result();
        
        $applications = [];
        while ($row = $result->fetch_assoc()) {
            $applications[] = $row;
        }
        
        echo $this->render('process_application', [
            'pageTitle' => 'Application - StageLink',
            'applications' => $applications,
            'isAdmin' => $isAdmin
        ]);
    }
    
    public function apply() {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
        
        if (!isset($_SESSION['user_id'])) {
            $this->redirect('login');
        }
        
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $offerId = isset($_POST['offer_id']) ? (int)$_POST['offer_id'] : 0;
            $letter = $_POST['cover_letter'] ?? '';
            
            $resumePath = '';
            if (isset($_FILES['resume']) && $_FILES['resume']['error'] === UPLOAD_ERR_OK) {
                $tmpName = $_FILES['resume']['tmp_name'];
                $fileName = basename($_FILES['resume']['name']);
                $uploadDir = ROOT_PATH . '/public/uploads/resume/';
                
                if (!is_dir($uploadDir)) {
                    mkdir($uploadDir, 0777, true);
                }
                
                $resumePath = 'resume_' . $_SESSION['user_id'] . '_' . time() . '_' . $fileName;
                
                move_uploaded_file($tmpName, $uploadDir . $cvPath);
            }
            
            if ($offerId > 0 && !empty($resumePath)) {
                $sql = "INSERT INTO Applications (user_id, offer_id, date_application, resume, cover_letter) 
                        VALUES (?, ?, NOW(), ?, ?)";
                $stmt = $this->db->prepare($sql);
                $stmt->bind_param("iiss", $_SESSION['user_id'], $offerId, $resumePath, $letter);
                $stmt->execute();
                
                $this->redirect('offer_details&id=' . $offreId . '&message=application_success');
            } else {
                $this->redirect('offer_details&id=' . $offreId . '&message=application_error');
            }
        } else {
            $this->redirect('offers');
        }
    }
    
    public function myApplications() {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
        
        if (!isset($_SESSION['user_id'])) {
            $this->redirect('login');
            return;
        }
        
        if (!isset($_SESSION['user_role']) || !in_array($_SESSION['user_role'], ['ADMIN', 'STUDENT'])) {
            $this->redirect('homepage');
            return;
        }
        
        $candidatures = $this->candidatureModel->getCandidaturesByUtilisateur($_SESSION['user_id']);
        
        echo $this->render('offre/mes_candidatures', [
            'pageTitle' => 'Mes Candidatures - StageLink',
            'candidatures' => $candidatures
        ]);
    }
    
    public function confirmation() {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
        
        $candidature_id = filter_input(INPUT_GET, 'id', FILTER_VALIDATE_INT);
        
        if ($candidature_id) {
            $candidature = $this->candidatureModel->getCandidatureById($candidature_id);
            $offre = $this->offreModel->getOffreById($candidature['offre_id']);
            
            echo $this->render('offre/confirmation_candidature', [
                'pageTitle' => 'Candidature Confirmée - StageLink',
                'candidature' => $candidature,
                'offre' => $offre
            ]);
        } else {
            $this->redirect('offres');
        }
    }
    
    public function traiter() {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
        
        if (!isset($_SESSION['user_id'])) {
            $_SESSION['flash'] = [
                'type' => 'error',
                'message' => 'Vous devez être connecté pour postuler à une offre'
            ];
            $this->redirect('login');
            return;
        }
        
        if (isset($_SESSION['user_role']) && $_SESSION['user_role'] === 'PILOTE') {
            $_SESSION['flash'] = [
                'type' => 'error',
                'message' => 'En tant que pilote, vous ne pouvez pas postuler aux offres'
            ];
            $this->redirect('offres');
            return;
        }
        
        $offre_id = filter_input(INPUT_POST, 'offre_id', FILTER_VALIDATE_INT);
        
        if (!$offre_id) {
            $_SESSION['flash'] = [
                'type' => 'error',
                'message' => 'Offre invalide'
            ];
            $this->redirect('offres');
            return;
        }
        
        $utilisateur_id = $_SESSION['user_id'];
        
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            $this->redirect('offres');
            return;
        }
        
        $lettre_motivation = trim($_POST['lettre_motivation'] ?? '');
        $cv_file = isset($_FILES['cv']) ? $_FILES['cv'] : null;
        
        $errors = [];
        
        if ($offre_id <= 0) {
            $errors[] = "L'ID de l'offre est invalide.";
        }
        
        if (empty($lettre_motivation)) {
            $errors[] = "La lettre de motivation est requise.";
        }
        
        $cv_path = '';
        if ($cv_file && $cv_file['error'] == 0) {
            $allowed_types = ['application/pdf'];
            $finfo = finfo_open(FILEINFO_MIME_TYPE);
            $mime_type = finfo_file($finfo, $cv_file['tmp_name']);
            finfo_close($finfo);
            
            if (!in_array($mime_type, $allowed_types)) {
                $errors[] = "Le CV doit être au format PDF.";
            } else {
                $upload_dir = ROOT_PATH . '/uploads/cv/';
                if (!file_exists($upload_dir)) {
                    mkdir($upload_dir, 0777, true);
                }
                
                $cv_filename = uniqid('cv_') . '.pdf';
                $cv_path = 'uploads/cv/' . $cv_filename;
                $target_file = ROOT_PATH . '/' . $cv_path;
                
                if (!move_uploaded_file($cv_file['tmp_name'], $target_file)) {
                    $errors[] = "Une erreur s'est produite lors du téléchargement du CV.";
                    $cv_path = '';
                }
            }
        } else {
            $errors[] = "Le CV est requis.";
        }
        
        if ($this->candidatureModel->candidatureExiste($utilisateur_id, $offre_id)) {
            $errors[] = "Vous avez déjà candidaté pour cette offre.";
        }
        
        if (empty($errors)) {
            $result = $this->candidatureModel->creerCandidature($utilisateur_id, $offre_id, $lettre_motivation, $cv_path);
            
            if ($result['success']) {
                $_SESSION['flash'] = [
                    'type' => 'success',
                    'message' => "Votre candidature a été enregistrée avec succès."
                ];
                $this->redirect('confirmation_candidature', ['id' => $result['id']]);
            } else {
                $_SESSION['flash'] = [
                    'type' => 'danger',
                    'message' => $result['message']
                ];
                if (!empty($cv_path)) {
                    @unlink(ROOT_PATH . '/' . $cv_path);
                }
                $this->redirect('offre_details', ['id' => $offre_id]);
            }
        } else {
            $_SESSION['flash'] = [
                'type' => 'danger',
                'message' => implode('<br>', $errors)
            ];
            if (!empty($cv_path)) {
                @unlink(ROOT_PATH . '/' . $cv_path);
            }
            $this->redirect('offre_details', ['id' => $offre_id]);
        }
    }
}
?>
