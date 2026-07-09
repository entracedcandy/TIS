<?php

defined('BASEPATH') OR exit('No direct script access allowed');
  
class M_DR_2023_00001 extends CI_Model{
    public function __construct(){
        $this->load->database();
        // $this->pellet = $this->load->database('pellet', TRUE);
    }
    
    function readFunction($funcName, $param, $state){
        if($param !== ""){
            $value = $this->$funcName($param, $state);
        }else{
            $value = $this->$funcName($state);
        }
        return $value;
    }

    function tableData($param, $state){
        $value = $param;

        if($state == "table"){
            $value[1] = ($value[1] - 1) * $value[0];
        }

        $date_end=date_create($value[3]);
        date_add($date_end,date_interval_create_from_date_string("1 days"));
        $date_end = date_format($date_end,"Y-m-d");
        
        $query = "  SELECT
                        z.tanggal as Tanggal,
                        z.hari as Hari,
                        z.umur_minggu as Minggu,
                        z.MT as Deplesi,
                        z.persen_jumlah_dep as Kumulatif_Deplesi,
                        z.EndAyam as Ayam,
                        z.CI_Awal as Chick_In,
                        z.ransum as Ransum,
                        z.fi as Feed_Intake,
                        (SELECT min_value FROM master_standard WHERE week = z.umur_minggu AND standard = 'feed_intake') as Standard_Feed_Intake_Min,
                        (SELECT max_value FROM master_standard WHERE week = z.umur_minggu AND standard = 'feed_intake') as Standard_Feed_Intake_Max,
                        -- 120 as Standard_Feed_Intake,
                        z.EndSilo as Silo,
                        z.air as Air,
                        z.wi as Water_Intake,
                        (SELECT min_value FROM master_standard WHERE week = z.umur_minggu AND standard = 'water_intake') as Standard_Water_Intake_Min,
                        (SELECT max_value FROM master_standard WHERE week = z.umur_minggu AND standard = 'water_intake') as Standard_Water_Intake_Max,
                        z.utuh_sum as Total_Telur_Utuh,
                        z.utuh_berat as Berat_Telur_Utuh,
                        z.pecah_sum as Total_Telur_Pecah,
                        z.pecah_berat as Berat_Telur_Pecah,
                        z.gr_btr as Gr_Per_Btr,
                        z.hd as Hen_Day,
                        (SELECT min_value FROM master_standard WHERE week = z.umur_minggu AND standard = 'hd') as Standard_Hen_Day_Min,
                        (SELECT max_value FROM master_standard WHERE week = z.umur_minggu AND standard = 'hd') as Standard_Hen_Day_Max,
                        -- 90 as Standard_Hen_Day,
                        z.hh as Hen_House,
                        z.fcr as FCR,
                        2.00 as Standard_FCR,
                        z.bw as Body_Weight,
                        (SELECT min_value FROM master_standard WHERE week = z.umur_minggu AND standard = 'body_weight') as Standard_Body_Weight_Min,
                        (SELECT max_value FROM master_standard WHERE week = z.umur_minggu AND standard = 'body_weight') as Standard_Body_Weight_Max
                    FROM
                    (
                        SELECT 
                        a.*, 
                        i.ransum,
                        i.umur_minggu,
                        i.umur_hari,
                        (i.umur_minggu * 7) - (7 - i.umur_hari) as hari,
                        i.ransum*1000/a.EndAyam AS fi,
                        i.air,
                        i.air*1000/a.EndAyam AS wi,
                        i.prod_utuh_qty as utuh_sum,
                        i.prod_utuh_berat as utuh_berat,
                        i.prod_pecah_qty as pecah_sum,
                        i.prod_pecah_berat as pecah_berat,
                        (i.prod_utuh_berat+i.prod_pecah_berat)*1000/(i.prod_utuh_qty+i.prod_pecah_qty) as gr_btr,
                        (i.prod_utuh_qty+i.prod_pecah_qty)/a.EndAyam*100 as hd,
                        (i.prod_utuh_qty+i.prod_pecah_qty)/a.CI_Awal*100 as hh,
                        i.ransum/(i.prod_utuh_berat+i.prod_pecah_berat) as fcr,
                        (SELECT BW FROM data_input WHERE bw > 0 AND tanggal_input <= a.tanggal AND active = 'y' ORDER BY tanggal_input DESC LIMIT 1) as bw,
                        i.keterangan,
                        i.id_data,
                        ((a.kum_dep / a.CI_Awal)*100) as persen_kum_dep,
                        ((a.jumlah_dep / a.CI_Awal)*100) as persen_jumlah_dep
                    FROM
                        (
                            SELECT 
                            k.tanggal
                            , (SELECT a.tanggal_trans FROM kartu_stock_ayam a WHERE a.tipe_trans = 'CO' AND a.tanggal_trans <= k.tanggal ORDER BY a.tanggal_trans DESC LIMIT 1) AS tgl_co
                            , IFNULL( ( SELECT SUM(quantity) AS Ayam FROM kartu_stock_ayam WHERE id_kandang = (SELECT mk.id_kandang FROM master_kandang mk WHERE mk.farm = '$value[4]' AND mk.kandang = '$value[5]') AND tanggal_trans < k.tanggal ) , 0) AS BeginAyam
                            , IFNULL( ( SELECT SUM(quantity *-1) FROM kartu_stock_ayam WHERE id_kandang = (SELECT mk.id_kandang FROM master_kandang mk WHERE mk.farm = '$value[4]' AND mk.kandang = '$value[5]') AND tanggal_trans = k.tanggal AND tipe_trans='MT' ) , 0) AS MT
                            , IFNULL( ( SELECT SUM(quantity *-1) FROM kartu_stock_ayam WHERE id_kandang = (SELECT mk.id_kandang FROM master_kandang mk WHERE mk.farm = '$value[4]' AND mk.kandang = '$value[5]') AND tanggal_trans = k.tanggal AND tipe_trans='AF' ) , 0) AS AF
                            , IFNULL( ( SELECT SUM(quantity) FROM kartu_stock_ayam WHERE id_kandang = (SELECT mk.id_kandang FROM master_kandang mk WHERE mk.farm = '$value[4]' AND mk.kandang = '$value[5]') AND tanggal_trans = k.tanggal AND tipe_trans='TFI' ) , 0) AS TFI
                            , IFNULL( ( SELECT SUM(quantity *-1) FROM kartu_stock_ayam WHERE id_kandang = (SELECT mk.id_kandang FROM master_kandang mk WHERE mk.farm = '$value[4]' AND mk.kandang = '$value[5]') AND tanggal_trans = k.tanggal AND tipe_trans='TFO' ) , 0) AS TFO
                            , IFNULL( ( SELECT SUM(quantity) FROM kartu_stock_ayam WHERE id_kandang = (SELECT mk.id_kandang FROM master_kandang mk WHERE mk.farm = '$value[4]' AND mk.kandang = '$value[5]') AND tanggal_trans = k.tanggal AND tipe_trans='CI' ) , 0) AS CI
                            , IFNULL( ( SELECT SUM(quantity *-1) FROM kartu_stock_ayam WHERE id_kandang = (SELECT mk.id_kandang FROM master_kandang mk WHERE mk.farm = '$value[4]' AND mk.kandang = '$value[5]') AND tanggal_trans <= k.tanggal AND (tipe_trans='TFO' OR tipe_trans='MT') ) , 0) AS kum_dep
                            , IFNULL( ( SELECT SUM(quantity *-1) FROM kartu_stock_ayam WHERE id_kandang = (SELECT mk.id_kandang FROM master_kandang mk WHERE mk.farm = '$value[4]' AND mk.kandang = '$value[5]') AND tanggal_trans = k.tanggal AND (tipe_trans='TFO' OR tipe_trans='MT') ) , 0) AS jumlah_dep
                            , IF(
                            (SELECT a.tanggal_trans FROM kartu_stock_ayam a WHERE a.tipe_trans = 'CO' AND a.tanggal_trans <= k.tanggal ORDER BY a.tanggal_trans DESC LIMIT 1) IS NULL, 
                            (SELECT SUM(quantity) FROM kartu_stock_ayam WHERE id_kandang = (SELECT mk.id_kandang FROM master_kandang mk WHERE mk.farm = '$value[4]' AND mk.kandang = '$value[5]') AND tanggal_trans <= k.tanggal AND (tipe_trans='CI' OR tipe_trans='CO')), 
                            (
                            IF(
                                    (SELECT SUM(quantity) FROM kartu_stock_ayam WHERE id_kandang = (SELECT mk.id_kandang FROM master_kandang mk WHERE mk.farm = '$value[4]' AND mk.kandang = '$value[5]') AND tanggal_trans = k.tanggal AND tipe_trans='CO') IS NULL, 
                                    (SELECT SUM(quantity) FROM kartu_stock_ayam WHERE id_kandang = (SELECT mk.id_kandang FROM master_kandang mk WHERE mk.farm = '$value[4]' AND mk.kandang = '$value[5]') AND tanggal_trans <= k.tanggal AND tanggal_trans > (SELECT a.tanggal_trans FROM kartu_stock_ayam a WHERE a.tipe_trans = 'CO' AND a.tanggal_trans <= k.tanggal ORDER BY a.tanggal_trans DESC LIMIT 1) AND (tipe_trans='CI' OR tipe_trans='CO')), 
                                    ( SELECT SUM(quantity) AS Ayam FROM kartu_stock_ayam WHERE id_kandang = (SELECT mk.id_kandang FROM master_kandang mk WHERE mk.farm = '$value[4]' AND mk.kandang = '$value[5]') AND tanggal_trans <= k.tanggal )
                                ))
                            ) AS CI_Awal
                            , ( SELECT SUM(quantity) AS Ayam FROM kartu_stock_ayam WHERE id_kandang = (SELECT mk.id_kandang FROM master_kandang mk WHERE mk.farm = '$value[4]' AND mk.kandang = '$value[5]') AND tanggal_trans <= k.tanggal ) AS EndAyam
                            , IFNULL( ( SELECT SUM(quantity) AS Ayam FROM kartu_stock_pakan WHERE id_kandang = (SELECT mk.id_kandang FROM master_kandang mk WHERE mk.farm = '$value[4]' AND mk.kandang = '$value[5]') AND tanggal_trans < k.tanggal ) , 0) AS BeginSilo
                            , IFNULL( ( SELECT SUM(quantity) FROM kartu_stock_pakan WHERE id_kandang = (SELECT mk.id_kandang FROM master_kandang mk WHERE mk.farm = '$value[4]' AND mk.kandang = '$value[5]') AND tanggal_trans = k.tanggal AND tipe_trans='SI' ) , 0) AS SI
                            , IFNULL( ( SELECT SUM(quantity *-1) FROM kartu_stock_pakan WHERE id_kandang = (SELECT mk.id_kandang FROM master_kandang mk WHERE mk.farm = '$value[4]' AND mk.kandang = '$value[5]') AND tanggal_trans = k.tanggal AND tipe_trans='RA' ) , 0) AS RA
                            , IFNULL( ( SELECT SUM(quantity *-1) FROM kartu_stock_pakan WHERE id_kandang = (SELECT mk.id_kandang FROM master_kandang mk WHERE mk.farm = '$value[4]' AND mk.kandang = '$value[5]') AND tanggal_trans = k.tanggal AND tipe_trans='SO' ) , 0) AS SO
                            , ( SELECT SUM(quantity) AS Ayam FROM kartu_stock_pakan WHERE id_kandang = (SELECT mk.id_kandang FROM master_kandang mk WHERE mk.farm = '$value[4]' AND mk.kandang = '$value[5]') AND tanggal_trans <= k.tanggal ) AS EndSilo
                        
                            FROM (
                                SELECT DISTINCT(tanggal_trans) AS tanggal
                                FROM kartu_stock_ayam
                                WHERE id_kandang = (SELECT mk.id_kandang FROM master_kandang mk WHERE mk.farm = '$value[4]' AND mk.kandang = '$value[5]') AND
                                tanggal_trans BETWEEN '" . $value[2] . "' AND '" . $value[3] . "'
                                GROUP BY tanggal_trans
                                UNION
                                SELECT DISTINCT(tanggal_trans) AS tanggal
                                FROM kartu_stock_pakan
                                WHERE id_kandang = (SELECT mk.id_kandang FROM master_kandang mk WHERE mk.farm = '$value[4]' AND mk.kandang = '$value[5]') AND
                                tanggal_trans BETWEEN '" . $value[2] . "' AND '" . $value[3] . "'
                                GROUP BY tanggal_trans
                            ) k
                        ) a
                    INNER JOIN data_input i ON a.tanggal = i.tanggal_input AND i.id_kandang=(SELECT mk.id_kandang FROM master_kandang mk WHERE mk.farm = '$value[4]' AND mk.kandang = '$value[5]') AND i.active = 'y') z
                ";

        if($state == "chart"){
            $query .= "     ORDER BY tanggal";
        }elseif($state == "table"){
            $query .= "     ORDER BY tanggal DESC";
        }

        $totalMaxRow = $this->db->query($query)->num_rows();
        $allResult = $this->db->query($query)->result();
        $valuefields = $this->db->query($query)->list_fields();
        foreach($allResult as $r){
            $r->Kumulatif_Deplesi = number_format($r->Kumulatif_Deplesi, 2, '.', '');
            $r->Feed_Intake = number_format($r->Feed_Intake, 2, '.', '');
            $r->FCR = number_format($r->FCR, 2, '.', '');
            $r->Hen_Day = number_format($r->Hen_Day, 2, '.', '');
            $r->Hen_House = number_format($r->Hen_House, 2, '.', '');
            $r->Gr_Per_Btr = number_format($r->Gr_Per_Btr, 2, '.', '');
            $r->Water_Intake = number_format($r->Water_Intake, 2, '.', '');
        }

        if($state == "table"){
            $query .= "     LIMIT $value[0] OFFSET $value[1]";
            $result = $this->db->query($query)->result();

            foreach($result as $r){
                $r->Kumulatif_Deplesi = number_format($r->Kumulatif_Deplesi, 2, '.', '');
                $r->Feed_Intake = number_format($r->Feed_Intake, 2, '.', '');
                $r->FCR = number_format($r->FCR, 2, '.', '');
                $r->Hen_Day = number_format($r->Hen_Day, 2, '.', '');
                $r->Hen_House = number_format($r->Hen_House, 2, '.', '');
                $r->Gr_Per_Btr = number_format($r->Gr_Per_Btr, 2, '.', '');
                $r->Water_Intake = number_format($r->Water_Intake, 2, '.', '');
            }
        }


        $data = array();

        array_push($data, $totalMaxRow);
        if($state == "table"){
            array_push($data, $result);
        }elseif($state == "chart" || $state == "excel"){
            array_push($data, $allResult);
        }

        array_push($data, $valuefields);

        return $data;
    }

    function getFarm($state){
        $query = "  SELECT DISTINCT
                        mk.farm
                    FROM 
                        master_kandang AS mk
                ";
        $result = $this->db->query($query);
        return $result->result();
    }

    function getKandang($state){
        $query = "  SELECT DISTINCT
                        mk.kandang
                    FROM 
                        master_kandang AS mk
                ";
        $result = $this->db->query($query);
        return $result->result();
    }

    function filterChartData($state){
        $query = " SELECT 
                    'Deplesi',
                    'Ayam',
                    'Chick_In',
                    'Ransum',
                    'Feed_Intake',
                    -- 'Standard_Feed_Intake',
                    'Silo',
                    'Air',
                    'Water_Intake',
                    'Total_Telur_Utuh',
                    'Berat_Telur_Utuh',
                    'Total_Telur_Pecah',
                    'Berat_Telur_Pecah',
                    'Gr_Per_Btr',
                    'Hen_Day',
                    'Hen_House',
                    -- 'Standard_Hen_Day',
                    'FCR',
                    -- 'Standard_FCR',
                    'Body_Weight'
                ";
        $result = $this->db->query($query);
        return $result->list_fields();
    }
}
?>