<?php

defined('BASEPATH') OR exit('No direct script access allowed');
  
class M_Profile extends CI_Model{

    function getMenuHead($token){
        $query = "  SELECT 
                        d.caption,
                        d.urutan,
                        d.src,
                        d.icon,
                        d.id_menu,
                        d.has_sub
                    FROM
                        log_user a,
                        z_master_user b,
                        z_master_akses c,
                        z_master_menu d
                    WHERE
                        a.token = '$token' AND
	                    a.status = 'LOGIN' AND
                        a.id_user = b.id_user AND
                        b.group_user = c.group_user AND
                        c.id_menu = d.id_menu AND
                        d.menu_type = 'head'
                    ORDER BY submenu
                    ";
                    
        $result = $this->db->query($query);
        
        return $result;
    }

    function getMenuChild($token){
        $query = "  SELECT 
                        d.caption,
                        d.submenu,
                        d.urutan,
                        d.src,
                        d.icon
                    FROM
                        log_user a,
                        z_master_user b,
                        z_master_akses c,
                        z_master_menu d
                    WHERE
                        a.token = '$token' AND
	                    a.status = 'LOGIN' AND
                        a.id_user = b.id_user AND
                        b.group_user = c.group_user AND
                        c.id_menu = d.id_menu AND
                        d.menu_type != 'head'
                    ORDER BY submenu
                    ";
                    
        $result = $this->db->query($query);
        
        return $result;
    }

    function getName($token){
        $query = "  SELECT 
                        b.caption
                    FROM
                        log_user a,
                        z_master_user b
                    WHERE
                        a.token = '$token' AND
                        a.id_user = b.id_user
                    ";
                    
        $result = $this->db->query($query);
        
        return $result;
    }

    function getUserInfo($token){
        $query = "  SELECT 
                        b.*
                    FROM
                        log_user a,
                        z_master_user b
                    WHERE
                        a.token = '$token' AND
                        a.id_user = b.id_user
                    ";
                    
        $result = $this->db->query($query);
        
        return $result;
    }

    function userValid($token){
        $query = "  SELECT 
                        CASE 
                            WHEN TIMEDIFF(NOW(), a.date_log) > TIME('12:00:00') THEN 'OUT'
                            ELSE 'STAY'
                        END AS validasi
                    FROM
                        log_user a
                    WHERE
                        a.token = '$token' AND a.status = 'LOGIN'
                    ";
                    
        $result = $this->db->query($query);
        
        return $result;
    }

    function getPass($id){
        $this->db->select('id_user,password');
        $this->db->from('z_master_user');
        $this->db->where('id_user', $id);
        return $this->db->get();
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

    function updateOtp($nomor, $otp){
        $data = array(
            'status' => 1
        );
        
        $this->db->where('nomor', $nomor);
        $this->db->where('otp', $otp);
        $this->db->update('otp_send', $data);
        return $this->db->affected_rows();
    }

    function updatePass($id, $pass){
        $data = array(
            'password' => $pass
        );
        
        $this->db->where('id_user', $id);
        $this->db->update('z_master_user', $data);
        return $this->db->affected_rows();
    }

    function updateNomorHP($nomor, $id){
        $data = array(
            'no_hp' => $nomor
        );
        
        $this->db->where('id_user', $id);
        $this->db->update('z_master_user', $data);
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
}
?>