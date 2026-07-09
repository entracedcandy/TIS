<?php

  $pesan = "test wa by karrier jatim";
  wa('087701927921', $pesan);


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
        // 'Authorization: 0HnvXmt9#hAUTe2ki_hI' //change TOKEN to your actual token
        'Authorization: UE66+fw4sK@Xbik5YQFq' //change TOKEN to your actual token
      ),
    ));
    
    $response = curl_exec($curl);
    
    curl_close($curl);
    echo $response;
}

?>