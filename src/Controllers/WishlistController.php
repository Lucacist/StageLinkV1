<?php
require_once ROOT_PATH . '/src/controllers/controller.php';
require_once ROOT_PATH . '/src/models/offremodel.php';

class wishlistcontroller extends controller {
    private $offremodel;
    private $db;
    
    public function __construct() {
        parent::__construct(); // Appel au constructeur parent pour initialiser Twig
        $this->offremodel = new offremodel();
        $this->db = database::getInstance()->getConnection();
    }   
    
    public function toggleLike() {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
        
        if (!isset($_SESSION['user_id'])) {
            http_response_code(401);
            echo json_encode(['success' => false, 'message' => 'Non connecté']);
            return;
        }
        
        if (!isset($_POST['offre_id'])) {
            http_response_code(400);
            echo json_encode(['success' => false, 'message' => 'ID de l\'offre manquant']);
            return;
        }
        
        $utilisateur_id = $_SESSION['user_id'];
        $offre_id = (int)$_POST['offre_id'];
        
        try {
            $db = database::getInstance()->getConnection();
            $db->begin_transaction();
            
            $stmt = $db->prepare("SELECT 1 FROM WishList WHERE utilisateur_id = ? AND offre_id = ?");
            $stmt->bind_param("ii", $utilisateur_id, $offre_id);
            $stmt->execute();
            $exists = $stmt->get_result()->num_rows > 0;
            
            if ($exists) {
                $stmt = $db->prepare("DELETE FROM WishList WHERE utilisateur_id = ? AND offre_id = ?");
                $stmt->bind_param("ii", $utilisateur_id, $offre_id);
                $stmt->execute();
            } else {
                $stmt = $db->prepare("INSERT INTO WishList (utilisateur_id, offre_id) VALUES (?, ?)");
                $stmt->bind_param("ii", $utilisateur_id, $offre_id);
                $stmt->execute();
            }
            
            $db->commit();
            
            header('Content-Type: application/json');
            echo json_encode([
                'success' => true,
                'action' => $exists ? 'removed' : 'added'
            ]);
            
        } catch (Exception $e) {
            if (isset($db)) {
                $db->rollback();
            }
            
            http_response_code(500);
            echo json_encode([ 
                'success' => false, 
                'message' => 'Une erreur est survenue: ' . $e->getMessage()
            ]);
        }
    }
    
    public function index() {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
        
        if (!isset($_SESSION['user_id'])) {
            $this->redirect('login');
        }
        
        $sql = "SELECT o.*, e.nom as entreprise_nom 
                FROM Offres o
                JOIN Entreprises e ON o.entreprise_id = e.id
                JOIN WishList w ON o.id = w.offre_id
                WHERE w.utilisateur_id = ?
                ORDER BY o.date_debut DESC";
                
        $stmt = $this->db->prepare($sql); 
        $stmt->bindParam(1, $_SESSION['user_id'], PDO::PARAM_INT);
        $stmt->execute();
        $result = $stmt->fetchAll(PDO::FETCH_OBJ);
        
        
        echo $this->render('wishlist/wishlist', [
            'pageTitle' => 'Mes offres favorites - StageLink',
            'offres' => $result
        ]);
    }
}
?>






