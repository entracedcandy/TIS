<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Login_FC extends CI_Controller {

	public function __construct()
	{

		parent::__construct();
		
		$this->load->helper('form');
        $this->load->helper('url');
		$this->load->helper('directory');
		$this->load->library('form_validation');
		// $this->load->model('M_data', 'data');
	}

	public function index()
	{
		$data['title'] = 'Login Form';

		$file = "";
		$map = directory_map('./asset/face-api/labels_new');

		foreach($map as $m){
			if(strpos($m,".jpeg") || strpos($m,".jpg") || strpos($m,".png")){
				$file .= $m . "|";
			}
		}

		$data['files'] = rtrim($file, "|");

		$this->load->view('page_view/home_fc', $data);
	}

	function getList(){
		$file = "";
		$map = directory_map('./asset/face-api/labels_new');

		foreach($map as $m){
			if(strpos($m,".jpeg") || strpos($m,".jpg") || strpos($m,".png")){
				$file .= $m . "|";
			}
		}

		$file = rtrim($file, "|");

		echo json_encode($file);
	}

	function GL(){
		$id = $this->input->post("id");

		$result = $this->data->getData($id)->result();

		$resultPhone = $this->data->getPhone($id)->result();

		$phone = $resultPhone[0]->phone;
		$otp = mt_rand(100000,999999);

// 		$msg = "Kode OTP anda adalah: *" . $otp . "*. Harap menjaga kode OTP ini dan jangan di bagikan kepada orang lain.
		
// _Pesan ini adalah Pesan Otomatis Dari Sistem CPI Jatim_";

// 		$this->wa($phone, $msg);

		echo json_encode($result);
	}

	function sendOtp(){
		$id = $this->input->post("id");

		$resultPhone = $this->data->getPhone($id)->result();

		$phone = $resultPhone[0]->phone;
		$otp = mt_rand(100000,999999);

		$msg = "Kode OTP anda adalah: *" . $otp . "*. Harap menjaga kode OTP ini dan jangan di bagikan kepada orang lain.
		
_Pesan ini adalah Pesan Otomatis Dari Sistem CPI Jatim_";

		$this->wa($phone, $msg);

		echo json_encode("done");
	}

	function Access(){
		// Store the string into variable 
		$password = 'Password'; 
		
		// Use password_hash() function to 
		// create a password hash 
		$hash_default_salt = password_hash($password, PASSWORD_DEFAULT); 

		var_dump($hash_default_salt);

		echo "<br>" . "==============================" . "<br>";
		
		$hash_variable_salt = password_hash($password, PASSWORD_DEFAULT, array('cost' => 9)); 

		var_dump($hash_variable_salt);

		echo "<br>" . "==============================" . "<br>";
		
		// Use password_verify() function to 
		// verify the password matches 
		var_dump(password_verify('Password', $hash_default_salt )); 

		echo "<br>" . "==============================" . "<br>";
		
		var_dump(password_verify('Password', $hash_variable_salt )); 

		echo "<br>" . "==============================" . "<br>";
		
		var_dump(password_verify('Password123', $hash_default_salt ));
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
            'target' => $target,
            'message' => $msg,
            'countryCode' => '62', //optional
        ),
          CURLOPT_HTTPHEADER => array(
            'Authorization: Li46RvGdGy+3e+7bUTJ9' //change TOKEN to your actual token
          ),
        ));
        
        $response = curl_exec($curl);
        
        curl_close($curl);
        // echo $response;
    }
}
