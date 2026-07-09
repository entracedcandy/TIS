<?php

defined('BASEPATH') OR exit('No direct script access allowed');
  
class M_Report_Form extends CI_Model{

    function getDataDash($date_start, $date_end){
        $query = "  SELECT
                        x.*,
                        CASE
                            WHEN x.status = 0.1 THEN 'Proses Memilih User Approval'
                            WHEN x.status >= 1 AND x.status < 10 AND x.on_approval = 0 THEN 'Pemohon Sedang Mengisi Form'
                            WHEN x.status >= 1 AND x.on_approval = 1 THEN CONCAT(x.jabatan, ' - ', x.user_now, ' Sedang Mengisi Informasi Tambahan')
                            WHEN x.status >= 10 THEN CONCAT('Menunggu Approval ', x.jabatan, ' - ', x.user_now)
                        END AS status_exp
                    FROM
                        (
                            SELECT
                                z.*,
                                (SELECT bc.caption FROM rec_form_app ba, m_form_alur_app bb, z_master_user bc WHERE ba.id_rec_form_h = z.id_rec_form_h AND ba.seq = z.seq_app AND ba.id_alur = bb.id_alur AND bb.id_user_app = bc.id_user) AS user_now,
                                (SELECT bb.caption FROM rec_form_app ba, m_form_alur_app bb WHERE ba.id_rec_form_h = z.id_rec_form_h AND ba.seq = z.seq_app AND ba.id_alur = bb.id_alur ) AS jabatan,
                                CONCAT('https://cpipga.com/assets/form_data/stream_data/work_permit(', z.id_rec_form_h, ').pdf') AS link
                            FROM
                                (
                                    SELECT
                                        a.id_rec_form_h,
                                        a.no_form,
                                        DATE_FORMAT(DATE_ADD(a.date_created, INTERVAL 7 HOUR), '%d %M %Y %H:%i') AS form_created,
                                        (SELECT status FROM rec_form_session_log WHERE id_rec_h = a.id_rec_form_h ORDER BY id_session_log DESC LIMIT 1) AS status,
                                        (SELECT id_pertanyaan FROM rec_form_session_log WHERE id_rec_h = a.id_rec_form_h ORDER BY id_session_log DESC LIMIT 1) AS id_pertanyaan,
                                        (SELECT id_opsi FROM rec_form_session_log WHERE id_rec_h = a.id_rec_form_h ORDER BY id_session_log DESC LIMIT 1) AS id_opsi,
                                        (SELECT seq_app FROM rec_form_session_log WHERE id_rec_h = a.id_rec_form_h ORDER BY id_session_log DESC LIMIT 1) AS seq_app,
                                        (SELECT on_approval FROM rec_form_session_log WHERE id_rec_h = a.id_rec_form_h ORDER BY id_session_log DESC LIMIT 1) AS on_approval,
                                        b.caption AS pemohon,
                                        b.vendor
                                    FROM
                                        rec_form_h a,
                                        z_master_user b
                                    WHERE
                                        a.created = 1 AND
                                        a.id_form = 5 AND
                                        a.id_user_pemohon <> 1 AND
                                        (CAST(a.date_created AS DATE) BETWEEN '$date_start' AND '$date_end') AND
                                        a.id_user_pemohon = b.id_user AND 
                                        a.plant = '1742'
                                ) z
                            ORDER BY
                                z.form_created
                        ) x
                ";
                    
        $result = $this->db->query($query);
        
        return $result;
    }
}
?>