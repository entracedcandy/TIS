<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class M_master_pakan extends CI_Model {
    public function insert_master_pakan($data) {
        return $this->db->insert('master_pakan', $data);
    }

    public function get_pakan_by_id($id) {
        return $this->db->get_where('master_pakan', ['master_pakan_id' => $id])->row_array();
    }

    public function update_pakan_data($id, $data) {
        $this->db->where('master_pakan_id', $id);
        return $this->db->update('master_pakan', $data);
    }
    
}