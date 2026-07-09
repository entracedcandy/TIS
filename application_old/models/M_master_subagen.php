<?php
class M_master_subagen extends CI_Model {
    public function insert_master_subagen($data) {
        return $this->db->insert('master_subagen', $data);
    }

    public function get_questions_by_page($page = 'master_subagen') {
        $this->db->where('page', $page);
        return $this->db->get('questions')->result_array();
    }
}