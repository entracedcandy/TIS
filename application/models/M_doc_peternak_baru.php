<?php
class M_doc_peternak_baru extends CI_Model {
    public function insert_doc_peternak_baru($data) {
        return $this->db->insert('doc_peternak_baru', $data);
    }

    public function get_questions_by_page($page = 'doc_peternak_baru') {
        $this->db->where('page', $page);
        return $this->db->get('questions')->result_array();
    }
}