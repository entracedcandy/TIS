<?php

defined('BASEPATH') OR exit('No direct script access allowed');
  
class M_Form extends CI_Model{

    function getDetailForm($id_rec_h){
        $query = "  SELECT
                        *
                    FROM
                        m_form
                    WHERE
                        id_form = $id_rec_h
                ";
                    
        $result = $this->db->query($query);
        
        return $result;
    }

    function getSeqApp($id_rec_h){
        $query = "  SELECT
                        a.seq,
                        b.id_user_app
                    FROM
                        rec_form_app a,
                        m_form_alur_app b
                    WHERE
                        a.id_rec_form_h = $id_rec_h AND
                        a.id_alur = b.id_alur AND
                        a.status = 0
                    ORDER BY
                        a.seq
                    LIMIT 1
                ";
                    
        $result = $this->db->query($query);
        
        return $result;
    }

    function getDeptFromRecH($id_rec_h){
        $query = "  SELECT
                        department, plant
                    FROM
                        rec_form_h
                    WHERE
                        id_rec_form_h = $id_rec_h
                ";
                    
        $result = $this->db->query($query);
        
        return $result;
    }

    function getPertanyaanForApp($id_form, $seq, $id_rec_h){
        $query = "  
                    SELECT
                        z.*,
                        (SELECT value FROM rec_form_d WHERE id_rec_form_h = $id_rec_h AND id_pertanyaan = z.id_pertanyaan) AS jawaban
                    FROM
                        (
                            SELECT
                                a.*
                            FROM
                                m_form_pertanyaan a
                            WHERE
                                a.id_form = $id_form AND
                                a.answer_by = $seq
                            ORDER BY
                                a.seq
                        ) z
                    WHERE
                        (SELECT value FROM rec_form_d WHERE id_rec_form_h = $id_rec_h AND id_pertanyaan = z.id_pertanyaan) IS NULL
                ";
                    
        $result = $this->db->query($query);
        
        return $result;
    }

    function getApprovalAvailable($id_rec_h, $no_hp){
        $query = "  
                    SELECT
                        a.*
                    FROM
                        rec_form_app a,
                        m_form_alur_app b,
                        z_master_user c
                    WHERE
                        a.id_rec_form_h = $id_rec_h AND
                        a.id_alur = b.id_alur AND
                        b.id_user_app = c.id_user AND
                        c.no_hp = '$no_hp' AND
                        a.status = 0
                    ORDER BY
                        a.seq
                    LIMIT 1
                ";
                    
        $result = $this->db->query($query);
        
        return $result;
    }

    function getPertanyaanApproval($id_form, $seq, $loop){
        $query = "  
                    SELECT
                        a.*,
                        b.tipe_jawaban
                    FROM
                        m_form_pertanyaan a,
                        m_form_tipe_jwb b
                    WHERE
                        a.id_form = $id_form AND
                        a.answer_by = $seq AND
                        a.on_approval = 1 AND
                        a.loop_app = $loop AND
                        a.tipe_jawaban = b.id_tipe_jwb
                    ORDER BY
                        a.seq
                ";
                    
        $result = $this->db->query($query);
        
        return $result;
    }

    function getJawabanById($id_rec_h, $id_pertanyaan){
        $query = "  
                    SELECT
                        a.*
                    FROM
                        rec_form_d a
                    WHERE
                        a.id_rec_form_h = $id_rec_h AND
                        a.id_pertanyaan = $id_pertanyaan
                ";
                    
        $result = $this->db->query($query);
        
        return $result;
    }

    function getApprovalBySeq($id_form, $dept, $plant, $seq){
        $query = "  SELECT
                        a.*,
                        b.caption AS nama,
                        b.no_hp
                    FROM 
                        m_form_alur_app a,
                        z_master_user b
                    WHERE
                        a.id_form = $id_form AND
                        a.department = '$dept' AND
                        a.plant = '$plant' AND
                        a.seq_app = $seq AND
                        a.id_user_app = b.id_user
                ";
                    
        $result = $this->db->query($query);
        
        return $result;
    }

    function getApprovalCtr($id_form, $dept, $plant){
        $query = "  SELECT
                        x.*
                    FROM
                        (
                            SELECT
                                z.*,
                                (SELECT COUNT(id_alur) FROM m_form_alur_app WHERE id_form = $id_form AND department = '$dept' AND plant = '$plant' AND seq_app = z.seq_app) ctr
                            FROM
                                (
                                    SELECT
                                        distinct(seq_app),
                                        caption
                                    FROM 
                                        m_form_alur_app
                                    WHERE
                                        id_form = $id_form AND
                                        department = '$dept' AND
                                        plant = '$plant' AND
                                        optional = 0
                                )z
                        )x
                    WHERE
                        x.ctr > 1
                    ORDER BY
	                    x.seq_app
                    ";
                    
        $result = $this->db->query($query);
        
        return $result;
    }

    function getApprovalNoChoose($id_form, $dept, $plant){
        $query = "  SELECT
                        x.*
                    FROM
                        (
                            SELECT
                                z.*,
                                (SELECT COUNT(id_alur) FROM m_form_alur_app WHERE id_form = $id_form AND department = '$dept' AND plant = '$plant' AND seq_app = z.seq_app) ctr
                            FROM
                                (
                                    SELECT
                                        distinct(seq_app),
                                        caption,
                                        id_user_app,
                                        id_alur
                                    FROM 
                                        m_form_alur_app
                                    WHERE
                                        id_form = $id_form AND
                                        department = '$dept' AND
                                        plant = '$plant' AND
                                        optional = 0
                                )z
                        )x
                    WHERE
                        x.ctr <= 1
                    ORDER BY
	                    x.seq_app
                    ";
                    
        $result = $this->db->query($query);
        
        return $result;
    }

    function getDeptQuestion($id_form){
        $query = "  SELECT
                        pertanyaan_dept
                    FROM
                        m_form
                    WHERE
                        id_form = $id_form
                    ";
                    
        $result = $this->db->query($query);
        
        return $result;
    }

    function getApprovalDept($id_form){
        $query = "  SELECT
                        DISTINCT(department)
                    FROM
                        m_form_alur_app
                    WHERE
                        id_form = $id_form
                    ";
                    
        $result = $this->db->query($query);
        
        return $result;
    }

    function getApprovalOption($seq_app, $id_form){
        $query = "  SELECT
                        *
                    FROM
                        m_form_alur_app
                    WHERE
                        seq_app = $seq_app AND
                        id_form = $id_form
                    ";
                    
        $result = $this->db->query($query);
        
        return $result;
    }

    function getUserByID($id){
        $query = "  SELECT 
                        a.id_user,
                        a.caption AS nama,
                        a.group_user_form AS group_user,
                        a.plant,
                        a.no_hp
                    FROM
                        z_master_user a
                    WHERE
                        a.id_user = '$id' AND
                        a.is_active >= 1
                    ";
                    
        $result = $this->db->query($query);
        
        return $result;
    }

    function getUser($nomor){
        $query = "  SELECT 
                        a.id_user,
                        a.caption AS nama,
                        a.group_user_form AS group_user,
                        a.plant,
                        CASE
                            WHEN a.nik != '' THEN a.nik
                            ELSE a.no_reg
                        END AS nopeg
                    FROM
                        z_master_user a
                    WHERE
                        a.no_hp = '$nomor' AND
                        a.is_active >= 1
                    ";
                    
        $result = $this->db->query($query);
        
        return $result;
    }

    function getValueRecD($id_rec_d){
        $query = "  SELECT 
                        a.value
                    FROM
                        rec_form_d a
                    WHERE
                        a.id_rec_form_d = $id_rec_d
                    ";
                    
        $result = $this->db->query($query);
        
        return $result;
    }

    function getCaptionForm($id_rec_h){
        $query = "  SELECT 
                        a.caption_form
                    FROM
                        rec_form_h a
                    WHERE
                        a.id_rec_form_h = '$id_rec_h'
                    ";
                    
        $result = $this->db->query($query);
        
        return $result;
    }

    function cekSession($nomor){
        $query = "  SELECT
                        a.*
                    FROM
                        rec_form_session a,
                        z_master_user b
                    WHERE
                        b.no_hp = '$nomor' AND
                        b.id_user = a.id_user
                    ORDER BY
                        a.id_session DESC
                    LIMIT 1
                    ";
                    
        $result = $this->db->query($query);
        
        return $result;
    }

    function getForm($nomor){
        $query = "set @rownum := 0";
        $this->db->query($query);

        $query = "  SELECT
                        @rownum := @rownum + 1 AS nomor,
                        z.title,
                        z.has_parent,
                        z.id_form
                    FROM
                        (
                            SELECT DISTINCT
                                c.title,
                                c.has_parent,
                                c.id_form,
                                c.seq
                            FROM
                                z_master_user a,
                                m_form_level_access b,
                                m_form c
                            WHERE
                                a.no_hp = '$nomor' AND
                                a.group_user_form = b.group_user AND
                                b.id_form = c.id_form AND
                                (CURDATE() BETWEEN c.date_start AND c.date_end)
                            ORDER BY
                                c.seq
                        ) z
                    ";
                    
        $result = $this->db->query($query);
        
        return $result;
    }

    function getSeqCaption($id_form, $id_pertanyaan){
        $query = "set @rownum := -1";
        $this->db->query($query);

        $query = "  SELECT
                        z.*
                    FROM
                        (
                            SELECT
                                @rownum := @rownum + 1 AS nomor,
                                a.*
                            FROM
                                m_form_pertanyaan a
                            WHERE
                                a.id_form = $id_form AND
                                a.as_caption = 1
                            ORDER BY
                                a.seq
                        ) z
                    WHERE 
                        z.id_pertanyaan = $id_pertanyaan
                    ";
                    
        $result = $this->db->query($query);
        
        return $result;
    }

    function getFormWithId($nomor, $id_form){
        $query = "set @rownum := 0";
        $this->db->query($query);

        $query = "  SELECT
                        @rownum := @rownum + 1 AS nomor,
                        z.title,
                        z.has_parent,
                        z.id_form
                    FROM
                        (
                            SELECT DISTINCT
                                c.title,
                                c.has_parent,
                                c.id_form
                            FROM
                                z_master_user a,
                                m_form_level_access b,
                                m_form c
                            WHERE
                                a.no_hp = '$nomor' AND
                                c.id_form = $id_form AND
                                a.group_user_form = b.group_user AND
                                b.id_form = c.id_form AND
                                (CURDATE() BETWEEN c.date_start AND c.date_end)
                        ) z
                    ";
                    
        $result = $this->db->query($query);
        
        return $result;
    }

    function getRefForm($id_form){
        $query = "set @rownum := 0";
        $this->db->query($query);

        $query = "  SELECT
                        @rownum := @rownum + 1 AS nomor,
                        z.*
                    FROM
                        (
                        SELECT
                            a.*
                        FROM
                            rec_form_h a
                        WHERE
                            a.id_form = $id_form AND
                            a.ref_to = 0
                        ) z
                    ";
                    
        $result = $this->db->query($query);
        
        return $result;
    }

    function getAllPertanyaan($id_form, $answer_by, $on_app, $iterasi){
        $query = "  SELECT
                        a.id_pertanyaan,
                        a.seq,
                        a.pertanyaan,
                        a.label_pertanyaan,
                        a.label_endfix,
                        a.section_seq,
                        a.section_name,
                        b.tipe_jawaban,
                        a.as_caption
                    FROM
                        m_form_pertanyaan a,
                        m_form_tipe_jwb b
                    WHERE
                        a.id_form = $id_form AND
                        a.tipe_jawaban = b.id_tipe_jwb AND
                        a.answer_by = $answer_by AND
                        a.on_approval = $on_app AND
                        a.loop_app = $iterasi
                    ORDER BY
                        a.seq
                    ";
                    
        $result = $this->db->query($query);
        
        return $result;
    }

    function getPertanyaan($id_pertanyaan){
        $query = "  SELECT
                        a.id_pertanyaan,
                        a.seq,
                        a.pertanyaan,
                        a.label_pertanyaan,
                        a.label_endfix,
                        a.section_seq,
                        a.section_name,
                        b.tipe_jawaban,
                        a.as_caption,
                        a.jumlah_file
                    FROM
                        m_form_pertanyaan a,
                        m_form_tipe_jwb b
                    WHERE
                        a.id_pertanyaan = $id_pertanyaan AND
                        a.tipe_jawaban = b.id_tipe_jwb
                    ORDER BY
                        a.seq
                    ";
                    
        $result = $this->db->query($query);
        
        return $result;
    }

    function getPertanyaanBySeqNumber($id_form, $seq_number){
        $query = "  SELECT
                        a.id_pertanyaan,
                        a.seq,
                        a.pertanyaan,
                        a.label_pertanyaan,
                        a.label_endfix,
                        a.section_seq,
                        a.section_name,
                        b.tipe_jawaban,
                        a.as_caption,
                        a.jumlah_file
                    FROM
                        m_form_pertanyaan a,
                        m_form_tipe_jwb b
                    WHERE
                        a.id_form = $id_form AND
                        a.seq LIKE $seq_number AND
                        a.tipe_jawaban = b.id_tipe_jwb
                    ORDER BY
                        a.seq
                    ";
                    
        $result = $this->db->query($query);
        
        return $result;
    }

    // function getPertanyaanBySeq($id_form, $seq, $answer_by, $on_app, $iterasi){
    //     $query = "set @rownum := 0";
    //     $this->db->query($query);

    //     $query = "  
    //                 SELECT 
    //                     z.*
    //                 FROM
    //                     (
    //                         SELECT
    //                             @rownum := @rownum + 1 AS nomor,
    //                             a.id_pertanyaan,
    //                             a.seq,
    //                             a.pertanyaan,
    //                             a.label_pertanyaan,
    //                             a.label_endfix,
    //                             a.section_seq,
    //                             a.section_name,
    //                             b.tipe_jawaban,
    //                             a.as_caption
    //                         FROM
    //                             m_form_pertanyaan a,
    //                             m_form_tipe_jwb b
    //                         WHERE
    //                             a.id_form = $id_form AND
    //                             a.tipe_jawaban = b.id_tipe_jwb AND
    //                             a.answer_by = $answer_by AND
    //                             a.on_approval = $on_app AND
    //                             a.loop_app = $iterasi
    //                         ORDER BY
    //                             a.seq
    //                     ) z
    //                 WHERE
    //                     z.nomor = $seq
    //                 ";
                    
    //     $result = $this->db->query($query);
        
    //     return $result;
    // }

    function getPertanyaanBySeq($id_form, $seq, $answer_by, $on_app, $iterasi){
        $query = "  
                    SELECT 
                        z.*
                    FROM
                        (
                            SELECT
                                a.id_pertanyaan,
                                a.seq,
                                a.pertanyaan,
                                a.label_pertanyaan,
                                a.label_endfix,
                                a.section_seq,
                                a.section_name,
                                b.tipe_jawaban,
                                a.as_caption
                            FROM
                                m_form_pertanyaan a,
                                m_form_tipe_jwb b
                            WHERE
                                a.id_form = $id_form AND
                                a.tipe_jawaban = b.id_tipe_jwb AND
                                a.answer_by = $answer_by AND
                                a.on_approval = $on_app AND
                                a.loop_app = $iterasi
                            ORDER BY
                                a.seq
                            LIMIT $seq
                        ) z
                    ORDER BY z.seq DESC
                    LIMIT 1
                    ";
                    
        $result = $this->db->query($query);
        
        return $result;
    }

    function getAppLoop($id_form){
        $query = "  SELECT DISTINCT
                        loop_app
                    FROM
                        m_form_pertanyaan
                    WHERE
                        id_form = $id_form
                    ";
                    
        $result = $this->db->query($query);
        
        return $result;
    }

    function getJawaban($id_rec_h, $id_pertanyaan){
        $query = "  SELECT
                        a.value,
                        a.sub_value,
                        a.id_rec_form_d
                    FROM
                        rec_form_d a
                    WHERE
                        a.id_rec_form_h = $id_rec_h AND
                        a.id_pertanyaan = $id_pertanyaan
                    ";
                    
        $result = $this->db->query($query);
        
        return $result;
    }

    function getOpsiJawaban($id_pertanyaan){
        $query = "  SELECT
                        a.seq_jawaban,
                        a.opsi_jawaban,
                        a.sub_pertanyaan,
                        a.id_opsi
                    FROM
                        m_form_opsi a
                    WHERE
                        a.id_pertanyaan = $id_pertanyaan
                    ORDER BY
                        a.seq_jawaban
                    ";
                    
        $result = $this->db->query($query);
        
        return $result;
    }

    // function getIDRecD($id_rec_h){
    //     $query = "  SELECT
    //                     id_rec_form_d
    //                 FROM
    //                     rec_form_d
    //                 WHERE
    //                     id_rec_form_h = $id_rec_h AND
    //                     value = ''
    //                 ";
                    
    //     $result = $this->db->query($query);
        
    //     return $result;
    // }

    function getIDRecD($id_rec_h, $id_pertanyaan){
        $query = "  SELECT
                        id_rec_form_d
                    FROM
                        rec_form_d
                    WHERE
                        id_rec_form_h = $id_rec_h AND
                        id_pertanyaan = $id_pertanyaan AND
                        value = ''
                    ";
                    
        $result = $this->db->query($query);
        
        return $result;
    }

    function getIDRecDwithPertanyaan($id_rec_h, $id_pertanyaan){
        $query = "  SELECT
                        id_rec_form_d
                    FROM
                        rec_form_d
                    WHERE
                        id_rec_form_h = $id_rec_h AND
                        id_pertanyaan = $id_pertanyaan
                    ";
                    
        $result = $this->db->query($query);
        
        return $result;
    }

    function getNoForm($id_rec_h){
        $query = "  SELECT
                        a.no_form,
                        b.title
                    FROM
                        rec_form_h a,
                        m_form b
                    WHERE
                        a.id_rec_form_h = $id_rec_h AND
                        a.id_form = b.id_form
                    ";
                    
        $result = $this->db->query($query);
        
        return $result;
    }

    function getAnswerAll($id_rec_h){
        $query = "  SELECT
                        b.id_pertanyaan,
                        (SELECT value FROM rec_form_d WHERE id_rec_form_h = $id_rec_h AND id_pertanyaan = b.id_pertanyaan) AS value,
                        (SELECT sub_value FROM rec_form_d WHERE id_rec_form_h = $id_rec_h AND id_pertanyaan = b.id_pertanyaan) AS sub_value
                    FROM
                        rec_form_h a,
                        m_form_pertanyaan b
                    WHERE
                        a.id_rec_form_h = $id_rec_h AND
                        a.id_form = b.id_form
                    ORDER BY
                        b.id_pertanyaan
                    ";
                    
        $result = $this->db->query($query);
        
        return $result;
    }
    
    function getAllApproval($id_rec_h){
        $query = "  SELECT
                        a.*,
                        b.id_user_app,
                        c.caption AS nama
                    FROM
                        rec_form_app a,
                        m_form_alur_app b,
                        z_master_user c
                    WHERE
                        a.id_rec_form_h = $id_rec_h AND
                        a.id_alur = b.id_alur AND
                        b.id_user_app = c.id_user
                    ORDER BY
                        a.loop_app,
                        a.seq
                    ";
                    
        $result = $this->db->query($query);
        
        return $result;
    }

    function getIDRecDHasValue($id_rec_h, $id_pertanyaan){
        $query = "  SELECT
                        id_rec_form_d
                    FROM
                        rec_form_d
                    WHERE
                        id_rec_form_h = $id_rec_h AND
                        id_pertanyaan = $id_pertanyaan

                    ";
                    
        $result = $this->db->query($query);
        
        return $result;
    }

    function checkNextQuestion($id_form, $id_rec_h, $on_app, $loop_app, $answer_by){
        $query = "  SELECT
                        z.pertanyaan,
                        z.jawaban,
                        CASE
                            WHEN z.pertanyaan > z.jawaban THEN 'NO'
                            ELSE 'YES'
                        END AS status
                    FROM
                        (
                            SELECT
                                count(a.id_pertanyaan) AS pertanyaan,
                                (
                                    SELECT
                                        COUNT(id_pertanyaan)
                                    FROM
                                        m_form_pertanyaan zz
                                    WHERE 
                                        zz.seq <= (SELECT seq FROM m_form_pertanyaan WHERE id_pertanyaan = (SELECT a.id_pertanyaan FROM rec_form_d a WHERE a.id_rec_form_h = $id_rec_h ORDER BY a.id_rec_form_d DESC LIMIT 1)) AND
                                        zz.id_form = $id_form
                                ) AS jawaban
                            FROM
                                m_form_pertanyaan a
                            WHERE
                                a.id_form = $id_form AND
                                a.answer_by = $answer_by AND
                                a.on_approval = $on_app AND
                                a.loop_app = $loop_app
                        ) z
                    ";
                    
        $result = $this->db->query($query);
        
        return $result;
    }

    function getFirstQuestion($id_form, $iterasi, $answer_by){
        $query = "  SELECT
                        a.id_pertanyaan,
                        a.seq,
                        a.pertanyaan,
                        a.label_pertanyaan,
                        a.label_endfix,
                        a.section_seq,
                        a.section_name,
                        b.tipe_jawaban
                    FROM
                        m_form_pertanyaan a,
                        m_form_tipe_jwb b
                    WHERE
                        a.id_form = $id_form AND
                        a.tipe_jawaban = b.id_tipe_jwb AND
                        a.answer_by = $answer_by AND
                        a.on_approval = 0 AND
                        a.loop_app = $iterasi
                    ORDER BY
                        a.seq
                    LIMIT 1	
                    ";
                    
        $result = $this->db->query($query);
        
        return $result;
    }

    function formViewer($id_rec_h){
        $query = "  SELECT
                        z.*,
                        CASE
                            WHEN z.tipe_jawaban = 'single_option' THEN (SELECT x.id_opsi FROM m_form_opsi x WHERE x.id_pertanyaan = z.id_pertanyaan AND x.opsi_jawaban = z.value)
                            WHEN z.tipe_jawaban = 'multi_option' THEN (SELECT x.id_opsi FROM m_form_opsi x WHERE x.id_pertanyaan = z.id_pertanyaan AND x.opsi_jawaban = z.value)
                        END AS id_opsi
                    FROM
                        (
                            SELECT
                                b.id_rec_form_d,
                                c.seq,
                                c.pertanyaan,
                                c.label_pertanyaan,
                                b.value,
                                b.sub_value,
                                d.tipe_jawaban,
                                c.id_pertanyaan,
                                c.section_seq,
                                c.label_endfix,
                                e.has_approval
                            FROM
                                rec_form_d b,
                                m_form_pertanyaan c,
                                m_form_tipe_jwb d,
                                m_form e
                            WHERE
                                b.id_rec_form_h = $id_rec_h AND
                                b.id_pertanyaan = c.id_pertanyaan AND
                                c.tipe_jawaban = d.id_tipe_jwb AND
                                c.id_form = e.id_form
                            ORDER BY
                                b.id_rec_form_d
                        ) z
                    ";
                    
        $result = $this->db->query($query);
        
        return $result;
    }

    function getSectionForm($id_rec_h){
        $query = "  SELECT DISTINCT
                        b.section_seq,
                        b.section_name
                    FROM
                        rec_form_h a,
                        m_form_pertanyaan b
                    WHERE
                        a.id_rec_form_h = $id_rec_h AND
                        a.id_form = b.id_form

                    ";
                    
        $result = $this->db->query($query);
        
        return $result;
    }

    function getAllAppBefore($id_rec_h, $loop){
        $query = "  SELECT
                        a.*
                    FROM
                        rec_form_app a,
                        m_form_alur_app b
                    WHERE
                        a.id_rec_form_h = $id_rec_h AND
                        a.loop_app = $loop AND
                        a.id_alur = b.id_alur AND
                        b.optional = 0
                    ORDER BY
                        a.seq

                    ";
                    
        $result = $this->db->query($query);
        
        return $result;
    }

    function getIdRecH($no_form){
        $query = "  SELECT
                        a.id_rec_form_h
                    FROM
                        rec_form_h a
                    WHERE
                        a.no_form = '$no_form' 
                    ";
                    
        $result = $this->db->query($query);
        
        return $result;
    }

    function getAllApprovalSet($id_rec_h){
        $query = "  SELECT
                        a.*,
                        b.*,
                        c.caption as jabatan
                    FROM
                        rec_form_app a,
                        z_master_user b,
                        m_form_alur_app c
                    WHERE
                        a.id_rec_form_h = $id_rec_h AND
                        a.id_alur = c.id_alur AND
                        c.id_user_app = b.id_user
                    ORDER BY
                        a.seq
                    ";
                    
        $result = $this->db->query($query);
        
        return $result;
    }

    function getOpsiForm($id_rec_h){
        $query = "  SELECT
                        b.opsi_jawaban,
                        b.sub_pertanyaan,
                        c.id_pertanyaan,
                        b.seq_jawaban
                    FROM
                        rec_form_h a,
                        m_form_opsi b,
                        m_form_pertanyaan c
                    WHERE
                        a.id_rec_form_h = $id_rec_h AND
                        a.id_form = c.id_form AND
                        b.id_pertanyaan = c.id_pertanyaan
                    ORDER BY
                        b.seq_jawaban
                    ";
                    
        $result = $this->db->query($query);
        
        return $result;
    }

    function getAppForm($no_form){
        $query = "  SELECT
                        c.caption
                    FROM
                        rec_form_h a,
                        m_form_alur_app b,
                        z_master_user c
                    WHERE
                        a.no_form = '$no_form' AND
                        a.id_form = b.id_form AND
                        (CURDATE() BETWEEN b.start_date AND b.end_date) AND
                        b.id_user_app = c.id_user
                    ORDER BY
                        b.seq_app
                    ";
                    
        $result = $this->db->query($query);
        
        return $result;
    }

    function getTitleForm($id_rec_h){
        $query = "  SELECT
                        b.title
                    FROM
                        rec_form_h a,
                        m_form b
                    WHERE
                        a.id_rec_form_h = $id_rec_h AND
                        a.id_form = b.id_form
                    ";
                    
        $result = $this->db->query($query);
        
        return $result;
    }

    function getOpsiJawabanValue($id_opsi){
        $query = "  SELECT
                        opsi_jawaban
                    FROM
                        m_form_opsi
                    WHERE
                        id_opsi = $id_opsi
                    ";
                    
        $result = $this->db->query($query);
        
        return $result;
    }

    function getPlantUser($id_user){
        $query = "  SELECT
                        plant
                    FROM
                        z_master_user
                    WHERE
                        id_user = $id_user
                    ";
                    
        $result = $this->db->query($query);
        
        return $result;
    }

    function getUserPemohon($id_rec_h){
        $query = "  SELECT
                        b.no_hp,
                        b.plant
                    FROM
                        rec_form_h a,
                        z_master_user b
                    WHERE
                        a.id_rec_form_h = $id_rec_h AND
                        a.id_user_pemohon = b.id_user
                    ";
                    
        $result = $this->db->query($query);
        
        return $result;
    }

    function getAppUser($id_rec_h){
        $query = "  SELECT
                        a.seq,
                        a.id_alur,
                        b.id_user_app,
                        c.caption,
                        c.no_hp
                    FROM
                        rec_form_app a,
                        m_form_alur_app b,
                        z_master_user c
                    WHERE
                        a.id_rec_form_h = $id_rec_h AND
                        a.id_alur = b.id_alur AND
                        b.id_user_app = c.id_user
                    ORDER BY
                        a.seq
                    ";
                    
        $result = $this->db->query($query);
        
        return $result;
    }

    function getAppUser_old($id_user, $id_form){
        $query = "  SELECT
                        a.seq_app,
                        a.id_user_app,
                        b.caption,
                        b.no_hp
                    FROM
                        m_form_alur_app a,
                        z_master_user b
                    WHERE
                        a.id_form = $id_form AND
                        a.plant = (SELECT plant FROM z_master_user WHERE id_user = $id_user) AND
                        a.id_user_app = b.id_user
                    ";
                    
        $result = $this->db->query($query);
        
        return $result;
    }

    function getAlurApp($id_user_app, $id_form, $plant){
        $query = "  SELECT
                        a.id_alur
                    FROM
                        m_form_alur_app a
                    WHERE
                        a.id_user_app = $id_user_app AND
                        a.id_form = $id_form AND
                        a.plant = '$plant'
                    ";
                    
        $result = $this->db->query($query);
        
        return $result;
    }

    function checkAppUser_old($no_hp){
        $query = "  SELECT
                        z.*
                    FROM
                        (
                            SELECT 
                                a.no_form,
                                b.seq_app,
                                b.id_user_app,
                                c.caption,
                                c.no_hp,
                                a.caption_form,
                                d.title,
                                a.id_rec_form_h,
                                a.id_form
                                (
                                    SELECT
                                        status
                                    FROM
                                        rec_form_app
                                    WHERE
                                        id_rec_form_h = a.id_rec_form_h AND
                                        id_alur = b.id_alur
                                ) AS approval
                            FROM
                                rec_form_h a,
                                m_form_alur_app b,
                                z_master_user c,
                                m_form d
                            WHERE
                                a.created = 1 AND
                                a.id_form = b.id_form AND
                                b.id_user_app = c.id_user AND
                                c.no_hp = '$no_hp' AND
                                c.is_active = 1 AND
                                (CURDATE() BETWEEN b.start_date AND b.end_date) AND
                                a.id_form = d.id_form
                        ) z
                    WHERE
                        z.approval IS NULL
                    ORDER BY
                        z.no_form
                    ";
                    
        $result = $this->db->query($query);
        
        return $result;
    }

    function checkAppUser($id_rec_h, $id_user_app){
        $query = "  SELECT
                        CASE
                            WHEN x.total_app = x.seq_now THEN 'TRUE'
                            ELSE 'FALSE'
                        END AS status
                    FROM
                        (
                            SELECT
                                COUNT(b.id_alur) AS total_app,
                                (
                                    SELECT 
                                        zb.seq_app 
                                    FROM
                                        rec_form_h za,
                                        m_form_alur_app zb
                                    WHERE
                                        za.id_rec_form_h = $id_rec_h AND
                                        za.id_form = zb.id_form AND
                                        zb.id_user_app = $id_user_app AND
                                        (CURDATE() BETWEEN zb.start_date AND zb.end_date)
                                ) seq_now
                            FROM
                                rec_form_h a,
                                m_form_alur_app b
                            WHERE
                                a.id_rec_form_h = $id_rec_h AND
                                a.id_form = b.id_form AND
                                (CURDATE() BETWEEN b.start_date AND b.end_date)
                        ) x
                    ";
                    
        $result = $this->db->query($query);
        
        return $result;
    }

    function getConfigApp($id_form, $seq_app, $status_app){
        $query = "  SELECT
                        a.id_config_app,
                        a.pertanyaan_config,
                        b.tipe_jawaban,
                        a.seq_config
                    FROM
                        m_form_config_app a,
                        m_form_tipe_jwb b
                    WHERE
                        a.id_form = $id_form AND
                        a.seq_app = $seq_app AND
                        a.status_app = $status_app AND
                        a.jenis_jawaban_config = b.id_tipe_jwb
                    ORDER BY
	                    a.seq_config
                ";
                    
        $result = $this->db->query($query);
        
        return $result;
    }

    function nextAppUser($id_rec_h){
        $query = "  SELECT	
                        a.seq,
                        c.id_user,
                        c.caption,
                        c.no_hp,
                        a.id_alur,
                        b.caption as jabatan
                    FROM
                        rec_form_app a,
                        m_form_alur_app b,
                        z_master_user c
                    WHERE
                        a.id_rec_form_h = $id_rec_h AND
                        a.status = 0 AND
                        a.id_alur = b.id_alur AND
                        b.id_user_app = c.id_user
                    ORDER BY
                        a.seq
                    LIMIT 1
                ";
                    
        $result = $this->db->query($query);
        
        return $result;
    }

    function nextAppUser_old($id_form, $plant, $id_user_app){
        $query = "  SELECT
                        c.no_hp,
                        c.id_user,
                        c.caption
                    FROM
                        m_form_alur_app b,
                        z_master_user c
                    WHERE
                        b.id_form = $id_form AND
                        b.seq_app = ((SELECT a.seq_app FROM m_form_alur_app a WHERE a.id_form = $id_form AND a.plant = '$plant' AND a.id_user_app = $id_user_app) + 1) AND
                        b.id_user_app = c.id_user
                ";
                    
        $result = $this->db->query($query);
        
        return $result;
    }

    function getIdRecDByAlur($id_rec_h, $id_alur){
        $query = "  SELECT
                        a.id_rec_form_app
                    FROM
                        rec_form_app a
                    WHERE
                        a.id_rec_form_h = $id_rec_h AND
                        a.id_alur = $id_alur
                ";
                    
        $result = $this->db->query($query);
        
        return $result;
    }

    function getDataPemohon($id_rec_h){
        $query = "  SELECT
                        b.*
                    FROM
                        rec_form_h a,
                        z_master_user b
                    WHERE
                        a.id_rec_form_h = $id_rec_h AND
                        a.id_user_pemohon = b.id_user
                ";
                    
        $result = $this->db->query($query);
        
        return $result;
    }

    function getUserAnswerNext($id_rec_h, $id_form, $loop){
        $query = "  SELECT
                        z.*
                    FROM
                        (
                            SELECT
                                a.seq,
                                b.id_user_app
                            FROM
                                rec_form_app a,
                                m_form_alur_app b
                            WHERE
                                a.loop_app = 1 AND
                                a.id_rec_form_h = $id_rec_h AND
                                a.id_alur = b.id_alur
                            ORDER BY
                                a.seq
                        ) z
                    WHERE
                        z.seq = (SELECT DISTINCT answer_by FROM m_form_pertanyaan WHERE id_form = $id_form AND loop_app = $loop)
                ";
                    
        $result = $this->db->query($query);
        
        return $result;
    }

    function getExtraAPP($id_rec_h, $id_form){
        $query = "  SELECT
                        a.*
                    FROM
                        m_form_alur_app a
                    WHERE
                        a.id_form = $id_form AND
                        a.optional = 1 AND
                        a.department = (SELECT department FROM rec_form_h WHERE id_rec_form_h = $id_rec_h) AND
                        a.plant = (SELECT plant FROM rec_form_h WHERE id_rec_form_h = $id_rec_h)
                    ORDER BY	
                        a.seq_app
                ";
                    
        $result = $this->db->query($query);
        
        return $result;
    }

    function recordApprovalDetail($id_rec_app, $id_config, $value){
        $data = array(
            'id_rec_form_app' => $id_rec_app,
            'id_config_app' => $id_config,
            'value_response' => $value
        );

        $this->db->insert('rec_form_app_detail', $data);
        return $this->db->affected_rows();
    }

    function recordSession($id_user){
        $data = array(
            'id_user' => $id_user,
            'id_form' => 0,
            'id_rec_h' => 0,
            'id_pertanyaan' => 0,
            'id_opsi' => 0,
            'status' => 0,
            'iterasi' => 0,
            'on_approval' => 0
        );

        $this->db->set('time_rec', 'NOW()', FALSE);
        $this->db->insert('rec_form_session', $data);
        return $this->db->insert_id();
    }

    function contLoopSession($id_user, $id_form, $id_rec_h, $id_pertanyaan, $loop, $answer_by){
        $data = array(
            'id_user' => $id_user,
            'id_form' => $id_form,
            'id_rec_h' => $id_rec_h,
            'id_pertanyaan' => $id_pertanyaan,
            'id_opsi' => 0,
            'status' => 1,
            'iterasi' => $loop,
            'on_approval' => 0,
            'seq_app' => $answer_by
        );

        $this->db->set('time_rec', 'NOW()', FALSE);
        $this->db->insert('rec_form_session', $data);
        return $this->db->affected_rows();
    }

    function recordSessionApprovalQuestion($id_user, $id_form, $id_rec_h, $id_pertanyaan, $iterasi, $seq_app){
        $data = array(
            'id_user' => $id_user,
            'id_form' => $id_form,
            'id_rec_h' => $id_rec_h,
            'id_pertanyaan' => $id_pertanyaan,
            'id_opsi' => 0,
            'status' => 1,
            'iterasi' => $iterasi,
            'on_approval' => 1,
            'seq_app' => $seq_app
        );

        $this->db->set('time_rec', 'NOW()', FALSE);
        $this->db->insert('rec_form_session', $data);
        return $this->db->affected_rows();
    }

    function recordSessionApproval($id_user, $id_form, $id_rec_h, $iterasi, $seq_app){
        $data = array(
            'id_user' => $id_user,
            'id_form' => $id_form,
            'id_rec_h' => $id_rec_h,
            'id_pertanyaan' => 0,
            'id_opsi' => 0,
            'status' => 10,
            'iterasi' => $iterasi,
            'on_approval' => 0,
            'seq_app' => $seq_app
        );

        $this->db->set('time_rec', 'NOW()', FALSE);
        $this->db->insert('rec_form_session', $data);
        return $this->db->affected_rows();
    }

    function updateSession($id_session, $id_form, $id_rec_h, $id_pertanyaan, $id_opsi, $seq_app, $iterasi, $on_app, $status){
        $data = array(
            'id_form' => $id_form,
            'id_rec_h' => $id_rec_h,
            'id_pertanyaan' => $id_pertanyaan,
            'id_opsi' => $id_opsi,
            'seq_app' => $seq_app,
            'status' => $status,
            'iterasi' => $iterasi,
            'on_approval' => $on_app
        );
        
        $this->db->set('time_rec', 'NOW()', FALSE);
        
        $this->db->where('id_session', $id_session);
        $this->db->update('rec_form_session', $data);
        return $this->db->affected_rows();
    }

    function updateSessionExtendedInfo($id_session, $plant, $dept, $batch){
        $data = array(
            'iterasi' => $batch,
            'department' => $dept,
            'plant' => $plant
        );
        
        $this->db->set('time_rec', 'NOW()', FALSE);
        
        $this->db->where('id_session', $id_session);
        $this->db->update('rec_form_session', $data);
        return $this->db->affected_rows();
    }

    function recordApproval($id_rec_form_h, $id_alur, $catatan, $status, $seq, $loop){
        $data = array(
            'id_rec_form_h' => $id_rec_form_h,
            'id_alur' => $id_alur,
            'status' => $status,
            'catatan' => $catatan,
            'seq' => $seq,
            'loop_app' => $loop,
        );

        $this->db->set('date_approved', 'NOW()', FALSE);
        $this->db->insert('rec_form_app', $data);
        return $this->db->affected_rows();
    }

    function updateApproval($id_rec_form_h, $id_alur, $catatan, $status, $loop){
        $data = array(
            'status' => $status,
            'catatan' => $catatan
        );

        $this->db->where('id_rec_form_h', $id_rec_form_h);
        $this->db->where('id_alur', $id_alur);
        $this->db->where('loop_app', $loop);
        $this->db->set('date_approved', 'NOW()', FALSE);
        $this->db->update('rec_form_app', $data);
        return $this->db->affected_rows();
    }

    function recordFormH($id_form, $id_user, $plant, $dept){
        $data = array(
            'id_form' => $id_form,
            'id_user_pemohon' => $id_user,
            'ref_to' => 0,
            'no_form' => 0,
            'caption_form' => '',
            'plant' => $plant,
            'department' => $dept
        );

        $this->db->set('date_created', 'NOW()', FALSE);
        $this->db->insert('rec_form_h', $data);
        $id_rec_h = $this->db->insert_id();

        // $romanMonths = [
		// 	1 => 'I',
		// 	2 => 'II',
		// 	3 => 'III',
		// 	4 => 'IV',
		// 	5 => 'V',
		// 	6 => 'VI',
		// 	7 => 'VII',
		// 	8 => 'VIII',
		// 	9 => 'IX',
		// 	10 => 'X',
		// 	11 => 'XI',
		// 	12 => 'XII'
		// ];

		// $no_ijin = $id_rec_h . "/" . date("d") . "/" . $romanMonths[date("n")] . "/" . date("Y") . "/IK/SHE-C06";

        // $data = array(
        //     'no_form' => $no_ijin
        // );
        
        // $this->db->where('id_rec_form_h', $id_rec_h);
        // $this->db->update('rec_form_h', $data);
        // $result = $this->db->affected_rows();

        // $send = 0;

        // if($result){
        //     $send = $id_rec_h;
        // }

        return $id_rec_h;
    }

    function updateCaptionRecH($id_rec_h, $caption){
        $data = array(
            'caption_form' => $caption
        );
        
        $this->db->where('id_rec_form_h', $id_rec_h);
        $this->db->update('rec_form_h', $data);
        return $this->db->affected_rows();
    }

    function recordFormHCreated($id_rec_h){
        $query = "  SELECT
                        count(id_rec_form_h) AS total,
                        (SELECT DISTINCT b.title FROM rec_form_h a, m_form b WHERE a.id_rec_form_h = $id_rec_h AND a.id_form = b.id_form) AS form
                    FROM
                        rec_form_h
                    WHERE
                        created = 1 AND
                        id_form = (SELECT id_form FROM rec_form_h WHERE id_rec_form_h = $id_rec_h) AND
                        YEAR(date_created) = YEAR(NOW())
                    ";
                    
        $result = $this->db->query($query)->result();

        if($result[0]->total <= 0){
            $result[0]->total = 1;
        }

        $no_form = $result[0]->form . "/" . date('Y') . "/" . str_pad($result[0]->total,5,"0", STR_PAD_LEFT);

        $data = array(
            'created' => 1,
            'no_form' => $no_form
        );
        
        $this->db->where('id_rec_form_h', $id_rec_h);
        $this->db->update('rec_form_h', $data);
        return $this->db->affected_rows();

        
    }

    function recordFormHApproved($id_rec_h){
        $data = array(
            'approved' => 1
        );
        
        $this->db->where('id_rec_form_h', $id_rec_h);
        $this->db->update('rec_form_h', $data);
        return $this->db->affected_rows();
    }

    function recordFormD($id_rec_form_h, $id_pertanyaan){
        $data = array(
            'id_rec_form_h' => $id_rec_form_h,
            'id_pertanyaan' => $id_pertanyaan,
            'value' => '',
            'sub_value' => ''
        );

        $this->db->insert('rec_form_d', $data);
        return $this->db->affected_rows();
    }

    function updateValueRecordFormD($id_rec_form_d, $value){
        $data = array(
            'value' => $value
        );

        $this->db->where('id_rec_form_d', $id_rec_form_d);
        $this->db->update('rec_form_d', $data);
        return $this->db->affected_rows();

        
    }

    function updateSubValueRecordFormD($id_rec_form_d, $value){
        $data = array(
            'sub_value' => $value
        );

        $this->db->where('id_rec_form_d', $id_rec_form_d);
        $this->db->update('rec_form_d', $data);
        return $this->db->affected_rows();
    }

    //=========================================================

    function logApi($input, $from, $id_msg, $conversation_id, $msg, $action_id, $timestamp, $file_type, $file_url){
        $data = array(
            'log_api' => $input,
            'from_number' => $from,
            'id_msg' => $id_msg,
            'conversation_id' => $conversation_id,
            'msg' => $msg,
            'action_id' => $action_id,
            'time_receive' => $timestamp,
            'file_type' => $file_type,
            'file_url' => $file_url
        );

        $this->db->insert('log_api', $data);
        return $this->db->affected_rows();
    }

    function recordReportAPI($report, $post){
        $data = array(
            'report' => $report,
            'post' => $post
        );

        $this->db->set('log_time', 'NOW()', FALSE);
        $this->db->insert('log_api_wa_report', $data);
        return $this->db->affected_rows();
    }
}
?>