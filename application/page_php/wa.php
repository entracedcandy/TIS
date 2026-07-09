<?php
// require("../../assets/php/connection/dbconnect1.php");

$servername = "localhost";
$username = "cpi";
$password = "Onta87!@#";
$dbname = "cpar";

// Create connection
$con = new mysqli($servername, $username, $password, $dbname);
// Check connection
if ($con->connect_error) 
{
  die("Connection failed: " . $con->connect_error);
} 


// ===================================================================================================================== //


echo "test schedule wa" . "<br><br>";

// Reminder

$query =  "
            SELECT 
              a.id_rec_detail, 
              a.id_rec, 
              b.id_detail, 
              a.reminder_dur, 
              a.tanggal_jatuh_tempo, 
                DATE_SUB(
                  a.tanggal_jatuh_tempo, INTERVAL a.reminder_dur DAY
                ) AS tgl_reminder, 
              b.caption, 
              b.detail, 
              c.caption as caption_doku 
            FROM 
              a_rec_doku_det a, 
              a_rec_doku b, 
              a_master_det_doku c 
            WHERE 
              a.id_rec = b.id_rec 
              AND c.id_detail = b.id_detail 
              AND a.status <> 1 
              AND DATE_SUB(
                a.tanggal_jatuh_tempo, INTERVAL a.reminder_dur DAY
              ) = now()
          ";

$result_reminder = mysqli_query($con,$query);

$data_reminder = array();

while($row = mysqli_fetch_array($result_reminder)) {
  array_push($data_reminder, $row);
}

foreach($data_reminder as $dr){
  $query6 =  "
          SELECT 
            mu.id_user,
            mu.caption,
            mu.no_telp
          FROM 
            a_rec_contact_user a,
            master_user mu
          WHERE 
            a.id_rec = '" . $dr['id_rec'] . "' AND
            a.id_user = mu.id_user
          ";

  $result_all_reminder = mysqli_query($con,$query6);

  while($row = mysqli_fetch_array($result_all_reminder)) {

    if($row['no_telp'] == NULL || $row['no_telp'] == ""){
      $nomor_hp = "081252888774";
    }else{
      $nomor_hp = $row['no_telp'];
    }

    $pesan = "Dokumen Reminder :

Dokumen : " . $dr['caption_doku'] . "
Data Dokumen : " . $dr['caption'] . "
Detail : " . $dr['detail'] . "
Jatuh Tempo : " . $dr['tanggal_jatuh_tempo'] . " 

*Akan Jatuh Tempo Pada Tanggal " . $row['tanggal_jatuh_tempo'] . "*

_Pesan ini adalah Pesan Otomatis Dari Sistem CPI Jatim_";

    wa($nomor_hp, $pesan);
  }
}

// H-7

$query2 =  "
          SELECT 
            a.id_rec_detail, 
            a.id_rec, 
            b.id_detail, 
            a.reminder_dur, 
            a.tanggal_jatuh_tempo, 
              DATE_SUB(
                a.tanggal_jatuh_tempo, INTERVAL a.reminder_dur DAY
              ) AS tgl_reminder, 
            b.caption, 
            b.detail, 
            c.caption as caption_doku 
          FROM 
            a_rec_doku_det a, 
            a_rec_doku b, 
            a_master_det_doku c 
          WHERE 
            a.id_rec = b.id_rec 
            AND c.id_detail = b.id_detail 
            AND a.status <> 1 
            AND DATE_SUB(
              a.tanggal_jatuh_tempo, INTERVAL 7 DAY
            ) = now()
          ";
          
$result_hmin_7 = mysqli_query($con,$query2);

$data_hmin_7 = array();

while($row = mysqli_fetch_array($result_hmin_7)) {
  array_push($data_hmin_7, $row);
}

foreach($data_hmin_7 as $dh){
  $query5 =  "
          SELECT 
            mu.id_user,
            mu.caption,
            mu.no_telp
          FROM 
            a_rec_contact_user a,
            master_user mu
          WHERE 
            a.id_rec = '" . $dh['id_rec'] . "' AND
            a.id_user = mu.id_user
          ";

  $result_all_hmin_7 = mysqli_query($con,$query5);

  while($row = mysqli_fetch_array($result_all_hmin_7)) {

    if($row['no_telp'] == NULL || $row['no_telp'] == ""){
      $nomor_hp = "081252888774";
    }else{
      $nomor_hp = $row['no_telp'];
    }

    $pesan = "Dokumen Reminder :

Dokumen : " . $dh['caption_doku'] . "
Data Dokumen : " . $dh['caption'] . "
Detail : " . $dh['detail'] . "
Jatuh Tempo : " . $dh['tanggal_jatuh_tempo'] . " 

*Akan Jatuh Tempo 7 Hari Lagi*

_Pesan ini adalah Pesan Otomatis Dari Sistem CPI Jatim_";

    var_dump($nomor, $pesan);
    wa($nomor_hp, $pesan);
  }
}

// Jatuh Tempo

$query3 =  "
          SELECT 
            a.id_rec_detail, 
            a.id_rec, 
            b.id_detail, 
            a.reminder_dur, 
            a.tanggal_jatuh_tempo, 
              DATE_SUB(
                a.tanggal_jatuh_tempo, INTERVAL a.reminder_dur DAY
              ) AS tgl_reminder, 
            b.caption, 
            b.detail, 
            c.caption as caption_doku 
          FROM 
            a_rec_doku_det a, 
            a_rec_doku b, 
            a_master_det_doku c 
          WHERE 
            a.id_rec = b.id_rec 
            AND c.id_detail = b.id_detail 
            AND a.status <> 1 
            AND a.tanggal_jatuh_tempo = now()
          ";
          
$result_end = mysqli_query($con,$query3);

$data_end = array();

while($row = mysqli_fetch_array($result_end)) {
  array_push($data_end, $row);
}

foreach($data_end as $de){
  $query4 =  "
          SELECT 
            mu.id_user,
            mu.caption,
            mu.no_telp
          FROM 
            a_rec_contact_user a,
            master_user mu
          WHERE 
            a.id_rec = '" . $de['id_rec'] . "' AND
            a.id_user = mu.id_user
          ";

  $result_all_end = mysqli_query($con,$query4);

  while($row = mysqli_fetch_array($result_all_end)) {

    if($row['no_telp'] == NULL || $row['no_telp'] == ""){
      $nomor_hp = "081252888774";
    }else{
      $nomor_hp = $row['no_telp'];
    }

    $pesan = "Dokumen Reminder :

Dokumen : " . $de['caption_doku'] . "
Data Dokumen : " . $de['caption'] . "
Detail : " . $de['detail'] . "
Jatuh Tempo : " . $de['tanggal_jatuh_tempo'] . " 

*Telah Memasuki Masa Jatuh Tempo*

_Pesan ini adalah Pesan Otomatis Dari Sistem CPI Jatim_";

    wa($nomor_hp, $pesan);
  }
}

wa("081252888774", "Test WA");

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
    ),
      CURLOPT_HTTPHEADER => array(
        'Authorization: UE66+fw4sK@Xbik5YQFq' //change TOKEN to your actual token
      ),
    ));
    
    $response = curl_exec($curl);
    
    curl_close($curl);
    echo $response;
}

?>