<?php
class M_master_agen extends CI_Model {
    public function insert_master_agen($data) {
        return $this->db->insert('master_agen', $data);
    }

    public function get_questions_by_page($page = 'master_agen') {
        $this->db->where('page', $page);
        return $this->db->get('questions')->result_array();
    }
}