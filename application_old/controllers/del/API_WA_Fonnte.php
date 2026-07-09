<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class API_WA_Fonnte extends CI_Controller {

	function __construct(){
        parent::__construct();
		$this->load->model('M_Form', 'form');
    }
	
	public function index(){
		$this->wa("082228909916", "_Halo_\n\n\n\n   *Aditya*");
	}

	public function wa($target, $msg){
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
            'target' => $target, //0812346789
            'message' => $msg, //"Halo"
            'countryCode' => '62', //optional
        ),
          CURLOPT_HTTPHEADER => array(
            'Authorization: Li46RvGdGy+3e+7bUTJ9' //change TOKEN to your actual token
          ),
        ));
        
        $response = curl_exec($curl);
        
        curl_close($curl);
        echo $response;
    }
}
