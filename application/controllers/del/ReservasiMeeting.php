<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class ReservasiMeeting extends CI_Controller {

	function __construct(){
        parent::__construct();
		$this->load->helper('url');
		$this->load->model('M_Dash', 'dash');
		// $this->load->model('M_Profile', 'profile');
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

        $data['user'] = $user;
		$data['group_user'] = $this->db->get_where('master_alias',['ori' => $this->session->userdata('group_user')])->row_array();
		$data['title'] = "CP - APPS | Reservasi Ruang Meeting";
		$data["js"] = "reservasiMeeting";

		$this->load->view('templates/dash_h',$data);
		$this->load->view('page_view/reservasiMeeting');
		$this->load->view('templates/dash_f',$data);
	}
}
