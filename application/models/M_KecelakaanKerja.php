<?php

defined('BASEPATH') OR exit('No direct script access allowed');
  
class M_KecelakaanKerja extends CI_Model{

    function kkInfo($id_rec_h){
        $query = "  SELECT
                        (SELECT a.value FROM rec_form_d a, m_form_pertanyaan b WHERE a.id_rec_form_h = $id_rec_h AND a.id_pertanyaan = b.id_pertanyaan AND b.seq = 1) AS tanggal,
                        (SELECT a.value FROM rec_form_d a, m_form_pertanyaan b WHERE a.id_rec_form_h = $id_rec_h AND a.id_pertanyaan = b.id_pertanyaan AND b.seq = 3) AS kronologi
                ";
                    
        $result = $this->db->query($query);
        
        return $result;
    }

    function kkKorban($id_rec_h){
        $query = "  SELECT 
                        a.value,
                        b.seq
                    FROM
                        rec_form_d a,
                        m_form_pertanyaan b
                    WHERE
                        a.id_rec_form_h = $id_rec_h AND
                        a.id_pertanyaan = b.id_pertanyaan AND
                        (b.seq > 1 AND b.seq < 3)
                    ORDER BY
                        a.id_rec_form_d
                ";
                    
        $result = $this->db->query($query);
        
        return $result;
    }

    function pelapor($sender){
        $query = "  SELECT
                        caption AS nama,
                        CASE
                            WHEN nik <> '' THEN nik
                            ELSE no_reg
                        END as nopeg,
                        vendor,
                        department,
                        plant
                    FROM
                        z_master_user
                    WHERE
                        no_hp = $sender
                ";
                    
        $result = $this->db->query($query);
        
        return $result;
    }
}
?>