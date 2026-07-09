<?php
class M_seminar extends CI_Model {
    public function insert_seminar($data) {
        return $this->db->insert('seminar', $data);
    }

    public function get_questions_by_page($page = 'seminar') {
        $this->db->where('page', $page);
        return $this->db->get('questions')->result_array();
    }
}