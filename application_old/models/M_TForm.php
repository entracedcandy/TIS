<?php

defined('BASEPATH') OR exit('No direct script access allowed');
  
class M_TForm extends CI_Model{

    function trackData($no_form){
        $query = "  SELECT
                        a.*,
                        b.*,
                        c.caption as jabatan,
                        d.caption as user_approval,
                        (SELECT caption FROM z_master_user WHERE id_user = a.id_user_pemohon) AS pemohon,
                        DATE_ADD(a.date_created, INTERVAL 7 HOUR) AS form_created,
                        DATE_ADD(b.date_approved, INTERVAL 7 HOUR) AS date_approved_user
                    FROM
                        rec_form_h a,
                        rec_form_app b,
                        m_form_alur_app c,
                        z_master_user d
                    WHERE
                        a.no_form = '$no_form' AND
                        a.id_rec_form_h = b.id_rec_form_h AND
                        b.id_alur = c.id_alur AND
                        c.id_user_app = d.id_user
                    ORDER BY
                        b.loop_app,
                        b.seq
                ";
                    
        $result = $this->db->query($query);
        
        return $result;
    }
}
?>