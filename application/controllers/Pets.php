<?php if (!defined('BASEPATH')) exit('No direct script access allowed');
require_once 'vendor/autoload.php';
require_once APPPATH . 'controllers/BaseController.php';

class Pets extends BaseController
{
    public $base_url;
    public function __construct()
    {
        parent::__construct();
        $this->load->library('authenticationmiddleware');
        $this->load->model('Pet/pets_model', 'pet');
        $this->load->model('Usuario/usuario_model', 'usuario');
        $this->load->model('Agendamento/agendamentos_model', 'agendamento');
        $this->base_url = $this->config->item('base_url');
    }

    public function index()
    {
        $this->listPets();
    }

    public function listPets()
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
        $data['dados'] = $this->pet->listPets($cod_usuario);

        $this->load->view('Pet/pets', $data);
    }

    public function getPet($id)
    {
        try {
            // Visualizar um usuário específico
            $token = isset($_COOKIE['token']) ? $_COOKIE['token'] : null;
            $decoded = $token !== null ? $this->authenticationmiddleware->verificarToken($token) : false;
            if (!$decoded) {
                $this->load->view('error_view');
                return;
            }
            $user = $this->pet->getPet($id);
            if (!$user) {
                throw new Exception("Usuário não encontrado");
            }
            header('Content-Type: application/json');
            echo json_encode($user);
        } catch (Exception $e) {
            header("HTTP/1.1 404 Not Found");
            echo json_encode(array("error" => $e->getMessage()));
        }
    }

    public function criarPet()
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
            if (empty($_POST['pet_nome']) || empty($_POST['pet_especie'])) {
                $response = ['warning' => 'Preencha todos os campos.'];
            } elseif($_SESSION['usuario']['des_cargo_usr'] == 1 && empty($_POST['pet_dono'])) {
                $response = ['warning' => 'Selecione um dono.'];
            } else {
                // Resto do código para criar o usuário
                $pet = [
                    'des_nome_pet' => $_POST['pet_nome'],
                    'des_especie_pet' => $_POST['pet_especie'],
                    'cod_usuario_pet' => !empty($_POST['pet_dono']) ? $_POST['pet_dono'] : $_SESSION['usuario']['cod_usuario_usr']
                ];

                $usuarioId = $this->pet->criarPet($pet);

                if ($usuarioId) {
                    $response = ['success' => 'Pet adicionado com sucesso.'];
                } else {
                    $response = ['error' => 'Ocorreu um erro ao adicionar o Pet.'];
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
                $user = $this->pet->getPet($cod);
                if (!$user) {
                    throw new Exception("Pet não encontrado");
                } else {
                    $data['dados'] = $user;
                }
            } else {
                $data['dados'] = [];
            }
            $data['usuarios'] = $this->usuario->listUsuarios();
            $this->load->view('Cadastro/cadastro_pet', $data);
        } catch (Exception $e) {
            header("HTTP/1.1 404 Not Found");
            echo json_encode(array("error" => $e->getMessage()));
        }
    }

    public function atualizarPet($id)
    {
        try {
            $token = isset($_COOKIE['token']) ? $_COOKIE['token'] : null;
            $decoded = $token !== null ? $this->authenticationmiddleware->verificarToken($token) : false;
            if (!$decoded) {
                $this->load->view('error_view');
                return;
            }
            $response = array();
            $des_nome_pet = isset($_POST['pet_nome'])  && !empty($_POST['pet_nome']) ? $_POST['pet_nome'] : null;
            $des_especie_pet = isset($_POST['pet_especie'])  && !empty($_POST['pet_especie']) ? $_POST['pet_especie'] : null;
            $cod_usuario_pet = isset($_POST['pet_dono'])  && !empty($_POST['pet_dono']) ? $_POST['pet_dono'] : null;

            if (!$des_nome_pet) {
                $response['warning'] = "Nome do pet é obrigatório";
            } elseif (!$des_especie_pet) {
                $response['warning'] = "Especie do pet é obrigatório";
            } elseif(!$cod_usuario_pet && $_SESSION['usuario']['des_cargo_usr'] == 1) {
                $response['warning'] = "Dono do pet é obrigatório";
            } 
            
            $pet = $this->pet->getPet($id);
            if (!$pet) {
                $response['warning'] = "Pet não encontrado";
            } else {
                $dadosPet = array(
                    'des_nome_pet' => $des_nome_pet,
                    'des_especie_pet' => $des_especie_pet,
                    'cod_usuario_pet' => !empty($cod_usuario_pet) ? $cod_usuario_pet : $pet->cod_usuario_pet
                );
                $this->pet->atualizarPet($id, $dadosPet);
                $response['success'] = "Pet atualizado com sucesso";
            }

            header('Content-Type: application/json');
            echo json_encode($response);
        } catch (Exception $e) {
            header("HTTP/1.1 400 Bad Request");
            echo json_encode(array("error" => $e->getMessage()));
        }
    }

    public function deletarPet($id)
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
            $verificaAgendamento = $this->agendamento->verificaAgendamentoPet($id);
            if($verificaAgendamento) {
                $response['warning'] = "Não é possível deletar o pet pois existe um agendamento para ele.";
            } else {
                $this->pet->deletarPet($id);
                $response['success'] = "Usuário deletado com sucesso";
            }
            header('Content-Type: application/json');
            echo json_encode($response);
        } catch (Exception $e) {
            header("HTTP/1.1 404 Not Found");
            echo json_encode(array("error" => $e->getMessage()));
        }
    }
}
