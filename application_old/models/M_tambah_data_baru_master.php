<?php
class M_tambah_data_baru_master extends CI_Model {
    public function get_questions_by_page($page = 'master_tambah_data_baru') {
        $this->db->where('page', $page);
        return $this->db->get('questions')->result_array();
    }
    

}