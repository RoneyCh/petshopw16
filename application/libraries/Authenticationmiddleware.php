<?php 
// autenticação JWT middleware
// Path: backend/application/middlewares/AuthenticationMiddleware.php
require_once 'vendor/autoload.php';
use \Firebase\JWT\JWT;
use \Firebase\JWT\Key;

class Authenticationmiddleware {

    public function verificarToken($token) {
        $chave_secreta = "eq-@*M+)n>YRt@#mA`+Gvfm+'&!4w-";
        try {
            $decoded = JWT::decode($token, new Key($chave_secreta, 'HS256'));
            return $decoded;
        } catch (Exception $e) {
            return false;
        }
    }
    
}

?>