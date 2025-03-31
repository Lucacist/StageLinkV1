<?php
require_once ROOT_PATH . '/src/Controllers/Controller.php';
require_once ROOT_PATH . '/src/Models/UserModel.php';

class AuthController extends Controller {
    private $userModel;
    
    public function __construct() {
        parent::__construct();
        $this->userModel = new UserModel();
    }
    
    public function login() {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
        
        if (isset($_SESSION['user_id'])) {
            $this->redirect('homepage');
        }
        
        $error = '';
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $email = $_POST['email'] ?? '';
            $password = $_POST['password'] ?? '';
            
            $user = $this->userModel->authenticate($email, $password);
            
            if ($user) {
                $_SESSION['user_id'] = $user['id'];
                $_SESSION['user_name'] = $user['name'];
                $_SESSION['user_firstname'] = $user['firstname'];
                
                $role = $this->userModel->getUserRole($user['id']);
                $_SESSION['user_role'] = $role['role_code'];
                
                $_SESSION['permissions'] = $this->userModel->getUserPermissions($user['id']);
                
                $this->redirect('homepage');
            } else {
                $error = 'Incorrect email or password';
            }
        }
        
        echo $this->render('login', [
            'error' => $error,
            'pageTitle' => 'Connection - StageLink'
        ]);
    }
    
    public function logout() {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
        
        $_SESSION = [];
        
        if (ini_get("session.use_cookies")) {
            $params = session_get_cookie_params();
            setcookie(session_name(), '', time() - 42000,
                $params["path"], $params["domain"],
                $params["secure"], $params["httponly"]
            );
        }
        
        session_destroy();
        
        $this->redirect('login');
    }
}
