<?php

defined('BASEPATH') OR exit('No direct script access allowed');
  
class M_DI_2023_00001 extends CI_Model{
    public function __construct(){
        // $this->load->database();
        // $this->pellet = $this->load->database('pellet', TRUE);
    }
    
    function readFunction($funcName, $param, $state){
        if($param !== ""){
            $value = $this->$funcName($param, $state);
        }else{
            $value = $this->$funcName($state);
        }
        return $value;
    }

    function insert_rrm($state){
        $query = "SELECT '' AS VALUE UNION SELECT 'FULL' AS VALUE UNION SELECT 'HALF' AS VALUE";
        $result = $this->db->query($query);
        return $result->result();
    }

}
?>