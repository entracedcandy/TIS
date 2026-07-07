<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Profile extends CI_Controller {

	function __construct(){
        parent::__construct();
		$this->load->helper('url');
		$this->load->model('M_Dash', 'dash');
		$this->load->model('M_Profile', 'profile');
		$this->load->library('session');

		if(!$this->session->has_userdata('token')){
			redirect('Home'); 
		}
    }
	
	public function index(){
		$token = $this->session->userdata('token');
        $user = $this->dash->getUserInfo($token)->row_array();

		$cost_center = $user['cost_center']; 

		$department = $user['department'];
		$group_user = $user['group_user'];
		// $id_menu = "5";
		// $level_access_pass = $this->arsip->getLevel_new($group_user, $id_menu)->result();
		// $data['level_access'] = $level_access_pass[0]->level_access;

        $data['user'] = $user;
		$data['group_user'] = $this->db->get_where('master_alias',['ori' => $this->session->userdata('group_user')])->row_array();
		$data['title'] = "CP - APPS | Profile";
		$data["js"] = "profile";

		$this->load->view('templates/dash_h',$data);
		$this->load->view('page_view/profile');
		$this->load->view('templates/dash_f',$data);
	}

	function save_wa(){
		$nomor_wa = html_escape($this->input->post('param'));

		$otp_angka = random_int(100000, 999999);

		$send = $this->profile->sendOtp($nomor_wa, $otp_angka);

		if($send){
			$msg = "Kode OTP Anda Adalah : " . $otp_angka;

			$this->send_wa($nomor_wa, $msg);
		}

		echo json_encode($send);
	}

	function save_pass(){
		$data = html_escape($this->input->post('param'));

		// $data = array(
		// 	"pass_old"=> "123",
		// 	"pass_new"=> "12345",
		// 	"pass_new_cnf"=> "12345"
		// );
		// $data = html_escape($data);

		// $data = $this->input->post('param');


		$pass_old = $data['pass_old'];
		$token = $this->session->userdata('token');
		$user = $this->dash->getUserInfo($token)->row_array();

		$check = $this->profile->getPass($user['id_user'])->result();

		// $pass_old = '123';

		if(password_verify($pass_old, $check[0]->password)) {
			$otp_angka = random_int(100000, 999999);
	
			$send = $this->profile->sendOtp($user['no_hp'], $otp_angka);
	
			$msg = "Kode OTP Anda Adalah : " . $otp_angka;
	
			$this->send_wa($user['no_hp'], $msg);

			$result = true;
		}else{
			$result = false;
		}

		echo json_encode($result);
	}

	function check_otp(){
		$data = html_escape($this->input->post('param'));

		$mode = $data['mode'];

		if($mode == 'nomor'){
			$nomor_wa = $data['nomor_wa'];
			$otp_angka = $data['otp'];
	
			$send = $this->profile->checkOtp($nomor_wa, $otp_angka)->result();
	
			if($send){
				$result = $this->profile->updateOtp($nomor_wa, $otp_angka);
	
				$token = $this->session->userdata('token');
				$user = $this->dash->getUserInfo($token)->row_array();
	
				$result = $this->profile->updateNomorHP($nomor_wa, $user['id_user']);
			}else{
				$result = false;
			}
		}else if($mode == 'pass'){
			$pass = $data['pass'];
			$otp_angka = $data['otp'];

			$token = $this->session->userdata('token');
			$user = $this->dash->getUserInfo($token)->row_array();

			$send = $this->profile->checkOtp($user['no_hp'], $otp_angka)->result();

			if($send){
				$result = $this->profile->updateOtp($user['no_hp'], $otp_angka);

				$result = $this->profile->updatePass($user['id_user'], password_hash($pass, PASSWORD_DEFAULT));
			}else{
				$result = false;
			}
		}

		echo json_encode($result);
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
