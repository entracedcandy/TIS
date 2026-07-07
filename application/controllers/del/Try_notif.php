<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Try_notif extends CI_Controller {

	public function __construct()
	{
		parent::__construct();
		
		$this->load->helper('form');
        $this->load->helper('url');
		$this->load->library('form_validation');
	}

	public function index()
	{
		if($this->session->has_userdata('token')){
			redirect('Dashboard_new'); 
		}else{
			$data["notif"] = "";
	
			$this->load->view('page_view/try');
		}
	}
}
