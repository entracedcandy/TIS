<?php

defined('BASEPATH') OR exit('No direct script access allowed');
  
class M_DR_XXXX_XXXXX extends CI_Model{
    public function __construct(){
        $this->load->database();
        
        //Load Database Sesuai Connection yang Akan Digunakan
        $this->pellet = $this->load->database('pellet', TRUE);
    }
    
    //Function Wajib (Jangan dihapus dan diedit)
    function readFunction($funcName, $param, $state){
        if($param !== ""){
            $value = $this->$funcName($param, $state);
        }else{
            $value = $this->$funcName($state);
        }
        return $value;
    }

    // =============================== Model Untuk Table =============================== //

    //Create Function Sesuai Dengan yang Dituliskan Di Database
    function tablePellet($param, $state){
        $value = $param;

        if($state == "table"){
            $value[1] = ($value[1] - 1) * $value[0];
        }
        
        $value[2] = str_replace("T", " ", $value[2]) . ':00';
        $value[3] = str_replace("T", " ", $value[3]) . ":00";
        $query = "  
                    SELECT 
                            gp.Write_Date AS Time, 
                            gp.Consigne_I AS Consigne_I, 
                            gp.Consigne_Temp1 AS Consigne_Temp_1, 
                            gp.Consigne_Temp2 AS Consigne_Temp_2,
                            gp.I AS Ampere,
                            gp.Temp1 AS Temp_1,
                            gp.Temp2 AS Temp_2,
                            gp.Temp3 AS Temp_3,
                            gp.Percent_Fider AS Percent_Fider,
                            gp.Percent_Vanne1 AS Percent_Vanne_1,
                            gp.Percent_Vanne2 AS Percent_Vanne_2,
                            gp.Percent_Vanne3 AS Percent_Vanne_3,
                            gp.Percent_Vanne4 AS Percent_Vanne_4,
                            gp.Steam_Pressure AS Steam_Pressure,
                            gp.Steam_Flowrate AS Steam_Flowrate,
                            gp.Percent_SpeedFat AS Percent_SpeedFat,
                            gp.Kg_H_Fat AS Kg_H_Fat,
                            gp.T_H AS T_H
                    FROM 
                        Graph_Presse2 AS gp
                    WHERE
                        gp.Write_Date >= '" . $value[2] . "' AND gp.Write_Date <'" . $value[3] . "'
                        AND gp.IdPresse = '" . $value[4] . "'
                ";

        if($value[5] !== ""){
            $query .= " AND gp.FeedCode = '" . $value[5] . "'";
        }
       
        $query .= "     ORDER BY gp.Write_Date";

        $totalMaxRow = $this->pellet->query($query)->num_rows();
        $allResult = $this->pellet->query($query)->result();
        $fields = $this->pellet->query($query)->list_fields();

        if($state == "table"){
            $query .= "     OFFSET $value[1] ROWS FETCH NEXT $value[0] ROWS ONLY";
            $result = $this->pellet->query($query)->result();
        }


        $data = array();

        array_push($data, $totalMaxRow);
        if($state == "table"){
            array_push($data, $result);
        }elseif($state == "chart"){
            array_push($data, $allResult);
        }
        array_push($data, $fields);

        return $data;
    }

    // =============================== Model Untuk Value Inputan =============================== //

    function pelletField($state){
        $query = "  SELECT DISTINCT gp.IdPresse AS Pellet
                    FROM Graph_Presse2 AS gp
                ";
        $result = $this->pellet->query($query);
        return $result->result();
    }

    function feedCodeField($state){
        $query = "  SELECT DISTINCT gp.FeedCode AS FeedCode
                    FROM Graph_Presse2 AS gp
                ";
        $result = $this->pellet->query($query);
        return $result->result();
    }

    function filterField($state){
        $query = "  SELECT  top 1
                            gp.Consigne_I AS Consigne_I, 
                            gp.Consigne_Temp1 AS Consigne_Temp_1, 
                            gp.Consigne_Temp2 AS Consigne_Temp_2,
                            gp.I AS Ampere,
                            gp.Temp1 AS Temp_1,
                            gp.Temp2 AS Temp_2,
                            gp.Temp3 AS Temp_3,
                            gp.Percent_Fider AS Percent_Fider,
                            gp.Percent_Vanne1 AS Percent_Vanne_1,
                            gp.Percent_Vanne2 AS Percent_Vanne_2,
                            gp.Percent_Vanne3 AS Percent_Vanne_3,
                            gp.Percent_Vanne4 AS Percent_Vanne_4,
                            gp.Steam_Pressure AS Steam_Pressure,
                            gp.Steam_Flowrate AS Steam_Flowrate,
                            gp.Percent_SpeedFat AS Percent_SpeedFat,
                            gp.Kg_H_Fat AS Kg_H_Fat,
                            gp.T_H AS T_H
                    FROM 
                        Graph_Presse2 AS gp
                ";
        $result = $this->pellet->query($query);
        return $result->list_fields();
    }

    function tablePelletField($state){
        $query = "  SELECT  top 1
                            gp.Write_Date AS Time, 
                            gp.Consigne_I AS Consigne_I, 
                            gp.Consigne_Temp1 AS Consigne_Temp_1, 
                            gp.Consigne_Temp2 AS Consigne_Temp_2,
                            gp.I AS Ampere,
                            gp.Temp1 AS Temp_1,
                            gp.Temp2 AS Temp_2,
                            gp.Temp3 AS Temp_3,
                            gp.Percent_Fider AS Percent_Fider,
                            gp.Percent_Vanne1 AS Percent_Vanne_1,
                            gp.Percent_Vanne2 AS Percent_Vanne_2,
                            gp.Percent_Vanne3 AS Percent_Vanne_3,
                            gp.Percent_Vanne4 AS Percent_Vanne_4,
                            gp.Steam_Pressure AS Steam_Pressure,
                            gp.Steam_Flowrate AS Steam_Flowrate,
                            gp.Percent_SpeedFat AS Percent_SpeedFat,
                            gp.Kg_H_Fat AS Kg_H_Fat,
                            gp.T_H AS T_H
                    FROM 
                        Graph_Presse2 AS gp
                ";
        $result = $this->pellet->query($query);
        return $result->list_fields();
    }

    function showDataPellet($param){
        $value = $param;
        $value[0] = str_replace("T", " ", $value[0]) . ':00';
        $value[0] = date("Y-m-d H:i:s", strtotime($value[0]));
        $value[1] = str_replace("T", " ", $value[1]) . ":00";
        $value[1] = date("Y-m-d H:i:s", strtotime($value[1]));
        $query = "  SELECT 
                            gp.Idligne AS Id,
                            gp.Write_Date AS Time, 
                            gp.Consigne_I AS Consigne_I, 
                            gp.Consigne_Temp1 AS Consigne_Temp_1, 
                            gp.Consigne_Temp2 AS Consigne_Temp_2,
                            gp.I AS Ampere,
                            gp.Temp1 AS Temp_1,
                            gp.Temp2 AS Temp_2,
                            gp.Temp3 AS Temp_3,
                            gp.Percent_Fider AS Percent_Fider,
                            gp.Percent_Vanne1 AS Percent_Vanne_1,
                            gp.Percent_Vanne2 AS Percent_Vanne_2,
                            gp.Percent_Vanne3 AS Percent_Vanne_3,
                            gp.Percent_Vanne4 AS Percent_Vanne_4,
                            gp.Steam_Pressure AS Steam_Pressure,
                            gp.Steam_Flowrate AS Steam_Flowrate,
                            gp.Percent_SpeedFat AS Percent_SpeedFat,
                            gp.Kg_H_Fat AS Kg_H_Fat,
                            gp.T_H AS T_H
                    FROM 
                        Graph_Presse2 AS gp
                    WHERE
                        gp.Write_Date >= '" . $value[0] . "' AND gp.Write_Date <'" . $value[1] . "'
                        AND gp.IdPresse = '" . $value[2] . "'
                ";

        if($value[3] !== ""){
            $query .= "AND gp.FeedCode = '" . $value[3] . "'";
        }

        $query .= "ORDER BY gp.Write_Date";
        $query .= " OFFSET $value[4] ROWS FETCH NEXT $value[5] ROWS ONLY";

        $result = $this->pellet->query($query);
        return $result;

        // return $value[0] . " | " . $value[1];
    }
}
?>