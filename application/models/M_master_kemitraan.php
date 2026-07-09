<?php
class M_master_kemitraan extends CI_Model {
    public function insert_master_kemitraan($data) {
        return $this->db->insert('master_kemitraan', $data);
    }

    public function get_questions_by_page($page = 'master_kemitraan') {
        $this->db->where('page', $page);
        return $this->db->get('questions')->result_array();
    }
}