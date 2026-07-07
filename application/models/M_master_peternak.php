<?php
class M_master_peternak extends CI_Model {
    public function insert_master_peternak($data) {
        return $this->db->insert('master_peternak', $data);
    }

    public function get_questions_by_page($page = 'master_peternak') {
        $this->db->where('page', $page);
        return $this->db->get('questions')->result_array();
    }
}