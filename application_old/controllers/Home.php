<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Home extends CI_Controller {

	public function __construct()
	{
		parent::__construct();
		
		$this->load->helper('form');
        $this->load->helper('url');
		$this->load->library('form_validation');
		$this->load->model('M_Home', 'mhm');
	}

	public function index()
	{

		if($this->session->has_userdata('token')){
			redirect('Dashboard_new'); 
		}else{
			$data["notif"] = "";
	
			$this->load->view('page_auth/login', $data);
		}

	}

	function login(){
		// echo "halo";
		// die();

		$username = $this->input->post("userName");
		$password = $this->input->post("password");

		$username = html_escape($username);
		$password = html_escape($password);
		
		$username = strip_tags($username);
		$password = strip_tags($password);

		if($username == "" || $password == ""){
			$data["notif"] = "Harap Mengisi Username atau Password";
			$this->load->view('page_auth/login', $data);
		}else{
			$result = $this->mhm->getPass($username)->result();

			// var_dump($result);
			// var_dump(password_verify($password, $result[0]->password));
			// die();
			
			if($result){
				if(password_verify($password, $result[0]->password)) {


					$token = bin2hex(random_bytes(16));
	
					$log_write = $this->mhm->logWrite($result[0]->id_user, $token, 'LOGIN');
					
					$data =[
						'authenticated'=>true,
						'token'=> $token
					];
	
					$this->session->set_userdata($data);
	
					redirect("Dashboard_new");
				} else {
					$data["notif"] = "Password Salah";
					$this->load->view('page_auth/login', $data);
				}
			}else{
				$data["notif"] = "User Tidak Ada";
				$this->load->view('page_auth/login', $data);
			}
		}

	}

	function logout(){
		$token = $this->session->userdata("token");
		$user = $this->mhm->getIdUser($token)->result();
		$logout = $this->mhm->logWrite($user[0]->id_user, $token, 'LOGOUT');

		$this->session->unset_userdata('token');
		$this->session->sess_destroy();

		redirect('Home');
	}

	function forgot_pass(){
		$user = html_escape($this->input->post("data"));

		$check = $this->mhm->checkUser($user)->result();

		$result = false;

		if($check){
			$otp_angka = random_int(100000, 999999);

			$send = $this->mhm->sendOtp($check[0]->no_hp, $otp_angka);

			if($send){
				$msg = "Kode OTP Anda Adalah : " . $otp_angka;

				$this->send_wa($check[0]->no_hp, $msg);

				$result = true;
			}
		}

		echo json_encode($result);
	}

	function check_otp(){
		$data = html_escape($this->input->post('param'));

		$user = $data['user'];
		$otp_angka = $data['otp'];

		$no_hp = $this->mhm->checkUser($user)->result();

		$send = $this->mhm->checkOtp($no_hp[0]->no_hp, $otp_angka)->result();

		if($send){
			$seed = str_split('abcdefghijklmnopqrstuvwxyz'
                     .'ABCDEFGHIJKLMNOPQRSTUVWXYZ'
                     .'0123456789'); 
			shuffle($seed); 
			$rand = '';
			foreach (array_rand($seed, 6) as $k) $rand .= $seed[$k];

			$result = $this->mhm->updatePass($no_hp[0]->id_user, password_hash($rand, PASSWORD_DEFAULT));

			$msg = "Sandi Baru Untuk Akun Anda Adalah : *" . $rand . "*";

			$this->send_wa($no_hp[0]->no_hp, $msg);
		}else{
			$result = false;
		}

		echo json_encode($result);
	}

	function bfUnload(){
		$token = $this->session->userdata("token");
		$user = $this->mhm->getIdUser($token)->result();
		$logout = $this->mhm->logWrite($user[0]->id_user, $token, 'BFUL');
	}

	function send_wa($target, $msg){
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
