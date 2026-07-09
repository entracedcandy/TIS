<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class ExampleController extends MY_Controller
{
	public function __construct()
	{
		parent::__construct();
		
		$this->load->helper('form');
        $this->load->helper('url');
		$this->load->library('form_validation');
		$this->load->model('M_Dash', 'dash'); // INI MODELS WAJIB UNTUK MENU
		$this->load->model('M_example', 'exam'); // INI MODELS BISA D GANTI SESUAI KEBUTUHAN

		if(!$this->session->has_userdata('token')){
			redirect('Home'); 
		}
	}

	Public function index()
	{
		$token = $this->session->userdata('token');
        $user = $this->exam->getTesterUser($token)->row_array();

        $data['user'] = $user;
		$iduser     = $user['id_user'];
		$department = $user['department'];
		$group_user = $user['group_user'];
		$nama     	= $user['nama'];
		$nik 		= $user['nik'];
		$plant 		= $user['plant'];
		
		$data['nama'] = $nama;
		$data['nik'] = $nik;
		$data['plant'] = $plant;
		$data['group_user'] = $group_user;
		$data['department'] = $department;
		$data['iduser'] = $iduser;
		
		$data["title"] = "CP - APPS | Example Controller"; // WAJIB UNTUK SET TITLE HALAMAN
		// $data["js"] = "examplejs"; // JIKA MEMBUTUHKAN JS TAMBAHKAN DI FOLDER application > page_js
		// var_dump($data);
		// die();

		$this->load->view('templates/dash_h', $data);
		$this->load->view('page_view/exampleview',$data);
		$this->load->view('templates/dash_f', $data);
	}
}