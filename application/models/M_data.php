<?php

defined('BASEPATH') OR exit('No direct script access allowed');
  
class M_data extends CI_Model{

    public function __construct(){
        $this->load->database();
        $this->sso = $this->load->database('sso', TRUE);
    }

	function getTotalHari($n, $k, $s, $e){
        $query = "  SELECT 
                        COUNT(az.umur_hari) as totalHari 
                    FROM 
                        data_input az 
                    WHERE 
                        az.active = 'y' AND
                        az.umur_minggu = $n AND
                        az.id_kandang = '$k' AND
                        (az.tanggal_input >= '$s' AND az.tanggal_input <= '$e')
					";
        $result = $this->db->query($query);
        return $result;
    }

    // function getData($id){
    //     $this->db->select('username, password');
    //     $this->db->from('master_user');
    //     $this->db->where('id_user', $id);
    //     return $this->db->get();
    // }

    function getData_old($id){
        $query = "  SELECT 
                        username,
                        password,
                        nik,
                        no_reg
                    FROM 
                        master_user
                    WHERE id_user = '$id'
        ";

        $datacpi = $this->db->query($query);
        $datacpidat = $this->db->query($query)->result();

        $nikdat = ''; $regdat = '';

        foreach ($datacpidat as $row){
            $user = $row->username;
            $pass = $row->password;
            $nikdat = $row->nik;
            $regdat = $row->no_reg;
        }

        $query = "  SELECT 
                        a.nama,
                        a.noreg,
                        a.nik,
                        a.group_id,
                        a.plant,
                        b.departmen,
                        b.hmis_login_user,
                        b.hmis_login_pass,
                        b.integrasi_login_user,
                        b.integrasi_login_pass,
                        '$user' as username,
                        '$pass' as password
                    FROM 
                        master_user a , 
                        master_group b
                    WHERE a.nik = '$nikdat' AND a.noreg = '$regdat' AND a.group_id = b.id 
        ";

        $datasso = $this->sso->query($query);

        return $datasso;
    }

    function getData($id){
        $query = "  SELECT 
                        a.nama,
                        a.noreg,
                        a.nik,
                        a.group_id,
                        a.plant,
                        (SELECT departmen FROM master_group WHERE id = a.group_id) AS departmen,
                        (SELECT hmis_login_user FROM master_group WHERE id = a.group_id) AS hmis_login_user,
                        (SELECT hmis_login_pass FROM master_group WHERE id = a.group_id) AS hmis_login_pass,
                        (SELECT integrasi_login_user FROM master_group WHERE id = a.group_id) AS integrasi_login_user,
                        (SELECT integrasi_login_pass FROM master_group WHERE id = a.group_id) AS integrasi_login_pass,
                        (SELECT username FROM cpi.master_user WHERE nik = a.nik AND no_reg = a.noreg) AS username,
	                    (SELECT password FROM cpi.master_user WHERE nik = a.nik AND no_reg = a.noreg) AS password
                    FROM 
                        master_user a
                    WHERE 
                        a.nik = '$id' OR a.noreg = '$id'
        ";

        $datasso = $this->sso->query($query);

        return $datasso;
    }

    function getPhone($id){
        $query = "  SELECT 
                        a.phone
                    FROM 
                        master_user a
                    WHERE 
                        a.nik = '$id' OR a.noreg = '$id'
        ";

        $datasso = $this->sso->query($query);

        return $datasso;
    }

}
?>