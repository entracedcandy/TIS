<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class ReminderMTNMaster extends CI_Controller {

	function __construct(){
        parent::__construct();
		$this->load->helper('url');
		$this->load->model('M_Dash', 'dash');
		$this->load->model('M_rmd_mtn_master', 'm_ast');
		$this->load->library('session');
    }
	
	public function index()
	{
		$token = $this->session->userdata('token');
        $user = $this->dash->getUserInfo($token)->row_array();

		$department = $user['department'];
		$group_user = $user['group_user'];
		$id_menu = "4";
		$level_access_pass = $this->m_ast->getLevel_new($group_user, $id_menu)->result();
		$data['level_access'] = $level_access_pass[0]->level_access;

        $data['user'] = $user;
		$data['group_user'] = $this->db->get_where('master_alias',['ori' => $this->session->userdata('group_user')])->row_array();
		$data['title'] = "CP - APPS | Master Reminder Aset";
		$data["js"] = "reminderMTNMaster";

		$idJudul = '0';
		$ket = "";

		$this->load->view('templates/dash_h',$data);
		$this->load->view('page_view/reminderMTNMaster');
		$this->load->view('templates/dash_f',$data);
	}
}
