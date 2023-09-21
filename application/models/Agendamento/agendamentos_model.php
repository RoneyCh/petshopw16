<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');


class Agendamentos_model extends CI_Model {

    public function __construct() {
        parent::__construct();
    }

    public function listAgendamentos($cod_usuario = null) {
        $this->db->select('cod_agendamento_agd, dta_agendamento_agd, des_nome_pet, des_especie_pet, cod_usuario_agd, cod_pets_agd, cu.des_usuario_usr agendado_por, c.des_usuario_usr dono_pet');
        $this->db->from('tab_agendamentos');
        $this->db->join('cad_usuario cu', 'cod_usuario_agd = cu.cod_usuario_usr');
        $this->db->join('cad_pets', 'cod_pets_agd = cod_pets_pet');
        $this->db->join('cad_usuario c', 'cod_usuario_pet = c.cod_usuario_usr');
        $this->db->order_by('dta_agendamento_agd', 'ASC');

        if ($cod_usuario) {
            $this->db->where('c.cod_usuario_usr', $cod_usuario);
        }
        $query = $this->db->get();
        
        return $query->result(); 

    }

    public function getAgendamento($id) {
        $query = $this->db->get_where('tab_agendamentos', array('cod_agendamento_agd' => $id));
        return $query->row();
    }

    public function criarAgendamento($data) {
        $this->db->insert('tab_agendamentos', $data);
        return $this->db->insert_id();
    }

    public function atualizarAgendamento($id, $data) {
        $this->db->where('cod_agendamento_agd', $id);
        return $this->db->update('tab_agendamentos', $data);
    }

    public function deletarAgendamento($id) {
        $this->db->where('cod_agendamento_agd', $id);
        return $this->db->delete('tab_agendamentos');
    }

    public function verificaAgendamentoPet($id) {
        $query = $this->db->get_where('tab_agendamentos', array('cod_pets_agd' => $id));
        return $query->row();
    }

}

