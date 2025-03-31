<?php
require_once ROOT_PATH . '/src/controllers/controller.php';
require_once ROOT_PATH . '/src/models/utilisateurmodel.php';
require_once ROOT_PATH . '/src/models/offremodel.php';
require_once ROOT_PATH . '/src/models/entreprisemodel.php';

class dashboardcontroller extends controller {
    private $utilisateurmodel;
    private $offremodel;
    private $entreprisemodel;
    
    public function __construct() {
        parent::__construct();
        $this->utilisateurmodel = new utilisateurmodel();
        $this->offremodel = new offremodel();
        $this->entreprisemodel = new entreprisemodel();
    }
    
    public function index() {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
        
        if (!isset($_SESSION['user_id'])) {
            $this->redirect('login');
        }
        
        if (!isset($_SESSION['user_role']) || ($_SESSION['user_role'] !== 'ADMIN' && $_SESSION['user_role'] !== 'PILOTE')) {
            $this->redirect('accueil');
        }
        
        $userData = $this->utilisateurmodel->getUserById($_SESSION['user_id']);
        $userRole = $this->utilisateurmodel->getUserRole($_SESSION['user_id']);
        
        $userPermissions = [
            'GERER_ENTREPRISES' => $this->hasPermission('GERER_ENTREPRISES'),
            'GERER_OFFRES' => $this->hasPermission('GERER_OFFRES'),
            'GERER_UTILISATEURS' => $this->hasPermission('GERER_UTILISATEURS'),
            'CREER_OFFRE' => $this->hasPermission('CREER_OFFRE')
        ];
        
        $entreprises = $this->entreprisemodel->getAllEntreprises();
        
        $competences = $this->offremodel->getAllCompetences();
        
        $totalOffres = count($this->offremodel->getAllOffres());
        $totalEntreprises = count($this->entreprisemodel->getAllEntreprises());
        
        $roles = $this->utilisateurmodel->getAllRoles();
        
        $error = isset($_GET['error']) ? $_GET['error'] : null;
        $success = isset($_GET['success']) ? $_GET['success'] : null;
        
        echo $this->render('dashboard', [
            'pageTitle' => 'Tableau de bord - StageLink',
            'userData' => $userData,
            'userRole' => $userRole,
            'totalOffres' => $totalOffres,
            'totalEntreprises' => $totalEntreprises,
            'entreprises' => $entreprises,
            'competences' => $competences,
            'userPermissions' => $userPermissions,
            'roles' => $roles,
            'error' => $error,
            'success' => $success
        ]);
    }
    
    public function traiterUtilisateur() {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
        
        if (!isset($_SESSION['user_id'])) {
            $this->redirect('login');
        }
        
        $userRole = $this->utilisateurmodel->getUserRole($_SESSION['user_id']);
        $isAdmin = ($userRole['role_code'] === 'ADMIN');
        $isPilote = ($userRole['role_code'] === 'PILOTE');
        
        if (!$isAdmin && !$isPilote) {
            $this->redirect('accueil');
        }
        
        $action = $_POST['action'] ?? '';
        
        if ($action === 'create') {
            $nom = $_POST['nom'] ?? '';
            $prenom = $_POST['prenom'] ?? '';
            $email = $_POST['email'] ?? '';
            $mot_de_passe = $_POST['mot_de_passe'] ?? '';
            $role = $_POST['role'] ?? '';
            
            error_log("Création d'utilisateur - Données reçues: " . 
                      "Nom: $nom, Prénom: $prenom, Email: $email, " . 
                      "Mot de passe: [masqué], Rôle: $role");
            
            if (empty($nom) || empty($prenom) || empty($email) || empty($mot_de_passe) || empty($role)) {
                error_log("Erreur de validation: champs manquants");
                $this->redirect('dashboard', ['error' => 'Tous les champs sont obligatoires.']);
                return;
            }
            
            if ($role === 'PILOTE' && !$isAdmin) {
                error_log("Erreur de permission: tentative de création d'un pilote par un non-admin");
                $this->redirect('dashboard', ['error' => 'Vous n\'avez pas la permission de créer un pilote.']);
                return;
            }
            
            $roleId = $this->utilisateurmodel->getRoleIdByCode($role);
            error_log("ID du rôle '$role': " . ($roleId ? $roleId : 'non trouvé'));
            
            if (!$roleId) {
                error_log("Erreur: rôle invalide ($role)");
                $this->redirect('dashboard', ['error' => 'Rôle invalide.']);
                return;
            }
            
            $result = $this->utilisateurmodel->createUser($nom, $prenom, $email, $mot_de_passe, $roleId);
            error_log("Résultat de la création: " . json_encode($result));
            
            if ($result['success']) {
                error_log("Utilisateur créé avec succès, ID: " . $result['id']);
                $this->redirect('dashboard', ['success' => 'Utilisateur créé avec succès.']);
            } else {
                error_log("Erreur lors de la création de l'utilisateur: " . $result['message']);
                $this->redirect('dashboard', ['error' => $result['message']]);
            }
        } else {
            $this->redirect('dashboard');
        }
    }
}
?>








