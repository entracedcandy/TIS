<?php

defined('BASEPATH') OR exit('No direct script access allowed');
  
class M_Home extends CI_Model{
    
    function getPass($un){
        $this->db->select('id_user,password');
        $this->db->from('z_master_user');
        $this->db->where('username', $un);
        return $this->db->get();
    }

    function getIdUser($token){
        $this->db->select('id_user');
        $this->db->from('log_user');
        $this->db->where('token', $token);
        return $this->db->get();
    }

    function logWrite($id_user, $token, $status){
        $query = "  INSERT INTO log_user (id_user, status, token, date_log)
					VALUES ($id_user, '$status', '$token', NOW());
                    ";
                    
        $result = $this->db->query($query);
        
        return $result;
    }

    function checkUser($user){
        $query = "  SELECT
                        id_user, no_hp
                    FROM
                        z_master_user
                    WHERE
                        username = '$user' OR no_hp = '$user'
                    ";
                    
        $result = $this->db->query($query);
        
        return $result;
    }

    function sendOtp($nomor, $otp){
        $data = array(
            'nomor' => $nomor,
            'otp' => $otp,
            'time_send' => date("Y-m-d H:i:s"),
            'status' => 0
        );
        
        $this->db->insert('otp_send', $data);
        return $this->db->affected_rows();
    }

    function checkOtp($nomor, $otp){
        $query = "  SELECT 
                        *
                    FROM
                        otp_send a
                    WHERE
                        a.nomor = '$nomor' AND 
                        a.otp = '$otp' AND 
                        status = 0 AND
                        time_send >= DATE_SUB(NOW(), INTERVAL 1 MINUTE);
                    ";
                    
        $result = $this->db->query($query);
        return $result;
    }

    function updatePass($id, $pass){
        $data = array(
            'password' => $pass,
            'reset_pass' => 1
        );
        
        $this->db->where('id_user', $id);
        $this->db->update('z_master_user', $data);
        return $this->db->affected_rows();
    }
}
?>