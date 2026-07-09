<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class User_setting extends CI_Controller {

	function __construct(){
        parent::__construct();
		$this->load->helper('url');
		$this->load->model('M_user_setting', 'user');
		$this->load->library('session');
		$this->load->library('zip');
		$this->load->library('email');

		if($this->session->has_userdata('username')){
		}else{
			redirect('login'); 
		}
    }
	
	public function index(){
        $user = $this->db->get_where('z_master_user',['username' => $this->session->userdata('username')])->row_array();
		$department = $user['department'];
		$cost_center = $user['cost_center'];
		$group_user = $user['group_user'];

		$id_menu = "185";

        $data['user'] = $user;
		$data['group_user'] = $this->db->get_where('master_alias',['ori' => $this->session->userdata('group_user')])->row_array();
		$data['title'] = "Master User";

		if($department == "Administrator"){
			$data['department'] = "";
		}else{
			$data['department'] = $department;
		}
		
		$this->load->view('templates/dash_header',$data);
		$this->load->view('templates/dash_sidebar',$data);
		$this->load->view('page_view/user');
	}

	function getData(){
		$user = $this->db->get_where('z_master_user',['username' => $this->session->userdata('username')])->row_array();

		$data = $this->user->data_user($user['department'], $user['cost_center'], $user['group_user'])->result();

		echo json_encode($data);
	}
}
