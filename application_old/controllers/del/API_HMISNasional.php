<?php

    ini_set('memory_limit', '-1');
    ini_set('display_errors', 1);
    ini_set('display_startup_errors', 1);
    error_reporting(E_ALL);
    date_default_timezone_set('Asia/Jakarta');

    $dataraw = $_POST['data'];
    $cdplant = $_POST['codeplant'];

    $date = new DateTime('now');
    $nowd = $date->format('Y-m-d H:i:s');
    $dmon = date('m');

    try {

        $host= 'localhost';
        $db = 'cp_hmis';
        $user = 'cpi';
        $password = 'Onta87!@#'; 
        
        $dsn = "pgsql:host=$host;dbname=$db;";
        $pdo = new PDO($dsn, $user, $password, [PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION]);

        if (!$pdo){
            die("Connection failed");
        }
        else{
            echo "Connected! \n";
        }

        $pdo->beginTransaction();

        // $cdplant = str_replace(",","','",$cdplant);

        $msgwa = "";
        
        // Select query to check if data exists
        $codeplntArray = explode(',', $cdplant);
        $placeholders = implode(',', array_fill(0, count($codeplntArray), '?'));
        $sQuery = "SELECT * FROM s_hmisnasional WHERE month_code = ? AND plant_code IN ($placeholders)";
        $stmt = $pdo->prepare($sQuery);

        $params = array_merge([$dmon], $codeplntArray);
        $stmt->execute($params);

        // Get the row count
        $rowCount = $stmt->rowCount();

        if ($rowCount > 0) {
            // Data exists, proceed to delete
            $dQuery = "DELETE FROM s_hmisnasional WHERE month_code = ? AND plant_code IN ($placeholders)";
            $stmtDelete = $pdo->prepare($dQuery);

            $paramsDelete = array_merge([$dmon], $codeplntArray);
            $stmtDelete->execute($paramsDelete);
            
            echo "Data deleted successfully.\n";
            $msgwa .= "Data deleted successfully.\n";
        } else {
            echo "Data not found, Procces inserting data.\n";
            $msgwa .= "Data not found, Procces inserting data.\n";
        }

        $dataexpfirst = explode("~~",$dataraw);
        $i = 0; $hitit = 0;

        $query = "INSERT INTO s_hmisnasional (nopeg, nama, jab_kode, begda, endda, jenis_pembayaran, hari_kerja, pendidikan_akhir, no_bpjs, no_bpjs_kes, tgl_lahir, plant_code, month_code) VALUES (:nopeg, :nama, :jab_kode, :begda, :endda, :jenis_pembayaran, :hari_kerja, :pendidikan_akhir, :no_bpjs, :no_bpjs_kes, :tgl_lahir, :plant_code, :month_code);";

        $stmt = $pdo->prepare($query);
        foreach($dataexpfirst as $firts){
            $dataexpsecon = explode("||",$dataexpfirst[$i]);
            if($dataexpsecon[0]){
                // print_r($dataexpsecon);
                $plant = explode("_",$dataexpsecon[2]);
                $plant = $plant[0];
                // print_r($plant[0]);
                // if($hitit > 180){
                    
                    $stmt->bindValue(':nopeg', $dataexpsecon[0]);
                    $stmt->bindValue(':nama', $dataexpsecon[1]);
                        $jabcode = str_replace("DAN","&",$dataexpsecon[2]);
                    $stmt->bindValue(':jab_kode', $jabcode);
                    $stmt->bindValue(':begda', $dataexpsecon[3]);
                    $stmt->bindValue(':endda', $dataexpsecon[4]);
                    $stmt->bindValue(':jenis_pembayaran', $dataexpsecon[5]);
                    $stmt->bindValue(':hari_kerja', $dataexpsecon[6]);
                    $stmt->bindValue(':pendidikan_akhir', $dataexpsecon[7]);
                    $stmt->bindValue(':no_bpjs', $dataexpsecon[9]);
                    $stmt->bindValue(':no_bpjs_kes', $dataexpsecon[10]);
                    $stmt->bindValue(':tgl_lahir', $dataexpsecon[8]);
                    $stmt->bindValue(':plant_code', $plant);
                    $stmt->bindValue(':month_code', $dmon);
                    $stmt->execute();

                // }
                // echo "[".$dataexpsecon[0];
                $hitit++;
            }
            $i++;
        }

        $logquery = "INSERT INTO s_logsuccess (info,date_log,jmlinsert,month_code,plant_code) VALUES (:info, :datelog, :jmlin, :monthcd, :cdplantinfo);";
        $stmt = $pdo->prepare($logquery);
        
        $stmt->bindValue(':info', 'Success');
        $stmt->bindValue(':datelog', $nowd);
        $stmt->bindValue(':jmlin', $hitit);
        $stmt->bindValue(':monthcd', $dmon);
        $stmt->bindValue(':cdplantinfo', $cdplant);
        $stmt->execute();

        $result = $pdo->lastInsertId('s_logsuccess_id_seq');

        $pdo->commit();

        echo "Both inserts executed successfully.\n";
        $msgwa .= "Both inserts executed successfully.\n";

$pesan = "*HMIS Upload to VPS Success*
            
Today : *" . $nowd . "*,
System HMIS at Plant Code : *".$cdplant."*
With Info :
" . $msgwa . "
Have been success insert total *".$hitit."* rows data

_Pesan ini adalah Pesan Otomatis Dari Sistem CPI Jatim_";
$fixwa = wa("081252888774", $pesan);

    } catch (PDOException $e) {
        $pdo->rollBack();
        echo "Error: " . $e->getMessage() . "\n";
        echo "Trace: " . $e->getTraceAsString();
    }

    $pdo = null;

    function wa($target, $msg){
        $curl = curl_init();
            
        curl_setopt_array($curl, array(
          CURLOPT_URL => 'https://api.fonnte.com/send',
          CURLOPT_RETURNTRANSFER => true,
          CURLOPT_ENCODING => '',
          CURLOPT_MAXREDIRS => 10,
          CURLOPT_TIMEOUT => 0,
          CURLOPT_FOLLOWLOCATION => true,
          CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
          CURLOPT_CUSTOMREQUEST => 'POST',
          CURLOPT_POSTFIELDS => array(
            'target' => $target,
            'message' => $msg,
            'countryCode' => '62', //optional
            'delay' => '180', //optional
        ),
          CURLOPT_HTTPHEADER => array(
            'Authorization: Li46RvGdGy+3e+7bUTJ9' //change TOKEN to your actual token
          ),
        ));
        
        $response = curl_exec($curl);
        
        curl_close($curl);
        // echo $response;
        return $response;
    }
    
    // print_r($logquery);
    
    // echo json_encode($query);
    echo json_encode($result);

?>