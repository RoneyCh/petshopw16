<?php if(!defined('BASEPATH')) exit('No direct script access allowed');
require_once APPPATH. 'controllers/BaseController.php';

class Conta extends BaseController {
    public $base_url;
    public function __construct() {
        parent::__construct();        
        $this->load->library('authenticationmiddleware');
        $this->load->model('Usuario/usuario_model', 'usuario');
        $this->base_url = $this->config->item('base_url');
    }

    public function index() {
        $token = isset($_COOKIE['token']) ? $_COOKIE['token'] : null;
        $decoded = $token !== null ? $this->authenticationmiddleware->verificarToken($token) : false;
        if(!$decoded) {
            $this->load->view('error_view');
            return;
        }
        $data['dados'] = $this->usuario->getUsuario($_SESSION['usuario']['cod_usuario_usr']);
        $data['base_url'] = $this->base_url;
        $this->load->view('conta', $data);
    }
}