<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class FormCreator extends CI_Controller {

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
		// $token = $this->session->userdata('token');
        // $user = $this->dash->getUserInfo($token)->row_array();

		// $cost_center = $user['cost_center']; 

		// $department = $user['department'];
		// $group_user = $user['group_user'];
		// $id_menu = "5";
		// $level_access_pass = $this->arsip->getLevel_new($group_user, $id_menu)->result();
		// $data['level_access'] = $level_access_pass[0]->level_access;

        // $data['user'] = $user;
		// $data['group_user'] = $this->db->get_where('master_alias',['ori' => $this->session->userdata('group_user')])->row_array();
		// $data['title'] = "CP - APPS | Profile";
		// $data["js"] = "profile";

		// $this->load->view('templates/dash_h',$data);
		$this->load->view('page_view/form_creator');
		// $this->load->view('templates/dash_f',$data);
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
