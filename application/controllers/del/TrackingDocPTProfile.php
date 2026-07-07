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
		$data['userDOC'] = $this->doc->getUserDOC()->result();
		$data['userTIS'] = $this->doc->getUserTIS()->result();

		$this->load->view('templates/dash_h',$data);
		$this->load->view('page_view/trackingDocPTProfile',$data);
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

	function addPT(){
		$data = html_escape($this->input->post());

		$namaPT = $data['param']['namaPT'];
		$kontakPT = $data['param']['kontakPT'];
		$picTis = $data['param']['picTis'];
		$picDoc = $data['param']['picDoc'];
		$spvTis = $data['param']['spvTis'];
		$spvDoc = $data['param']['spvDoc'];
		$picMgt = $data['param']['picMgt'];

		$valid_input = true;

		if(strlen($kontakPT) >= 14 || strlen($kontakPT) <= 9){
			$valid_input = false;
		}else{
			if(substr($kontakPT, 0, 2) != "08"){
				$valid_input = false;
			}
		}

		$return_value = "";

		if(!$valid_input){
			$return_value = "Format Kontak PT Salah!";
		}else{
			$id_pt = $this->doc->inputPT($namaPT, $kontakPT);

			if($id_pt){
				$return_value = $this->doc->inputGroup($id_pt, $picMgt, $spvTis, $picTis, $spvDoc, $picDoc);

				if($return_value){
					$return_value = "done";
				}
			}
		}

		echo json_encode($return_value);
	}

	function addFarm(){
		$data = html_escape($this->input->post());

		$idPT = $data['param']['idPT'];
		$namaFarm = $data['param']['namaFarm'];
		$alamatFarm = $data['param']['alamatFarm'];
		$kuotaDoc = $data['param']['kuotaDoc'];
		$tipeKandang = $data['param']['tipeKandang'];

		$valid_input = true;
		$return_value = "";

		if((int)$kuotaDoc <= 0){
			$valid_input = false;
			$return_value = "Kuota Doc Tidak Bisa Dibawah 0";
		}

		if($valid_input){
			$result = $this->doc->inputFarm($idPT, $namaFarm, $alamatFarm, $kuotaDoc, $tipeKandang);

			if($result){
				$return_value = "done";
			}
		}

		echo json_encode($return_value);
	}

	function editFarm(){
		$data = html_escape($this->input->post());

		$idFarm = $data['param']['idFarm'];
		$namaFarm = $data['param']['namaFarm'];
		$alamatFarm = $data['param']['alamatFarm'];
		$kuotaDoc = $data['param']['kuotaDoc'];
		$tipeKandang = $data['param']['tipeKandang'];
		$tipeRemainder = $data['param']['tipeRemainder'];
		$mingSpesial = $data['param']['mingSpesial'];

		$valid_input = true;
		$return_value = "";

		if((int)$kuotaDoc <= 0){
			$valid_input = false;
			$return_value = "Kuota Doc Tidak Bisa Dibawah 0";
		}

		if($valid_input){
			$result = $this->doc->editFarm($idFarm, $namaFarm, $alamatFarm, $kuotaDoc, $tipeKandang, $tipeRemainder, $mingSpesial);

			if($result){
				$return_value = "done";
			}else{
				$return_value = $result;
			}
		}

		echo json_encode($return_value);
	}

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

	function dataFarm(){
		$idPT = $this->input->post('param');

		$farm = $this->doc->dataFarm($idPT)->result();

		foreach($farm as $f){
			$f->doc_kuota = number_format((int)$f->doc_kuota,0,",",".");
		}

		echo json_encode($farm);
	}

	function closePT(){
		$idPT = html_escape($this->input->post());

		$idPT = $idPT['param'];

		$result = $this->doc->closePT($idPT);

		echo json_encode($result);
	}

	function closeFarm(){
		$idFarm = html_escape($this->input->post());

		$idFarm = $idFarm['param'];

		$result = $this->doc->closeFarm($idFarm);

		echo json_encode($result);
	}

	function getDataFarm(){
		$data = html_escape($this->input->post());
		$idFarm = $data['param'];

		$result = $this->doc->getDataFarm($idFarm)->result();

		echo json_encode($result[0]);
	}

	function getSurveyFarm(){
		$data = html_escape($this->input->post());
		$idFarm = $data['param'];

		$result = $this->doc->getSurveyFarm($idFarm)->result();

		echo json_encode($result);
	}

	function inputSurvey(){
		$data = html_escape($this->input->post());
		$idFarm = $data['param']['idFarm'];
		$tglSurvey = $data['param']['tglSurvey'];
		$date_now = date("Y-m-d");

		$result_value = true;

		if($tglSurvey <= $date_now){
			$result_value = false;
		}else{
			$result = $this->doc->inputSurvey($idFarm, $tglSurvey);
			if(!$result){
				$result_value = false;
			}
		}

		echo json_encode($result_value);
	}
}
