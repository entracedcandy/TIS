<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class TrackingDoc extends CI_Controller {

	function __construct(){
        parent::__construct();
		$this->load->helper('url');
		$this->load->model('M_Dash', 'dash');
		$this->load->model('M_Home', 'mhm');
		$this->load->model('M_Doc', 'doc');
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

        $data['user'] = $user;
		// $data['group_user'] = $this->db->get_where('master_alias',['ori' => $this->session->userdata('group_user')])->row_array();
		$data['group_user'] = $group_user;
		$data['department'] = $department;
		$data['title'] = "CP - APPS | DOC Tracking";
		$data["js"] = "trackingDoc";

		$data['allPT'] = $this->doc->getAllPT()->result();

		$this->load->view('templates/dash_h',$data);
		$this->load->view('page_view/trackingDoc',$data);
		$this->load->view('templates/dash_f',$data);
	}

	function getFarm(){
		$idPT = $this->input->post('param');

		$farm = $this->doc->getAllFarm($idPT)->result();

		echo json_encode($farm);
	}

	function insertCI(){
		$data = $this->input->post('param');

		$total_ci = $data['total_ci'];
		$date_ci = $data['tanggal_ci'];
		$id_farm = $data['id_farm'];
		$last_day_ci = $data['last_day_ci'];

		$result = '';
		
		if($data['status_farm'] != '1'){
			$status_now = 1;

			$id_prog = $this->doc->insertProg($id_farm, $date_ci, $status_now);
			$insert_ci = $this->doc->insertCI($id_prog, $total_ci, $date_ci);

			if($insert_ci){
				$id_pt = $this->doc->getIDPT($id_farm)->result();
				$id_pt = $id_pt[0]->id_pt;

				$result = $id_pt;
			}else{
				$result = $insert_ci;
			}
		}else{
			$id_prog = $data['id_progress'];

			$validasiTanggalCI = $this->doc->cekDateCI($date_ci, $id_prog)->result();

			if($validasiTanggalCI[0]->status_valid == 'Aman'){
				$insert_ci = $this->doc->insertCI($id_prog, $total_ci, $date_ci);

				if($insert_ci){
					$id_pt = $this->doc->getIDPT($id_farm)->result();
					$id_pt = $id_pt[0]->id_pt;

					$result = $id_pt;
				}else{
					$result = $insert_ci;
				}
			}else{
				$result = $validasiTanggalCI[0]->status_valid;
			}
		}
		
		if($last_day_ci == "true"){
			$status_now = 2;

			$updateProg = $this->doc->updateFarmProg($id_prog, $status_now);
		}
		echo json_encode($result);
	}

	function getInfoCI(){
		$id_prog = $this->input->post('param');

		$infoDateCI = $this->doc->infoDateCI($id_prog)->result();
		$infoLogCI = $this->doc->infoCILog($id_prog)->result();

		$result = array(
			"info_date_ci" => $infoDateCI,
			"info_log_ci" => $infoLogCI
		);

		echo json_encode($result);
	}

	function getInfoTIS(){
		$id_prog = $this->input->post('param');

		$infoDateCI = $this->doc->infoDateCI($id_prog)->result();
		$infoLogCI = $this->doc->infoCILog($id_prog)->result();

		$result = array(
			"info_date_ci" => $infoDateCI,
			"info_log_ci" => $infoLogCI
		);

		echo json_encode($result);
	}

	function getInfoDOC(){
		$id_prog = $this->input->post('param');

		$infoDateCI = $this->doc->infoDateCI($id_prog)->result();
		$infoLogCI = $this->doc->infoCILog($id_prog)->result();

		$result = array(
			"info_date_ci" => $infoDateCI,
			"info_log_ci" => $infoLogCI
		);

		echo json_encode($result);
	}
}
