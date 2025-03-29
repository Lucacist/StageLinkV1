<?php
require_once ROOT_PATH . '/src/Controllers/Controller.php';
require_once ROOT_PATH . '/src/Models/OffreModel.php';
require_once ROOT_PATH . '/src/Models/EntrepriseModel.php';
require_once ROOT_PATH . '/src/Controllers/Pagination.php';

class OffreController extends Controller {
    private $offreModel;
    private $entrepriseModel;
    
    public function __construct() {
        parent::__construct();
        $this->offreModel = new OffreModel();
        $this->entrepriseModel = new EntrepriseModel();
    }
    
    public function index() {
        $this->checkPageAccess('VOIR_OFFRE');
        
        $page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
        $searchTerm = isset($_GET['search']) ? trim($_GET['search']) : '';
        
        if (!empty($searchTerm)) {
            // Recherche avec terme
            $totalOffres = $this->offreModel->countOffresBySearch($searchTerm);
            
            $pagination = new Pagination($totalOffres, 5, $page);
            
            $userId = isset($_SESSION['user_id']) ? $_SESSION['user_id'] : null;
            $offres = $this->offreModel->searchOffres(
                $searchTerm,
                $pagination->getLimit(), 
                $pagination->getOffset(),
                $userId
            );
        } else {
            // Sans recherche
            $totalOffres = $this->offreModel->countAllOffres();
            
            $pagination = new Pagination($totalOffres, 5, $page);
            
            $userId = isset($_SESSION['user_id']) ? $_SESSION['user_id'] : null;
            $offres = $this->offreModel->getOffresWithPagination(
                $pagination->getLimit(), 
                $pagination->getOffset(),
                $userId
            );
        }
        
        foreach ($offres as &$offre) {
            if (isset($_SESSION['user_id'])) {
                $offre['isLiked'] = $this->offreModel->isOffreLiked($offre['id'], $_SESSION['user_id']);
            } else {
                $offre['isLiked'] = false;
            }
        }
        
        $baseUrl = 'index.php?route=offres';
        if (!empty($searchTerm)) {
            $baseUrl .= '&search=' . urlencode($searchTerm);
        }
        
        echo $this->render('offres', [
            'pageTitle' => 'Offres de stage - StageLink',
            'offres' => $offres,
            'pagination' => $pagination->renderHtml($baseUrl),
            'searchTerm' => $searchTerm
        ]);
    }
    
    public function details() {
        $this->checkPageAccess('VOIR_OFFRE');
        
        $id = isset($_GET['id']) ? (int)$_GET['id'] : 0;
        
        if ($id <= 0) {
            $this->redirect('offres');
        }
        
        $offre = $this->offreModel->getOffreById($id);
        
        if (!$offre) {
            $this->redirect('offres');
        }
        
        $competences = $this->offreModel->getOffreCompetences($id);
        
        $isLiked = false;
        $hasApplied = false;
        
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
        
        if (isset($_GET['message'])) {
            $message = $_GET['message'];
            if ($message === 'candidature_success') {
                $_SESSION['flash'] = [
                    'type' => 'success',
                    'message' => 'Votre candidature a été enregistrée avec succès.'
                ];
            } elseif ($message === 'candidature_error') {
                $_SESSION['flash'] = [
                    'type' => 'error',
                    'message' => 'Une erreur est survenue lors de l\'enregistrement de votre candidature.'
                ];
            }
        }
        
        if (isset($_SESSION['user_id'])) {
            $isLiked = $this->offreModel->isOffreLiked($id, $_SESSION['user_id']);
            
            require_once ROOT_PATH . '/src/Models/CandidatureModel.php';
            $candidatureModel = new CandidatureModel();
            $hasApplied = $candidatureModel->candidatureExiste($_SESSION['user_id'], $id);
        }
        
        $flash = isset($_SESSION['flash']) ? $_SESSION['flash'] : null;
        if (isset($_SESSION['flash'])) {
            unset($_SESSION['flash']);
        }
        
        echo $this->render('offre_details', [
            'pageTitle' => $offre['titre'] . ' - StageLink',
            'offre' => $offre,
            'competences' => $competences,
            'isLiked' => $isLiked,
            'hasApplied' => $hasApplied,
            'flash' => $flash
        ]);
    }
    
    public function create() {
        $this->checkPageAccess('CREER_OFFRE');
        
        $entreprises = $this->entrepriseModel->getAllEntreprises();
        
        $mode = 'create';
        $offre = null;
        
        if (isset($_GET['id'])) {
            $id = (int)$_GET['id'];
            $offre = $this->offreModel->getOffreById($id);
            
            if ($offre) {
                $mode = 'edit';
            }
        }
        
        echo $this->render('creer-offre', [
            'pageTitle' => ($mode === 'create' ? 'Créer une offre' : 'Modifier une offre') . ' - StageLink',
            'entreprises' => $entreprises,
            'mode' => $mode,
            'offre' => $offre
        ]);
    }
    
    public function traiter() {
        if (!isset($_SESSION['user_id'])) {
            $this->redirect('login');
        }
        
        if (!isset($_SESSION['user_role']) || ($_SESSION['user_role'] !== 'ADMIN' && $_SESSION['user_role'] !== 'PILOTE')) {
            $this->redirect('accueil');
        }
        
        error_log("POST data: " . print_r($_POST, true));
        
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $action = $_POST['action'] ?? '';
            $id = isset($_POST['id']) ? (int)$_POST['id'] : 0;
            
            if ($action === 'create' || $action === 'update') {
                $entrepriseId = isset($_POST['entreprise_id']) ? (int)$_POST['entreprise_id'] : 0;
                $titre = $_POST['titre'] ?? '';
                $description = $_POST['description'] ?? '';
                $baseRemuneration = isset($_POST['base_remuneration']) ? (float)$_POST['base_remuneration'] : 0;
                $dateDebut = !empty($_POST['date_debut']) ? $_POST['date_debut'] : date('Y-m-d');
                $dateFin = !empty($_POST['date_fin']) ? $_POST['date_fin'] : date('Y-m-d', strtotime('+3 months'));
                $competences = isset($_POST['competences']) ? $_POST['competences'] : [];
                
                error_log("Création d'offre - Données reçues: " . json_encode([
                    'action' => $action,
                    'entrepriseId' => $entrepriseId,
                    'titre' => $titre,
                    'competences' => $competences
                ]));
                
                if ($action === 'create') {
                    $offreId = $this->offreModel->createOffre(
                        $entrepriseId, 
                        $titre, 
                        $description, 
                        $baseRemuneration, 
                        $dateDebut, 
                        $dateFin,
                        $competences
                    );
                    
                    if (!$offreId) {
                        $_SESSION['error_message'] = "Erreur lors de la création de l'offre.";
                    } else {
                        $_SESSION['success_message'] = "L'offre a été créée avec succès.";
                    }
                } else {
                    $success = $this->offreModel->updateOffre($id, $entrepriseId, $titre, $description, $baseRemuneration, $dateDebut, $dateFin);
                    
                    if ($success) {
                        $this->offreModel->addOffreCompetences($id, $competences);
                    }
                    
                    if (!$success) {
                        $_SESSION['error_message'] = "Erreur lors de la mise à jour de l'offre.";
                    } else {
                        $_SESSION['success_message'] = "L'offre a été mise à jour avec succès.";
                    }
                }
            } elseif ($action === 'delete' && $id > 0) {
                $this->offreModel->deleteOffre($id);
            }
        }
        
        $this->redirect('dashboard');
    }
    
    public function like() {
        header('Content-Type: application/json');
        
        if (!isset($_SESSION['user_id'])) {
            http_response_code(401); 
            echo json_encode(['success' => false, 'message' => 'Vous devez être connecté pour ajouter une offre à vos favoris']);
            return;
        }
        
        $offreId = isset($_POST['offre_id']) ? (int)$_POST['offre_id'] : 0;
        
        if ($offreId <= 0) {
            http_response_code(400); 
            echo json_encode(['success' => false, 'message' => 'ID d\'offre invalide']);
            return;
        }
        
        $result = $this->offreModel->toggleLike($offreId, $_SESSION['user_id']);
        
        $isLiked = $this->offreModel->isOffreLiked($offreId, $_SESSION['user_id']);
        
        echo json_encode([
            'success' => $result,
            'liked' => $isLiked
        ]);
        exit; 
    }
}