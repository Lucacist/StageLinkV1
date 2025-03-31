<?php
require_once ROOT_PATH . '/src/Controllers/Controller.php';

class HomepageController extends Controller {
    public function __construct() {
        parent::__construct();
    }
    
    public function index() {
        echo $this->render('homepage', [
            'pageTitle' => 'Homepage - StageLink'
        ]);
    }
}