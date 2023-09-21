<?php if (!defined('BASEPATH')) exit('No direct script access allowed');
require_once 'vendor/autoload.php';
require_once APPPATH . 'controllers/BaseController.php';

class Agendamentos extends BaseController
{
    public $base_url;
    public function __construct()
    {
        parent::__construct();
        $this->load->library('authenticationmiddleware');
        $this->load->model('Pet/pets_model', 'pet');
        $this->load->model('Agendamento/agendamentos_model', 'agendamento');
        $this->load->model('Usuario/usuario_model', 'usuario');
        $this->base_url = $this->config->item('base_url');
    }

    public function index()
    {
        $this->listAgendamentos();
    }

    public function listAgendamentos()
    {
        // Listar todos os pets
        $token = isset($_COOKIE['token']) ? $_COOKIE['token'] : null;
        $decoded = $token !== null ? $this->authenticationmiddleware->verificarToken($token) : false;
        if (!$decoded) {
            $this->load->view('error_view');
            return;
        }
        $cod_usuario = null;
        if($_SESSION['usuario']['des_cargo_usr'] == 0) {
            $cod_usuario = $_SESSION['usuario']['cod_usuario_usr'];
        }
        
        $data['dados'] = $this->agendamento->listAgendamentos($cod_usuario);
        // converter data para o formato brasileiro
        foreach ($data['dados'] as $key => $value) {
            $data['dados'][$key]->dta_agendamento_agd = date('d/m/Y H:i', strtotime($value->dta_agendamento_agd));
        }

        $this->load->view('Agendamento/agendamentos', $data);
    }

    public function getAgendamento($id)
    {
        try {
            // Visualizar um usuário específico
            $token = isset($_COOKIE['token']) ? $_COOKIE['token'] : null;
            $decoded = $token !== null ? $this->authenticationmiddleware->verificarToken($token) : false;
            if (!$decoded) {
                $this->load->view('error_view');
                return;
            }
            $agendamento = $this->agendamento->getAgendamento($id);
            
            print_r($agendamento);exit;
            if (!$agendamento) {
                throw new Exception("Agendamento não encontrado");
            }
            header('Content-Type: application/json');
            echo json_encode($agendamento);
        } catch (Exception $e) {
            header("HTTP/1.1 404 Not Found");
            echo json_encode(array("error" => $e->getMessage()));
        }
    }

    public function criarAgendamento()
    {
        try {
            $response = array();
            $token = isset($_COOKIE['token']) ? $_COOKIE['token'] : null;
            $decoded = $token !== null ? $this->authenticationmiddleware->verificarToken($token) : false;
            if (!$decoded) {
                $this->load->view('error_view');
                return;
            }
            // Validações
            if (empty($_POST['cod_pet']) || $_POST['cod_pet'] == "" || empty($_POST['data_agendamento'])) {
                $response = ['warning' => 'Preencha todos os campos.'];
            } else {
                $date = DateTime::createFromFormat('d/m/Y H:i', $_POST['data_agendamento']);
                $date = $date->format('Y-m-d H:i:s');
                $_POST['data_agendamento'] = $date;
                $pet = [
                    'cod_pets_agd' => $_POST['cod_pet'],
                    'dta_agendamento_agd' => $_POST['data_agendamento'],
                    'cod_usuario_agd' => !empty($_POST['pet_dono']) ? $_POST['pet_dono'] : $_SESSION['usuario']['cod_usuario_usr']
                ];

                $usuarioId = $this->agendamento->criarAgendamento($pet);

                if ($usuarioId) {
                    $response = ['success' => 'Agendamento realizado com sucesso!'];
                } else {
                    $response = ['error' => 'Ocorreu um erro ao realizar o Agendamento.'];
                }
            }
            header('Content-Type: application/json');
            echo json_encode($response);
        } catch (Exception $e) {
            header("HTTP/1.1 400 Bad Request");
            echo json_encode(['error' => $e->getMessage()]);
        }
    }


    public function showCreate($cod = 0)
    {
        try {
            if ($cod !== 0) {
                $agendamento = $this->agendamento->getAgendamento($cod);
                if (!$agendamento) {
                    throw new Exception("Agendamento não encontrado");
                } else {
                    $data['dados'] = $agendamento;
                    // converter data para o formato brasileiro
                    $data['dados']->dta_agendamento_agd = date('d/m/Y H:i', strtotime($data['dados']->dta_agendamento_agd));
                }
            } else {
                $data['dados'] = [];
            }
            $data['usuarios'] = $this->usuario->listUsuarios();
            $cod_usuario = null;
            if($_SESSION['usuario']['des_cargo_usr'] == 0) {
                $cod_usuario = $_SESSION['usuario']['cod_usuario_usr'];
            }
            $data['pets'] = $this->pet->listPets($cod_usuario);
            $this->load->view('Cadastro/cadastro_agendamento', $data);
        } catch (Exception $e) {
            header("HTTP/1.1 404 Not Found");
            echo json_encode(array("error" => $e->getMessage()));
        }
    }

    public function atualizarAgendamento($id)
    {
        try {
            $token = isset($_COOKIE['token']) ? $_COOKIE['token'] : null;
            $decoded = $token !== null ? $this->authenticationmiddleware->verificarToken($token) : false;
            if (!$decoded) {
                $this->load->view('error_view');
                return;
            }
            $response = array();
            // Processar o formulário de edição e atualizar um usuário existente
            $cod_pets_agd = isset($_POST['cod_pet'])  && !empty($_POST['cod_pet']) ? $_POST['cod_pet'] : null;
            $dta_agendamento_agd = isset($_POST['data_agendamento'])  && !empty($_POST['data_agendamento']) ? $_POST['data_agendamento'] : null;
            $cod_usuario_agd = isset($_POST['pet_dono'])  && !empty($_POST['pet_dono']) ? $_POST['pet_dono'] : null;

            if (!$cod_pets_agd) {
                $response['warning'] = "Escolha um pet";
            } elseif (!$dta_agendamento_agd) {
                $response['warning'] = "Escolha uma data";
            }
            
            $pet = $this->agendamento->getAgendamento($id);
            if (!$pet) {
                $response['warning'] = "Agendamento não encontrado";
            } else {
                $date = DateTime::createFromFormat('d/m/Y H:i', $dta_agendamento_agd);
                $date = $date->format('Y-m-d H:i:s');
                $dta_agendamento_agd = $date;
                $dadosAgendamento = array(
                    'cod_pets_agd' => $cod_pets_agd,
                    'dta_agendamento_agd' => $dta_agendamento_agd,
                    'cod_usuario_agd' => !empty($cod_usuario_agd) ? $cod_usuario_agd : $pet->cod_usuario_agd
                );
                $this->agendamento->atualizarAgendamento($id, $dadosAgendamento);
                $response['success'] = "Agendamento atualizado com sucesso";
            }

            header('Content-Type: application/json');
            echo json_encode($response);
        } catch (Exception $e) {
            header("HTTP/1.1 400 Bad Request");
            echo json_encode(array("error" => $e->getMessage()));
        }
    }

    public function deletarAgendamento($id)
    {
        try {
            $response = array();
            // Deletar um usuário pelo ID
            $token = isset($_COOKIE['token']) ? $_COOKIE['token'] : null;
            $decoded = $token !== null ? $this->authenticationmiddleware->verificarToken($token) : false;
            if (!$decoded) {
                $this->load->view('error_view');
                return;
            }
            $this->agendamento->deletarAgendamento($id);
            $response['success'] = "Agendamento deletado com sucesso";

            header('Content-Type: application/json');
            echo json_encode($response);
        } catch (Exception $e) {
            header("HTTP/1.1 404 Not Found");
            echo json_encode(array("error" => $e->getMessage()));
        }
    }
}
