<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class M_Check_In extends CI_Model {

    public function insert_check_in($data) {
        return $this->db->insert('user_check_ins', $data);
    }
}