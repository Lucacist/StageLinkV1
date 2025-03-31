<?php
require_once ROOT_PATH . '/src/Controllers/Controller.php';

class AccueilController extends Controller {
    public function __construct() {
        parent::__construct();
    }
    
    public function index() {
        echo $this->render('accueil', [
            'pageTitle' => 'Accueil - StageLink'
        ]);
    }
}