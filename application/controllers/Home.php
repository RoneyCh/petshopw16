<?php if(!defined('BASEPATH')) exit('No direct script access allowed');
require_once APPPATH. 'controllers/BaseController.php';

class Home extends BaseController {
    public function __construct() {
        parent::__construct();        
        $this->load->library('authenticationmiddleware');
    }

    public function index() {
        $token = isset($_COOKIE['token']) ? $_COOKIE['token'] : null;
        $decoded = $token !== null ? $this->authenticationmiddleware->verificarToken($token) : false;
        if(!$decoded) {
            $this->load->view('error_view');
            return;
        }
        $this->load->view('menu');
    }
    
    public function home() {
        $this->load->view('home');
    }
}