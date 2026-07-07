<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Dashboard extends MY_Controller
{

	Public function index()
	{
		//$this->session->set_userdata($data); 
		$data['title'] = 'Dashboard';
		$data['user'] = $this->db->get_where('z_master_user',['username' => $this->session->userdata('username')])->row_array();
		$data['group_user'] = $this->db->get_where('master_alias',['ori' => $this->session->userdata('group_user')])->row_array();
		
		// if($this->session->has_userdata('username')){
			
		// 	$this->load->view('templates/dash_header', $data);
        //     $this->load->view('templates/dash_sidebar',$data);
        //     $this->load->view('dashboard/index_do', $data);
		// }else{
		// 	$this->session->set_flashdata('message', '<div class="alert alert-warning" role="alert">Anda Harus Login Dahulu</div>');
        //     redirect('login'); 
		// }
		
		$this->load->view('templates/dash_header',$data);
		$this->load->view('templates/dash_sidebar',$data);
		$this->load->view('dashboard/index',$data);

	}
}