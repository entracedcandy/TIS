<?php

defined('BASEPATH') OR exit('No direct script access allowed');
  
class M_dynamic_input extends CI_Model{

    function getDI($id_di){
        $this->db->select('*');
        $this->db->from('di_h');
        $this->db->where('id_di', $id_di);
        return $this->db->get();
    }

    function getFuncInput($id_di){
        $this->db->select('insert_query');
        $this->db->from('di_h');
        $this->db->where('id_di', $id_di);
        return $this->db->get();
    }

    function getMM($id_di){
        $this->db->select('*');
        $this->db->from('master_menu');
        $this->db->like('src', $id_di);
        return $this->db->get();
    }

    function getElmDI($id_di, $col){
        $this->db->select('*');
        $this->db->from('di_elm');
        $this->db->where('id_di', $id_di);
        $this->db->where('elm_col', $col);
        $this->db->order_by('elm_seq');
        return $this->db->get();
    }
    
    function getMaxCol($id_di){
        $this->db->select_max('elm_col');
        $this->db->from('di_elm');
        $this->db->where('id_di', $id_di);
        return $this->db->get();
    }
    
    function getFuncDI($id_di){
        $this->db->select('*');
        $this->db->from('di_func');
        $this->db->where('id_di', $id_di);
        $this->db->order_by('target');
        $this->db->order_by('seq');
        return $this->db->get();
    }

    function readQuery($query){
        $result = $this->db->query($query);
        return $result;
    }
}
?>