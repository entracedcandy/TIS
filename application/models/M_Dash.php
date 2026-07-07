<?php

defined('BASEPATH') OR exit('No direct script access allowed');
  
class M_Dash extends CI_Model{

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

    function getListPTFarm($iduser, $groupuser){

        $query = "  SELECT GROUP_CONCAT(f.id_farm ORDER BY f.id_farm ASC SEPARATOR ', ') AS idf
                    FROM 
                    d_wa_contact d 
                    INNER JOIN d_master_farm f ON d.id_farm = f.id_farm
                    INNER JOIN d_master_pt p ON f.id_pt = p.id
                    WHERE d.".$groupuser." = '$iduser'
                ";

        $result = $this->db->query($query);
                
        return $result;

    }

    function getAllFarmDashboard(){
        $query = "  SELECT 
                    a.*,
                    CASE 
                        WHEN (SELECT status FROM d_farm_prog WHERE id_farm = a.id_farm ORDER BY id_progress DESC LIMIT 1) IS NULL THEN 'Belum Ada Data'
                        WHEN (SELECT status FROM d_farm_prog WHERE id_farm = a.id_farm ORDER BY id_progress DESC LIMIT 1) = 1 THEN 'Proses Chick In'
                        WHEN (SELECT status FROM d_farm_prog WHERE id_farm = a.id_farm ORDER BY id_progress DESC LIMIT 1) = 2 THEN 'Selesai Chick In'
                        WHEN (SELECT status FROM d_farm_prog WHERE id_farm = a.id_farm ORDER BY id_progress DESC LIMIT 1) = 3 THEN 'TIS Melakukan Survey'
                        WHEN (SELECT status FROM d_farm_prog WHERE id_farm = a.id_farm ORDER BY id_progress DESC LIMIT 1) = 4 THEN 'DOC Marketing'
                        WHEN (SELECT status FROM d_farm_prog WHERE id_farm = a.id_farm ORDER BY id_progress DESC LIMIT 1) = 5 THEN 'DOC Negosiasi'
                        WHEN (SELECT status FROM d_farm_prog WHERE id_farm = a.id_farm ORDER BY id_progress DESC LIMIT 1) = 6 THEN 'DOC Chick In Berikutnya'
                    END AS status,
                    (SELECT date_chickin FROM d_farm_prog WHERE id_farm = a.id_farm ORDER BY id_progress DESC LIMIT 1) AS tglci,
                    CASE   
                        WHEN ABS(DATEDIFF((SELECT date_chickin FROM d_farm_prog WHERE id_farm = a.id_farm ORDER BY id_progress DESC LIMIT 1),NOW())) is null then '-'
                        ELSE ABS(DATEDIFF((SELECT date_chickin FROM d_farm_prog WHERE id_farm = a.id_farm ORDER BY id_progress DESC LIMIT 1),NOW()))
                    END AS umur,
                    CASE
                        WHEN (SELECT l.kosong FROM d_log l, d_farm_prog f WHERE l.id_progress = f.id_progress AND f.id_farm = a.id_farm AND l.depart LIKE '%TIS%' AND f.status <= '5' ORDER BY l.id_log DESC LIMIT 1) IS NULL THEN '-'
                        ELSE (SELECT l.kosong FROM d_log l, d_farm_prog f WHERE l.id_progress = f.id_progress AND f.id_farm = a.id_farm AND l.depart LIKE '%TIS%' AND f.status <= '5' ORDER BY l.id_log DESC LIMIT 1)
                    END AS kandangkosong,
                    CASE
                        WHEN (SELECT l.date_survey FROM d_log l, d_farm_prog f WHERE l.id_progress = f.id_progress AND f.id_farm = a.id_farm AND l.depart LIKE '%TIS%' AND f.status <= '5' ORDER BY l.id_log DESC LIMIT 1) IS NULL THEN '-'
                        ELSE (SELECT l.date_survey FROM d_log l, d_farm_prog f WHERE l.id_progress = f.id_progress AND f.id_farm = a.id_farm AND l.depart LIKE '%TIS%' AND f.status <= '5' ORDER BY l.id_log DESC LIMIT 1)
                    END AS lastsurveytis,
                    
                    CASE
                        WHEN (SELECT l.date_estimasi FROM d_log l, d_farm_prog f WHERE l.id_progress = f.id_progress AND f.id_farm = a.id_farm AND l.kosong = 'true' AND f.status <= '5' ORDER BY l.id_log DESC LIMIT 1) = '1111-01-01' THEN '-'
                        WHEN (SELECT l.date_estimasi FROM d_log l, d_farm_prog f WHERE l.id_progress = f.id_progress AND f.id_farm = a.id_farm AND l.kosong = 'true' AND f.status <= '5' ORDER BY l.id_log DESC LIMIT 1) IS NULL THEN '-'
                        ELSE (SELECT l.date_estimasi FROM d_log l, d_farm_prog f WHERE l.id_progress = f.id_progress AND f.id_farm = a.id_farm AND l.kosong = 'true' AND f.status <= '5' ORDER BY l.id_log DESC LIMIT 1)
                    END AS lastkosongtis,
                    CASE
                        WHEN (SELECT l.catatan FROM d_log l, d_farm_prog f WHERE l.id_progress = f.id_progress AND f.id_farm = a.id_farm AND l.depart LIKE '%TIS%' AND f.status <= '5' ORDER BY f.id_progress DESC, id_log DESC LIMIT 1) IS NULL THEN '-'
                        ELSE (SELECT l.catatan FROM d_log l, d_farm_prog f WHERE l.id_progress = f.id_progress AND f.id_farm = a.id_farm AND l.depart LIKE '%TIS%' AND f.status <= '5' ORDER BY f.id_progress DESC, id_log DESC LIMIT 1)
                    END AS lasttisinfo,
                    
                    CASE
                        WHEN (SELECT l.nilai_farm FROM d_log l, d_farm_prog f WHERE l.id_progress = f.id_progress AND f.id_farm = a.id_farm AND l.kosong IN ('fixye','fixno') AND l.depart LIKE '%MARKETING_DOC%' AND f.status <= '5' ORDER BY l.id_log DESC LIMIT 1) IS NULL THEN '-'
                        ELSE (SELECT l.nilai_farm FROM d_log l, d_farm_prog f WHERE l.id_progress = f.id_progress AND f.id_farm = a.id_farm AND l.kosong IN ('fixye','fixno')  AND l.depart LIKE '%MARKETING_DOC%' AND f.status <= '5' ORDER BY l.id_log DESC LIMIT 1)
                    END AS ratefixmarket,
                    CASE
                        WHEN (SELECT l.date_estimasi FROM d_log l, d_farm_prog f WHERE l.id_progress = f.id_progress AND f.id_farm = a.id_farm AND l.kosong = 'fixye' AND l.depart LIKE '%MARKETING_DOC%' AND f.status <= '5' ORDER BY l.id_log DESC LIMIT 1) IS NULL THEN '-'
                        ELSE (SELECT l.date_estimasi FROM d_log l, d_farm_prog f WHERE l.id_progress = f.id_progress AND f.id_farm = a.id_farm AND l.kosong = 'fixye' AND l.depart LIKE '%MARKETING_DOC%' AND f.status <= '5' ORDER BY l.id_log DESC LIMIT 1) 
                    END AS datefixmarket,
                    
                    CASE 
                        WHEN (SELECT status FROM d_farm_prog WHERE id_farm = a.id_farm ORDER BY id_progress DESC LIMIT 1) IS NULL THEN 0
                        ELSE (SELECT status FROM d_farm_prog WHERE id_farm = a.id_farm ORDER BY id_progress DESC LIMIT 1)
                    END AS status_num,
                    (SELECT id_progress FROM d_farm_prog WHERE id_farm = a.id_farm ORDER BY id_progress DESC LIMIT 1) AS id_progress,
                    IFNULL((SELECT SUM(total_ci)*100 FROM d_ci_log WHERE id_progress = (SELECT id_progress FROM d_farm_prog WHERE id_farm = a.id_farm ORDER BY id_progress DESC LIMIT 1)),0) AS t_ayam_now
                    FROM
                    d_master_farm a
                    WHERE
                    a.active = 'y' AND
                    a.id_pt = '$idPT'
                    AND id_pt != '32'
        
                ";
                    
        $result = $this->db->query($query);
        
        return $result;
    }

    function dashDOCAtas(){
        $query = "  SELECT 
                    (SELECT 
                    COUNT(nama_pt)
                    FROM d_master_pt
                    WHERE active = 'y' AND id != '32') AS tot_all
                    ,(SELECT 
                    COUNT(a.nama_farm)
                    FROM d_master_farm a, d_master_pt b
                    WHERE b.active = 'y' AND b.id = a.id_pt AND a.id_pt != '32') AS tot_kandang
                    ,ROUND((SELECT 
                    COUNT(a.kompetitor)
                    FROM d_farm_prog b, d_ci_log a
                    WHERE b.active = 'y' AND a.id_progress = b.id_progress AND a.kompetitor = 'CPJF' AND b.id_farm NOT IN (59,86,87)),2) AS tot_cpi
                    ,(SELECT 
                    COUNT(a.kompetitor)
                    FROM d_farm_prog b, d_ci_log a
                    WHERE b.active = 'y' AND a.id_progress = b.id_progress AND a.kompetitor != 'CPJF' AND b.id_farm NOT IN (59,86,87)) AS tot_komp
                    ,(SELECT 
                    COUNT(id_farm)
                    FROM d_farm_prog
                    WHERE active = 'y' AND status = '4' AND id_farm NOT IN (59,86,87)) AS tot_kos

                    UNION
                    SELECT 'Persentase'
                    ,'100%'
                    ,concat(ROUND(((SELECT 
                    COUNT(a.kompetitor)
                    FROM d_farm_prog b, d_ci_log a
                    WHERE b.active = 'y' AND a.id_progress = b.id_progress AND a.kompetitor = 'CPJF' AND b.id_farm NOT IN (59,86,87))/
                    (SELECT 
                    COUNT(a.nama_farm)
                    FROM d_master_farm a, d_master_pt b
                    WHERE b.active = 'y' AND b.id = a.id_pt AND a.id_pt != '32')*100),0),'%')
                    ,concat(ROUND(((SELECT 
                    COUNT(a.kompetitor)
                    FROM d_farm_prog b, d_ci_log a
                    WHERE b.active = 'y' AND a.id_progress = b.id_progress AND a.kompetitor != 'CPJF' AND b.id_farm NOT IN (59,86,87))/
                    (SELECT 
                    COUNT(a.nama_farm)
                    FROM d_master_farm a, d_master_pt b
                    WHERE b.active = 'y' AND b.id = a.id_pt AND a.id_pt != '32')*100),0),'%')
                    ,concat(ROUND(((SELECT 
                    COUNT(id_farm)
                    FROM d_farm_prog
                    WHERE active = 'y' AND status = '4' AND id_farm NOT IN (59,86,87))/
                    (SELECT 
                    COUNT(a.nama_farm)
                    FROM d_master_farm a, d_master_pt b
                    WHERE b.active = 'y' AND b.id = a.id_pt AND a.id_pt != '32')*100),0),'%')
                    ";
                    
        $result = $this->db->query($query);
        
        return $result;
    }

    function dashDOCAtas2(){
        $query = "  SELECT a.*
                    ,(SELECT COUNT(nama_farm) FROM d_master_farm WHERE id_pt = a.id) as jml_kandang
                    ,(SELECT COUNT(f.nama_farm) FROM d_master_farm f,d_farm_prog p, d_ci_log l WHERE f.id_pt = a.id AND p.id_farm = f.id_farm AND p.id_progress = l.id_progress AND p.active = 'y' AND l.kompetitor = 'CPJF') as jml_cpi
                    ,(SELECT COUNT(f.nama_farm) FROM d_master_farm f,d_farm_prog p, d_ci_log l WHERE f.id_pt = a.id AND p.id_farm = f.id_farm AND p.id_progress = l.id_progress AND p.active = 'y' AND l.kompetitor != 'CPJF') as jml_kompetitor
                    ,(SELECT COUNT(f.nama_farm) FROM d_master_farm f,d_farm_prog p, d_ci_log l WHERE f.id_pt = a.id AND p.id_farm = f.id_farm AND p.id_progress = l.id_progress AND p.active = 'y' AND p.status = '4') as jml_kosong
                    FROM d_master_pt a
                    WHERE a.id != '32'
                    ORDER BY id
                    ";
                    
        $result = $this->db->query($query);
        
        return $result;
    }

    function dashDOCMarket(){
        $query = "  SELECT p.*
                    ,CASE
                    WHEN date_harvest = '1990-01-01' THEN '-'
                    ELSE date_harvest
                    END as estimasi_kosong
                    ,(SELECT description FROM d_master_status WHERE p.status = code) as deskripsi
                    ,(SELECT t.nama_pt FROM d_master_farm f,d_master_pt t WHERE p.id_farm = f.id_farm AND t.id=f.id_pt) as nama_cust
                    ,(SELECT nama_farm FROM d_master_farm WHERE p.id_farm = id_farm) as nama_farm
                    ,(SELECT doc_kuota FROM d_master_farm WHERE p.id_farm = id_farm) as populasi
                    FROM d_farm_prog p
                    WHERE active = 'y'
                    -- AND a.id_pt != '32'
                    AND p.id_farm NOT IN (59,86,87)
                    ";
                    
        $result = $this->db->query($query);
        
        return $result;
    }

    function dashDOCTIS($list){
        $query = "  SELECT p.*
                    ,CASE
                    WHEN date_harvest = '1990-01-01' THEN '-'
                    ELSE date_harvest
                    END as estimasi_kosong
                    ,(SELECT description FROM d_master_status WHERE p.status = code) as deskripsi
                    ,(SELECT t.nama_pt FROM d_master_farm f,d_master_pt t WHERE p.id_farm = f.id_farm AND t.id=f.id_pt) as nama_cust
                    ,(SELECT nama_farm FROM d_master_farm WHERE p.id_farm = id_farm) as nama_farm
                    ,(SELECT doc_kuota FROM d_master_farm WHERE p.id_farm = id_farm) as populasi
                    FROM d_farm_prog p
                    WHERE active = 'y'
                    AND p.id_farm IN ($list)
                    AND p.id_farm NOT IN (59,86,87)
                    ";
                    
        $result = $this->db->query($query);
        
        return $result;
    }
}
?>