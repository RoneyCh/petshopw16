<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');


class Usuario_model extends CI_Model {

    public function __construct() {
        parent::__construct();
    }

    public function listUsuarios() {
        $query = $this->db->select('cod_usuario_usr, des_email_usr, des_usuario_usr, des_cargo_usr');
        $query =$this->db->get('cad_usuario');
        return $query->result();
    }

    public function getUsuario($id) {
        $query = $this->db->get_where('cad_usuario', array('cod_usuario_usr' => $id));
        return $query->row();
    }

    public function criarUsuario($data) {
        $this->db->insert('cad_usuario', $data);
        return $this->db->insert_id();
    }

    public function atualizarUsuario($id, $data) {
        $this->db->where('cod_usuario_usr', $id);
        return $this->db->update('cad_usuario', $data);
    }

    public function deletarUsuario($id) {
        $this->db->where('cod_usuario_usr', $id);
        return $this->db->delete('cad_usuario');
    }

    public function buscarUsuarioPorEmail($email, $id = null) {
        try {
            $this->db->select('cod_usuario_usr, des_email_usr, des_senha_usr, des_usuario_usr, des_cargo_usr');
            $this->db->where('des_email_usr', $email);
            if ($id) {
                $this->db->where('cod_usuario_usr !=', $id);
            }
            $query = $this->db->get('cad_usuario');
            return $query->row();
        } catch (Exception $e) {
            return false;
        }
    }
}

