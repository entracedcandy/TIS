<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class TrackingDocHasilSurvey extends CI_Controller {

	function __construct(){
        parent::__construct();
		$this->load->helper('url');
		$this->load->model('M_Dash', 'dash');
		$this->load->model('M_Home', 'mhm');
		$this->load->model('M_DocSurvey', 'doc');
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
		$id_menu = "13";
		$awal = 0;

        $data['user'] = $user;
		$data['group_user'] = $this->db->get_where('master_alias',['ori' => $this->session->userdata('group_user')])->row_array();
		$data['title'] = "CP - APPS | DOC Hasil Survey";
		$data["js"] = "trackingDocHasilSurvey";

		$data['allPT'] = $this->doc->getAllPT()->result();
		$data['profilePT'] = $this->doc->getPTAwal()->result();
		$data['userDOC'] = $this->doc->getUserDOC()->result();
		$data['userTIS'] = $this->doc->getUserTIS()->result();

		
		$this->load->view('templates/dash_h',$data);
		$this->load->view('page_view/trackingDocHasilSurvey',$data);
		$this->load->view('templates/dash_f',$data);
	}

	function getKarList($listk){
		$kar = $this->doc->getKarMod($listk)->result();

		echo json_encode($kar);
	}

	function searchData(){
		$data = html_escape($this->input->post());
		$result = $this->doc->getPTAwal()->result();
		
		$return = [
			'input' => $data,
			'output' => $result
		];

		echo json_encode($return);

	}

	// function getPTFilter(){
	// 	// $idPT = $this->input->post('param');
	// 	$idPT = 9999;
	// 	$farm = $this->doc->getPTAwal($idPT)->result();
	// 	if(is_null($idPT)){
			
			
	// 	}

	// 	echo json_encode($farm);
	// }

	function updateData(){
		$data = html_escape($this->input->post());
		$id_pt = array(
			'id_pt' => $data['id_user']
		);
		$id_user = array(
			'id' => $data['id_user']
		);

		$result = array(
			'id_pt' => $data['id_user'],
			'user_mgt' => $data['picMgt'],
			'spv_tis' => $data['spvTis'],
			'user_tis' => $data['picTis'],
			'spv_doc' => $data['spvDoc'],
			'user_doc' => $data['picDoc'],
		);

		$user = array(
			'nama_pt' => $data['namaPT'],
			'kontak_pt' => $data['kontakPT']
		); 
		$insert = $this->doc->updateNotifData($result, $id_pt);
		$insert2 = $this->doc->updatePTData($user, $id_user);
		$data['insert'] = $insert;
		$data['insert2'] = $insert2;
		echo json_encode($data);
		// if ($insert || $insert2) {
		// // 	# code...
		// 	echo json_encode($data);
		// }
				
		// if ($insert) {
		// 	# code...
		// 	$this->session->set_userdata('success', "Data telah terupload");
        //     redirect(base_url('/')."TrackingDocPTProfile" );
        //     die();
		// }else{
		// 	$this->session->set_userdata('err', "Data gagal terupload");
        //     redirect(base_url('/')."TrackingDocPTProfile" );
        //     die();

		// }

	}

	function getFarm(){
		$idPT = $this->input->post('param');

		$farm = $this->doc->getAllFarm($idPT)->result();

		echo json_encode($farm);
	}
}
