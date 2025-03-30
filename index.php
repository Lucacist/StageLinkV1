<?php
define('ROOT_PATH', __DIR__);

session_start();

require_once ROOT_PATH . '/src/config/config.php';
require_once ROOT_PATH . '/vendor/autoload.php';
require_once ROOT_PATH . '/src/Utils/UrlHelper.php';

$public_routes = ['login', 'logout'];

// Gestion des URLs propres
$request_uri = $_SERVER['REQUEST_URI'];
$base_path = '/StageLinkV1/';

// Vérifier si l'URL est au format propre
if (strpos($request_uri, 'index.php') === false && strpos($request_uri, $base_path) === 0) {
    // Extraire la route de l'URL propre
    $path = substr($request_uri, strlen($base_path));
    $path = parse_url($path, PHP_URL_PATH);
    $route = trim($path, '/');
    
    // Si la route est vide, utiliser la route par défaut
    if (empty($route)) {
        $route = 'accueil';
    }
} else {
    // Utiliser le paramètre GET traditionnel
    $route = $_GET['route'] ?? 'accueil';
}

if (!isset($_SESSION['user_id']) && !in_array($route, $public_routes)) {
    header('Location: ' . UrlHelper::generateUrl('login'));
    exit();
}

switch ($route) {
    case 'accueil':
        require_once ROOT_PATH . '/src/Controllers/AccueilController.php';
        $controller = new AccueilController();
        $controller->index();
        break;
    
    case 'login':
        require_once ROOT_PATH . '/src/Controllers/AuthController.php';
        $controller = new AuthController();
        $controller->login();
        break;
    
    case 'entreprises':
        require_once ROOT_PATH . '/src/Controllers/EntrepriseController.php';
        $controller = new EntrepriseController();
        $controller->index();
        break;
    
    case 'entreprise_details':
        require_once ROOT_PATH . '/src/Controllers/EntrepriseController.php';
        $controller = new EntrepriseController();
        $controller->details();
        break;
    
    case 'offres':
        require_once ROOT_PATH . '/src/Controllers/OffreController.php';
        $controller = new OffreController();
        $controller->index();
        break;
    
    case 'offre_details':
        require_once ROOT_PATH . '/src/Controllers/OffreController.php';
        $controller = new OffreController();
        $controller->details();
        break;
    
    case 'creer-offre':
        require_once ROOT_PATH . '/src/Controllers/OffreController.php';
        $controller = new OffreController();
        $controller->create();
        break;
    
    case 'dashboard':
        require_once ROOT_PATH . '/src/Controllers/DashboardController.php';
        $controller = new DashboardController();
        $controller->index();
        break;
    
    case 'toggle_like':
        require_once ROOT_PATH . '/src/Controllers/WishlistController.php';
        $controller = new WishlistController();
        $controller->toggleLike();
        break;

    case 'wishlist':
        require_once ROOT_PATH . '/src/Controllers/WishlistController.php';
        $controller = new WishlistController();
        $controller->index();   
        break;
    
    case 'rate_entreprise':
        require_once ROOT_PATH . '/src/Controllers/EntrepriseController.php';
        $controller = new EntrepriseController();
        $controller->rate();
        break;
    
    case 'traiter_candidature':
        require_once ROOT_PATH . '/src/Controllers/CandidatureController.php';
        $controller = new CandidatureController();
        $controller->traiter();
        break;
    
    case 'mes_candidatures':
        require_once ROOT_PATH . '/src/Controllers/CandidatureController.php';
        $controller = new CandidatureController();
        $controller->mesCandidatures();
        break;
    
    case 'confirmation_candidature':
        require_once ROOT_PATH . '/src/Controllers/CandidatureController.php';
        $controller = new CandidatureController();
        $controller->confirmation();
        break;
    
    case 'traiter_entreprise':
        require_once ROOT_PATH . '/src/Controllers/EntrepriseController.php';
        $controller = new EntrepriseController();
        $controller->traiter();
        break;
    
    case 'traiter_offre':
        require_once ROOT_PATH . '/src/Controllers/OffreController.php';
        $controller = new OffreController();
        $controller->traiter();
        break;
    
    case 'traiter_utilisateur':
        require_once ROOT_PATH . '/src/Controllers/DashboardController.php';
        $controller = new DashboardController();
        $controller->traiterUtilisateur();
        break;
        
    case 'profil':
        require_once ROOT_PATH . '/src/Controllers/ProfilController.php';
        $controller = new ProfilController();
        $controller->index();
        break;
        
    case 'logout':
        require_once ROOT_PATH . '/src/Controllers/AuthController.php';
        $controller = new AuthController();
        $controller->logout();
        break;
        
    default:
        header('Location: ' . UrlHelper::generateUrl('accueil'));
        exit();
}
?>