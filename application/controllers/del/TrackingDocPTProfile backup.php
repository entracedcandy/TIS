<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class TrackingDocPTProfile extends CI_Controller {

	function __construct(){
        parent::__construct();
		$this->load->helper('url');
		$this->load->model('M_Dash', 'dash');
		$this->load->model('M_Home', 'mhm');
		$this->load->model('M_DocPTProfile', 'doc');
		$this->load->library('session');

		if(!$this->session->has_userdata('token')){
			redirect('Home'); 
		}else{
			$token = $this->session->userdata('token');
        	$info = $this->dash->userValid($token)->row_array();

			if($info['validasi'] == 'OUT'){
				$user = $this->mhm->getIdUser($token)->result();
				$logout = $this->mhm->logWrite($user[0]->id_user, $token, 'LOGOUT');

				$this->session->unset_userdata('token');
				$this->session->sess_destroy();

				redirect('Home');
			}
		}
    }
	
	public function index()
	{
		$token = $this->session->userdata('token');
        $user = $this->dash->getUserInfo($token)->row_array();

		$department = $user['department'];
		$group_user = $user['group_user'];
		$id_menu = "11";
		$awal = 0;

        $data['user'] = $user;
		$data['group_user'] = $this->db->get_where('master_alias',['ori' => $this->session->userdata('group_user')])->row_array();
		$data['title'] = "CP - APPS | DOC PT Profile";
		$data["js"] = "trackingDocPTProfile";

		$data['allPT'] = $this->doc->getAllPT()->result();
		$data['profilePT'] = $this->doc->getPTAwal()->result();

		$this->load->view('templates/dash_h',$data);
		$this->load->view('page_view/trackingDocPTProfile',$data);
		$this->load->view('templates/dash_f',$data);
	}

	function getKarList($listk){
		$kar = $this->doc->getKarMod($listk)->result();

		echo json_encode($kar);
	}

	// function getPTFilter(){
	// 	// $idPT = $this->input->post('param');
	// 	$idPT = 9999;
	// 	$farm = $this->doc->getPTAwal($idPT)->result();
	// 	if(is_null($idPT)){
			
			
	// 	}

	// 	echo json_encode($farm);
	// }

	function getFarm(){
		$idPT = $this->input->post('param');

		$farm = $this->doc->getAllFarm($idPT)->result();

		echo json_encode($farm);
	}
}
