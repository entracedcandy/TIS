<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class ArsipDataMaster_new extends CI_Controller {

	function __construct(){
        parent::__construct();
		$this->load->helper('url');
		$this->load->model('M_Dash', 'dash');
		$this->load->model('M_arsip_data_master', 'arsipms');
		$this->load->library('session');

		if(!$this->session->has_userdata('token')){
			redirect('Home'); 
		}
    }
	
	public function index()
	{
		$token = $this->session->userdata('token');
        $user = $this->dash->getUserInfo($token)->row_array();

		$department = $user['department'];
		$group_user = $user['group_user'];
		$id_menu = "4";
		$level_access_pass = $this->arsipms->getLevel_new($group_user, $id_menu)->result();
		$data['level_access'] = $level_access_pass[0]->level_access;

        $data['user'] = $user;
		$data['group_user'] = $this->db->get_where('master_alias',['ori' => $this->session->userdata('group_user')])->row_array();
		$data['title'] = "CP - APPS | Master Reminder Dokumen";
		$data["js"] = "arsipDataMaster";

		$idJudul = '0';
		$ket = "";

		$data['dokumenAktif'] = $this->arsipms->dokumenAktif($department, $idJudul, $ket)->result();
		$data['filterJudul'] = $this->arsipms->filterJudul()->result();
		$data['jenisDokumen'] = $this->arsipms->getJenisDokumen()->result();

		$this->load->view('templates/dash_h',$data);
		$this->load->view('page_view/arsipDataMaster_new');
		$this->load->view('templates/dash_f',$data);
	}

	function getTipeDoku(){
		$id_jenis = $this->input->post('id_jenis');
		$tipeDoku = $this->arsipms->getTipeDokumen($id_jenis)->result();

		echo json_encode($tipeDoku);
	}

	function getTitleDoku(){
		$param = $this->input->post('param');
		$id_detail = $param[0];
		$titleDoku = $this->arsipms->getTitleDoku($id_detail)->result();
		foreach($titleDoku as $a){
			$inititle = $a->title_doku;
		}

		echo json_encode($inititle);
	}

	function getDataLog(){
		// $id_rec = $this->input->post('param');
		$param = $this->input->post('param');
		// $param = '37';
		$id_detail = $param[0];
		$logData = $this->arsipms->getDataLog($id_detail)->result();

		$idx = 1;
		$result = "";

		// var_dump($logData);
		
			$result .= "<div class='card'>";
			$result .= 			"<div class='card-body'>";
			$result .= 				"<div class='table-responsive-sm'>";
			$result .= 					"<table class='table-sm'>";
			$result .= 						"<thead>";
			$result .= 							"<tr class='text-center'>";
			$result .= 								"<th>No. </th>";
			$result .= 								"<th style='width:20%'>Judul</th>";
			$result .= 								"<th style='width:30%'>Keterangan</th>";
			$result .= 								"<th>Tanggal Buat Dokumen</th>";
			$result .= 								"<th>Jatuh Tempo Terakhir</th>";
			$result .= 								"<th>Aktif</th>";
			$result .= 							"</tr>";
			$result .= 						"</thead>";
			$result .= 						"<tbody>";
					if(count($logData) == '0'){
			$result .=									"<tr class='table-info text-center'><td colspan='6'><b>Belum ada data.</b></td></tr>";
					}
						foreach($logData as $ld){
							if($ld->active == 'y'){
			$result .=									"<tr class='table-success text-center'>";
							}else{
			$result .= 									"<tr class='table-danger text-center'>";
							}
			$result .= 										"<td>" . $idx++ . "</td>";
			$result .= 										"<td>" . $ld->judul . "</td>";
			$result .= 										"<td>" . $ld->keterangan . "</td>";
			$result .= 										"<td>" . $ld->createdoku . "</td>";
			$result .= 										"<td>" . $ld->jatuh_tempo_terakhir . "</td>";	
							if($ld->active == 'y'){
			$result .= 										"<td><span class='badge badge-success'>Iya</span></td>";	
							}else{
			$result .= 										"<td><span class='badge badge-danger'>Tidak</span></td>";	
							}	
			$result .= 									"</tr>";
						}
			$result .= 						"</tbody>";
			$result .= 					"</table>";
			$result .= 				"</div>";
			$result .= 			"</div>";
			$result .= 		"</div>";
			$result .= "</div>";
			// $idx++;

		echo json_encode($result);
	}

	function getTitleInfoMaster(){
		$param = $this->input->post('param');
		$id_detail = $param[0];
		$titleDoku = $this->arsipms->getTitleDoku($id_detail)->result();
		foreach($titleDoku as $a){
			$inititle = $a->title_doku;
		}

		echo json_encode($inititle);
	}

	function tambahMasDoku(){
		// $user = $this->db->get_where('z_master_user',['username' => $this->session->userdata('username')])->row_array();
		$token = $this->session->userdata('token');
		$user = $this->dash->getUserInfo($token)->row_array();
		$department = $user['department'];

		$param = $this->input->post('param');

		$result = $this->arsipms->tambahMasDoku($param, $department);

		echo json_encode($result);
	}

	function getMasDokuCek(){
		$param = $this->input->post('param');
		$result = $this->arsipms->getMasDokuCek($param)->result();

		echo json_encode($result);
	}

	function ubahMasDoku(){
		// $user = $this->db->get_where('z_master_user',['username' => $this->session->userdata('username')])->row_array();
		$token = $this->session->userdata('token');
		$user = $this->dash->getUserInfo($token)->row_array();
		$department = $user['department'];
		$caption = $user['caption'];
		$id_user = $user['id_user'];

		$param = $this->input->post('param');
		$result = $this->arsipms->ubahMasDokuUpdate($param);
		$result = $this->arsipms->dokuHistoryUpdate($param, $id_user, $caption, $department);
		// $result = $this->arsipms->ubahMasDokuInsert($param); //JIKA ID DETAIL PARENT JADI DI PAKAI!

		echo json_encode($result);
	}

	function getDataProg(){
		// $id_rec = $this->input->post('param');
		$param = $this->input->post('param');
		// $param = '37';
		$id_detail = $param[0];
		$logData = $this->arsipms->getDataProg($id_detail)->result();

		$idx = 0;
		$result = "";

		// var_dump($logData);
		foreach($logData as $ld){

		$result .= "<label><b>Progress Ke ". $ld->seq ." : </b></label>";
		$result .=  "<div class='form-row'>";
		$result .=	"<input id='seq_prog_ed_". $ld->seq ."' name='seq_prog_ed_". $ld->seq ."' value='". $ld->seq ."' hidden>";
		$result .=	"<input id='det_nya_ed_". $ld->seq ."' name='det_nya_ed_". $ld->seq ."' value='' hidden>";
		$result .=	"<div class='form-group col-md-4'>";
		$result .=		"<label>Nama Progress</label>";
		$result .=		"<input class='form-control' id='nama_prog_ed_". $ld->seq ."' name='nama_prog_ed_". $ld->seq ."' style='height:39px;' value='". $ld->namanya ."' required>";
		$result .=		"<div id='n_nama_ed_". $ld->seq ."' class='d-block font-weight-bold' style='display:none'></div>";
		$result .=	"</div>";
		$result .=	"<div class='form-group col-md-7'>";
		$result .=		"<label>Deskripsi Progress</label>";
		$result .=		"<input class='form-control' id='desc_prog_ed_". $ld->seq ."' name='desc_prog_ed_". $ld->seq ."' style='height:39px;' value='". $ld->deskripsi ."' required>";
		$result .=		"<div id='n_desc_ed_". $ld->seq ."' class='d-block font-weight-bold' style='display:none'></div>";
		$result .=	"</div>";
		$result .= "</div>";
		
		$idx++; 
		}

		$result .=	"<input id='maxseq' value='". $idx ."' hidden>";		

		echo json_encode($result);
	}

	function getStep(){
		$id_d = $this->input->post('id_detail');
		$id_rd = $this->input->post('id_rec_detail');
		$getStep = $this->arsipms->getStep($id_d, $id_rd)->result();

		echo json_encode($getStep);
	}

	function tambahProgress(){
		$param = $this->input->post('param');
		for($i = 0; $i < count($param); $i++){
			$result = $this->arsipms->tambahProgress($param[$i]);
		}
		// $result = 1;

		echo json_encode($result);
	}

	function ubahProgress(){
		// $user = $this->db->get_where('z_master_user',['username' => $this->session->userdata('username')])->row_array();
		$token = $this->session->userdata('token');
		$user = $this->dash->getUserInfo($token)->row_array();
		$department = $user['department'];
		$caption = $user['caption'];
		$id_user = $user['id_user'];

		$param = $this->input->post('param');
		for($i = 0; $i < count($param); $i++){
			$result = $this->arsipms->progHistoryUpdate($param, $id_user, $caption, $department);
			$result = $this->arsipms->ubahProgress($param[$i]);
		}
		// $result = 1;

		echo json_encode($result);
	}

	function getDataFilterJudul(){
		// $user = $this->db->get_where('z_master_user',['username' => $this->session->userdata('username')])->row_array();
		$token = $this->session->userdata('token');
		$user = $this->dash->getUserInfo($token)->row_array();
		$department = $user['department'];

		$id_judul = $this->input->post('id_judul');
		$ket = $this->input->post('ket');
		$result = $this->arsipms->dokumenAktif($department, $id_judul, $ket)->result();
		
		echo json_encode($result);
	}

	function getRender(){
		$token = $this->session->userdata('token');
		$user = $this->dash->getUserInfo($token)->row_array();

		// $user = $this->db->get_where('z_master_user',['username' => $this->session->userdata('username')])->row_array();
		$department = $user['department'];

		$id_judul = "";
		$ket = "";
		$result = $this->arsipms->dokumenAktif($department, $id_judul, $ket)->result();
		
		echo json_encode($result);
	}

	function getEdit(){
		$param = $this->input->post('param');
		$result = $this->arsipms->getEdit($param)->result();
		
		echo json_encode($result);
	}
}
