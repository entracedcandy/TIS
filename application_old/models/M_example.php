<?php

defined('BASEPATH') OR exit('No direct script access allowed');
  
class M_example extends CI_Model{

    function getTesterUser($token){
        $query = "  SELECT 
                        z.id_user,
                        z.group_user,
                        z.department,
                        z.caption as nama,
                        z.nik,
                        z.plant
                    FROM
                        z_master_user z,
                        log_user a
                    WHERE
                        a.token = '$token' AND
	                    a.status = 'LOGIN' AND
                        a.id_user = z.id_user
                    LIMIT 1;
                    ";
                    
        $result = $this->db->query($query);
        
        return $result;
    }

}
?>