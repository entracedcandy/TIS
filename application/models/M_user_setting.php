<?php

defined('BASEPATH') OR exit('No direct script access allowed');
  
class M_user_setting extends CI_Model{
    
    function data_user($d, $cc, $gu){
        $query = "  SELECT
                        caption,
                        department,
                        CASE 
                            WHEN vendor = 'CPI' THEN 'PERMANENT'
                            ELSE 'NON PERMANENT'
                        END AS status,
                        vendor,
                        nik,
                        no_reg
                    FROM
                        z_master_user
                    WHERE
                        group_user <> 'administrator' AND vendor <> ''
                    ";
        
        if($gu === "admin_nasional" || $gu === "administrator"){
            //Biarkan Kosong
        }elseif($gu === "admin_regional"){
            $query .= " AND (SELECT regional FROM master_department WHERE cost_center = a.cost_center) = '$cc'";
        }elseif($gu === "admin_plant" || $gu === "admin_group_dept"){
            $query .= " AND (SELECT plant FROM master_department WHERE cost_center = a.cost_center) = '$cc'";

            if($gu === "admin_group_dept"){
                $query .= " AND (SELECT group_department FROM master_department WHERE cost_center = a.cost_center) = '$d'";
            }
        }else{
            $query .= " AND a.department = '$d' AND a.cost_center = '$cc'";
        }

        $query .= " ORDER BY status DESC, department, caption";

        $result = $this->db->query($query);
        return $result;
    }
    
    function department(){
        // return $this->db->select('kode')->from('m_check_point')->order_by('kode', 'DESC')->limit(1)->get();
    }
}
?>