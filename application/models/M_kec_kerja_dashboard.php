<?php

defined('BASEPATH') OR exit('No direct script access allowed');
  
class M_kec_kerja_dashboard extends CI_Model{
    
    function dokumenAktif($gu, $id_type, $ket){
        $query = "  SELECT
                    m1.id_detail as id, 
                    m2.jenis_dokumen as jenis, 
                    m3.tipe as tipe, 
                    m1.caption as judul, 
                    CASE
                        WHEN m1.durasi > 0 THEN CONCAT(m1.durasi, ' Bulan')
                        ELSE 'Tanpa Perpanjangan'
                    END as durasi, 
                    m1.sub_dept as departemen,
                    (SELECT '1' as OK FROM a_master_progress WHERE id_detail = m1.id_detail LIMIT 1) as progress,
                    m1.id_detail_parent as has_parent
                    FROM
                    a_master_det_doku m1, a_master_dokumen m2, a_master_type m3
                    WHERE
                    m1.id_doku = m2.id_doku
                    AND m1.id_tipe = m3.id_type
                    AND m1.this_active = 'y' ";
        
        if($id_type != "0" && $id_type != ""){
            $query .= "AND m3.id_type = '" . $id_type . "' ";
        }

        if($ket != ""){
            $query .= "AND m1.caption LIKE '%$ket%' ";
        }
        
        if($gu != "Administrator"){
            $query .= "AND m1.sub_dept LIKE '%$gu%' ";
        }

        $query .= "ORDER BY m2.jenis_dokumen ASC, m3.tipe ASC, m1.caption ASC";
        $result = $this->db->query($query);
        return $result;
    }

    function filterJudul(){
        $query = "  SELECT
                        id_type as id,
                        tipe as judul
                    FROM
                       a_master_type
                    ";
        
        // if($gu != "Administrator"){
        //     $query .= "AND m1.sub_dept LIKE '%$gu%' ";
        // }

        $result = $this->db->query($query);
        return $result;
    }

    function getTitleDoku($id_title){
        $query = "  SELECT
                        caption as title_doku
                    FROM
                        a_master_det_doku
                    WHERE
                        id_detail = '".$id_title."'
                    ";
    
        $result = $this->db->query($query);
        return $result;
    }

    function getDataLog($id_detail){
        $query = "  SELECT 
                        id_rec,
                        caption as judul,
                        detail as keterangan,
                        tanggal_buat_doku as createdoku,
                        (SELECT tanggal_jatuh_tempo FROM a_rec_doku_det WHERE id_rec = ma.id_rec ORDER BY id_rec_detail DESC LIMIT 1) as jatuh_tempo_terakhir,
                        active
                    FROM a_rec_doku ma
                    WHERE id_detail = '".$id_detail."'
                    ORDER BY active DESC, tanggal_buat_doku ASC
            ";

        $result = $this->db->query($query);
        return $result;
    }

    function getDataProg($id_detail){
        $query = "  SELECT seq as seq, nama_prog as namanya, desc_prog as deskripsi
                    FROM a_master_progress
                    WHERE id_detail = '".$id_detail."'
                    ORDER BY seq
            ";

        $result = $this->db->query($query);
        return $result;
    }

    function getEdit($id_detail){
        $query = "  SELECT
                    m1.id_detail as id, 
                    m2.jenis_dokumen as jenis, 
                    m3.tipe as tipe, 
                    m1.caption as judul, 
                    m1.durasi as durasi, 
                    m1.sub_dept as departemen,
                    m1.id_tipe as idtipe,
                    m1.id_doku as iddoku,
                    m1.sub_dept as iddept

                    FROM
                    a_master_det_doku m1, a_master_dokumen m2, a_master_type m3
                    WHERE
                    m1.id_doku = m2.id_doku
                    AND m1.id_tipe = m3.id_type 
                    AND m1.id_detail = '".$id_detail."' ";

        $result = $this->db->query($query);
        return $result;
    }

    function tambahMasDoku($value, $depart){
        $query = "  INSERT INTO a_master_det_doku
                        (id_tipe, id_doku, caption, durasi, sub_dept, this_active)
                    VALUES 
                        ('$value[1]','$value[0]','$value[2]','$value[3]','$depart','y')
					";

        $this->db->simple_query($query);
		$result = $this->db->affected_rows();
        
        return $result;
    }

    function ubahMasDokuUpdate($value){
        $query = "  UPDATE a_master_det_doku
                    SET caption = '".$value[0]."'
                    WHERE id_detail = '".$value[2]."'
					";

        $this->db->simple_query($query);
		$result = $this->db->affected_rows();
        
        return $result;
    }

    function dokuHistoryUpdate($value, $id_user, $caption, $department){
        $query = "  INSERT INTO a_upndel_history_master
                        (id_detail_master_det_doku, id_tipe_master_det_doku, id_doku_master_det_doku, caption_master_det_doku, durasi_master_det_doku, sub_dept_master_det_doku, user_input, nama_input, kelakuan, tanggal_input)
                    VALUES 
                        ('$value[2]','$value[4]','$value[3]','$value[0]','$value[1]','$value[5]', '$id_user', '$caption', 'Update', NOW())
					";

        $this->db->simple_query($query);
		$result = $this->db->affected_rows();
        
        return $result;
    }

    function ubahProgress($value){
        $query = "  UPDATE a_master_progress
                    SET nama_prog = '".$value[1]."', desc_prog = '".$value[2]."'
                    WHERE seq = '".$value[0]."' AND id_detail = '".$value[3]."'
					";

        $this->db->simple_query($query);
		$result = $this->db->affected_rows();
        
        return $result;
    }

    function progHistoryUpdate($value, $id_user, $caption, $department){
        $query = "  INSERT INTO a_upndel_history_master
                        (id_detail_master_progress, seq_master_progress, nama_prog_master_progress, desc_prog_master_progress, user_input, nama_input, kelakuan, tanggal_input)
                    VALUES 
                        ('$value[3]','$value[0]','$value[1]','$value[2]','$id_user', '$caption', 'Update', NOW())
					";

        $this->db->simple_query($query);
		$result = $this->db->affected_rows();
        
        return $result;
    }

// ---------------------- JIKA ID DETAIL PARENT JADI DI PAKAI!
    // function ubahMasDokuInsert($value){
    //     $query = "  INSERT INTO a_master_det_doku
    //                     (id_tipe, id_doku, caption, durasi, sub_dept, id_detail_parent, this_active)
    //                 VALUES 
    //                     ('$value[4]','$value[3]','$value[0]','$value[1]','$value[5]', '$value[2]' ,'y')
	// 				";

    //     $this->db->simple_query($query);
	// 	$result = $this->db->affected_rows();
        
    //     return $result;
    // }
// ---------------------- JIKA ID DETAIL PARENT JADI DI PAKAI!
    function tambahProgress($value){
        $query = "  INSERT INTO a_master_progress
                        (id_detail, seq, nama_prog, desc_prog)
                    VALUES 
                        ('".$value[3]."','".$value[0]."','".$value[1]."','".$value[2]."')
					";

        $this->db->simple_query($query);
		$result = $this->db->affected_rows();
        
        return $result;
    }

    function getStep($id_detail, $id_rec_detail){
        $query = "  SELECT
                        mp.seq AS step,
                        mp.nama_prog as prog,
                        (
                            SELECT
                                DATE(rp1.tanggal_create)
                            FROM
                                a_rec_progress AS rp1
                            WHERE
                                rp1.id_rec_detail = $id_rec_detail AND
                                rp1.id_progress = mp.id_progress
                        ) AS tgl_update,
                        (
                            SELECT
                                rp2.estimasi
                            FROM
                                a_rec_progress AS rp2
                            WHERE
                                rp2.id_rec_detail = $id_rec_detail AND
                                rp2.id_progress = mp.id_progress
                        ) AS tgl_estimasi,
                        (
                            SELECT
                                rp3.catatan
                            FROM
                                a_rec_progress AS rp3
                            WHERE
                                rp3.id_rec_detail = $id_rec_detail AND
                                rp3.id_progress = mp.id_progress
                        ) AS note
                    FROM
                        a_master_progress AS mp
                    WHERE
                        mp.id_detail = $id_detail  ";

        $result = $this->db->query($query);
        return $result;
    }

    function getMasDokuCek($value){
        $query = "  SELECT
                        COUNT(id_detail) as hasil
                    FROM
                        a_master_det_doku
                    WHERE 
                        id_detail = '".$value[2]."' AND
                        caption = '".$value[0]."' AND
                        durasi = '".$value[1]."'
                    ";
    
        $result = $this->db->query($query);
        return $result;
    }

    function judulMenu($id){
        return $query = $this->db->get_where('master_menu', array('id_menu' => $id))->row();
	}

    function cekSubMenu($idMenu){
        return $query = $this->db->get_where('master_menu', array('id_menu' => $idMenu))->row();
    }

    function dataReport($idReport){
        $this->db->select('*');
        $this->db->from('dm_report');
        $this->db->where('id_report', $idReport);
        return $this->db->get();
    }

    function getAllFunc($idMenu){
        $this->db->select('*');
        $this->db->from('dm_function');
        $this->db->where('id_menu', $idMenu);
        $this->db->order_by('seq', 'ASC');
        return $this->db->get();
    }

    function getAllElm($idMenu){
        $this->db->select('*');
        $this->db->from('dm_element');
        $this->db->where('id_report', $idMenu);
        $this->db->order_by('col', 'ASC');
        $this->db->order_by('seq', 'ASC');
        return $this->db->get();
    }

    function getJenisDokumen(){
        $this->db->select('*');
        $this->db->from('a_master_dokumen');
        return $this->db->get();
    }

    function getTipeDokumen($id_jenis){
        $this->db->select('*');
        $this->db->from('a_master_type');
        $this->db->where('id_doku', $id_jenis);
        return $this->db->get();
    }

    function getLevel($gu, $id_menu){
        $this->db->select('level_access');
        $this->db->from('master_user_d_access_right');
        $this->db->where('id_menu', $id_menu);
        $this->db->where('group_user', $gu);
        return $this->db->get();
    }

    function getLevel_new($gu, $id_menu){
        $this->db->select('level_access');
        $this->db->from('z_master_akses');
        $this->db->where('id_menu', $id_menu);
        $this->db->where('group_user', $gu);
        return $this->db->get();
    }    
}
?>