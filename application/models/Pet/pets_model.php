<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');


class Pets_model extends CI_Model {

    public function __construct() {
        parent::__construct();
    }

    public function listPets($cod_usuario = null) {
        $this->db->select('cod_pets_pet, des_nome_pet, des_especie_pet, cod_usuario_pet, des_usuario_usr, des_email_usr, des_cargo_usr');
        $this->db->from('cad_pets');
        $this->db->join('cad_usuario', 'cod_usuario_pet = cod_usuario_usr');

        if ($cod_usuario) {
            $this->db->where('cod_usuario_pet', $cod_usuario);
        }
        $query = $this->db->get();
        
        return $query->result();

    }

    public function getPet($id) {
        $query = $this->db->get_where('cad_pets', array('cod_pets_pet' => $id));
        return $query->row();
    }

    public function criarPet($data) {
        $this->db->insert('cad_pets', $data);
        return $this->db->insert_id();
    }

    public function atualizarPet($id, $data) {
        $this->db->where('cod_pets_pet', $id);
        return $this->db->update('cad_pets', $data);
    }

    public function deletarPet($id) {
        $this->db->where('cod_pets_pet', $id);
        return $this->db->delete('cad_pets');
    }

    public function getPetPorUsuario($id) {
        $this->db->where('cod_usuario_pet', $id);
        return $this->db->get('cad_pets')->result();
    }

}

