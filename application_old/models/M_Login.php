<?php

defined('BASEPATH') OR exit('No direct script access allowed');
  
class M_Login extends CI_Model{
    
    function getDept(){
        $this->db->select('*');
        $this->db->from('master_department');
        return $this->db->get();
    }

    function getVendor(){
        $this->db->select('*');
        $this->db->from('master_vendor');
        return $this->db->get();
    }
}
?>