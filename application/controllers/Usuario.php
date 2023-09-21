<?php if (!defined('BASEPATH')) exit('No direct script access allowed');
require_once 'vendor/autoload.php';
require_once APPPATH . 'controllers/BaseController.php';

use \Firebase\JWT\JWT;
use \Firebase\JWT\Key;

class Usuario extends BaseController
{
    public $base_url;
    public function __construct()
    {
        parent::__construct();
        $this->load->library('authenticationmiddleware');
        $this->load->model('Usuario/usuario_model', 'usuario');
        $this->load->model('Pet/pets_model', 'pet');
        $this->base_url = $this->config->item('base_url');
    }

    public function index()
    {
        $this->load->view('Usuario/login');
    }

    public function login()
    {
        try {
            $response = array();
            $des_email_usr = isset($_POST['des_email'])  && !empty($_POST['des_email']) ? $_POST['des_email'] : null;
            $des_senha_usr = isset($_POST['des_senha'])  && !empty($_POST['des_senha']) ? $_POST['des_senha'] : null;
            $cod_usuario_usr = isset($_POST['cod_usuario_usr'])  && !empty($_POST['cod_usuario_usr']) ? $_POST['cod_usuario_usr'] : null;

            if (!$des_email_usr || !$des_senha_usr) {
                $response['warning'] = "E-mail e senha são obrigatórios";
            } else {
            $usuario = $this->usuario->buscarUsuarioPorEmail($des_email_usr, $cod_usuario_usr);
            if (!$usuario) {
                $response['warning'] = "Usuário não encontrado";
            } else {

            $compara_senha = password_verify($des_senha_usr, $usuario->des_senha_usr);
            if (!$compara_senha) {
                $response['warning'] = "Senha incorreta";
            } else {
                $token = $this->gerarToken($usuario);
                $des_email_usr = $usuario->des_email_usr;
                $cod_usuario_usr = $usuario->cod_usuario_usr;
                $des_usuario_usr = $usuario->des_usuario_usr;
                $des_cargo_usr = $usuario->des_cargo_usr;
                $dadosUsuario = array(
                    'des_email_usr' => $des_email_usr,
                    'cod_usuario_usr' => $cod_usuario_usr,
                    'des_usuario_usr' => $des_usuario_usr,
                    'des_cargo_usr' => $des_cargo_usr
                );

                setcookie(
                    $name = "token",
                    $value = $token,
                    $options = time() + 86400,
                    $path = "/",
                    $domain = "",
                    $secure = true,
                    $httponly = true
                );

                $_SESSION['usuario'] = $dadosUsuario;

                //header('Location: /Home');
        } 
    }
    }
            header('Content-Type: application/json');
            echo json_encode($response);
        } catch (Exception $e) {
            header("HTTP/1.1 400 Bad Request");
            echo json_encode(array("error" => $e->getMessage()));
        }
    }

    public function logout()
    {
        try {
            setcookie(
                $name = "token",
                $value = "",
                $options = time() + 86400,
                $path = "/",
                $domain = "",
                $secure = true,
                $httponly = true
            );
            session_unset();
            session_destroy();

            header('Location: /Usuario');
        } catch (Exception $e) {
            header("HTTP/1.1 400 Bad Request");
            echo json_encode(array("error" => $e->getMessage()));
        }
    }

    private function gerarToken($usuario)
    {
        try {
            $chave_secreta = "eq-@*M+)n>YRt@#mA`+Gvfm+'&!4w-";

            $token_data = array(
                "cod_usuario_usr" => $usuario->cod_usuario_usr,
                "des_email_usr" => $usuario->des_email_usr,
                "exp" => strtotime("+1 day")
            );

            $token = JWT::encode($token_data, $chave_secreta, 'HS256');

            return $token;
        } catch (Exception $e) {
            return false;
        }
    }

    public function listUsuarios()
    {
        $token = isset($_COOKIE['token']) ? $_COOKIE['token'] : null;
        $decoded = $token !== null ? $this->authenticationmiddleware->verificarToken($token) : false;
        if (!$decoded) {
            $this->load->view('error_view');
            return;
        }
        $data['dados'] = $this->usuario->listUsuarios();

        $this->load->view('Usuario/usuario', $data);
    }

    public function getUsuario($id)
    {
        try {
            $token = isset($_COOKIE['token']) ? $_COOKIE['token'] : null;
            $decoded = $token !== null ? $this->authenticationmiddleware->verificarToken($token) : false;
            if (!$decoded) {
                $this->load->view('error_view');
                return;
            }
            $user = $this->usuario->getUsuario($id);
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

    public function criarUsuario()
    {
        try {
            if (empty($_POST['des_usuario']) || empty($_POST['des_email']) || empty($_POST['des_senha'])) {
                $response = ['warning' => 'Preencha todos os campos.'];
            } elseif (!filter_var($_POST['des_email'], FILTER_VALIDATE_EMAIL)) {
                $response = ['warning' => 'E-mail inválido.'];
            } else {
                if (empty($_POST['des_cargo']) || !isset($_POST['des_cargo'])) {
                    $_POST['des_cargo'] = 0;
                }

                $verificaEmailJaCadastrado = $this->usuario->buscarUsuarioPorEmail($_POST['des_email']);

                if ($verificaEmailJaCadastrado) {
                    $response = ['warning' => 'E-mail já cadastrado.'];
                } else {
                    $usuario = [
                        'des_usuario_usr' => $_POST['des_usuario'],
                        'des_email_usr' => $_POST['des_email'],
                        'des_senha_usr' => password_hash($_POST['des_senha'], PASSWORD_DEFAULT),
                        'des_cargo_usr' => $_POST['des_cargo']
                    ];

                    $usuarioId = $this->usuario->criarUsuario($usuario);

                    if ($usuarioId) {
                        $response = ['success' => 'Usuário criado com sucesso.'];
                    } else {
                        $response = ['error' => 'Ocorreu um erro ao criar o usuário.'];
                    }
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
                $user = $this->usuario->getUsuario($cod);
                if (!$user) {
                    throw new Exception("Usuário não encontrado");
                } else {
                    $data['dados'] = $user;
                }
            } else {
                $data['dados'] = [];
            }
            $this->load->view('Cadastro/cadastro_usuario', $data);
        } catch (Exception $e) {
            header("HTTP/1.1 404 Not Found");
            echo json_encode(array("error" => $e->getMessage()));
        }
    }

    public function atualizarUsuario($id)
    {
        try {
            $response = array();
            $des_usuario_usr = isset($_POST['des_usuario'])  && !empty($_POST['des_usuario']) ? $_POST['des_usuario'] : null;
            $des_email_usr = isset($_POST['des_email'])  && !empty($_POST['des_email']) ? $_POST['des_email'] : null;
            $des_senha_antiga = isset($_POST['des_senha'])  && !empty($_POST['des_senha']) ? $_POST['des_senha'] : null;
            $des_senha_nova = isset($_POST['des_senha_nova'])  && !empty($_POST['des_senha_nova']) ? $_POST['des_senha_nova'] : null;
            $cod_usuario_usr = isset($id)  && !empty($id) ? $id : null;
            $des_cargo_usr = isset($_POST['des_cargo'])  && !empty($_POST['des_cargo']) ? $_POST['des_cargo'] : null;

            if (!$des_email_usr) {
                $response['warning'] = "E-mail é obrigatório";
            } elseif (!$des_senha_antiga && ($_SESSION['usuario']['des_cargo_usr'] == 0)) {
                $response['warning'] = "Senha é obrigatória";
            } else {
                $usuario_email = $this->usuario->buscarUsuarioPorEmail($des_email_usr, $cod_usuario_usr);
                $usuario = $this->usuario->getUsuario($id);
                if ($_SESSION['usuario']['cod_usuario_usr'] == $usuario->cod_usuario_usr && !$des_senha_antiga) {
                    $response['warning'] = "Senha é obrigatória";
                } else {
                    $compara_senha = password_verify($des_senha_antiga, $usuario->des_senha_usr);

                    if ($usuario_email) {
                        $response['warning'] = "E-mail já cadastrado!";
                    } elseif (!$usuario) {
                        $response['warning'] = "Usuário não encontrado";
                    } elseif (!$compara_senha && ($_SESSION['usuario']['des_cargo_usr'] == 0 || $_SESSION['usuario']['des_email_usr'] == $des_email_usr)) {
                        $response['warning'] = "Senha incorreta";
                    } else {
                        $token = isset($_COOKIE['token']) ? $_COOKIE['token'] : null;
                        $decoded = $token !== null ? $this->authenticationmiddleware->verificarToken($token) : false;
                        if (!$decoded) {
                            $this->load->view('error_view');
                            return;
                        }
                        $dadosUsuario = array(
                            'des_email_usr' => $des_email_usr,
                            'des_usuario_usr' => $des_usuario_usr,
                            'des_senha_usr' => password_hash($des_senha_nova, PASSWORD_DEFAULT),
                            'des_cargo_usr' => !empty($des_cargo_usr) ? $des_cargo_usr : 0
                        );
                        $this->usuario->atualizarUsuario($id, $dadosUsuario);
                        $response['success'] = "Usuário atualizado com sucesso";
                    }
                }
            }
            header('Content-Type: application/json');
            echo json_encode($response);
        } catch (Exception $e) {
            header("HTTP/1.1 400 Bad Request");
            echo json_encode(array("error" => $e->getMessage()));
        }
    }

    public function deletarUsuario($id)
    {
        try {
            $response = array();
            $token = isset($_COOKIE['token']) ? $_COOKIE['token'] : null;
            $decoded = $token !== null ? $this->authenticationmiddleware->verificarToken($token) : false;
            if (!$decoded) {
                $this->load->view('error_view');
                return;
            }
            $pets = $this->pet->getPetPorUsuario($id);
            if($pets) {
                $response['warning'] = "Usuário não pode ser deletado pois possui pets associados";
            } else {
                $this->usuario->deletarUsuario($id);
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
