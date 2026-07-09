<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class ArsipData_new extends CI_Controller {

	function __construct(){
        parent::__construct();
		$this->load->helper('url');
		$this->load->model('M_Dash', 'dash');
		$this->load->model('M_arsip_data', 'arsip');
		$this->load->library('session');
		$this->load->library('zip');
		$this->load->library('email');

		if(!$this->session->has_userdata('token')){
			redirect('Home'); 
		}
    }
	
	public function index(){
		$token = $this->session->userdata('token');
        $user = $this->dash->getUserInfo($token)->row_array();

		$cost_center = $user['cost_center']; 

		$department = $user['department'];
		$group_user = $user['group_user'];
		$id_menu = "5";
		$level_access_pass = $this->arsip->getLevel_new($group_user, $id_menu)->result();
		$data['level_access'] = $level_access_pass[0]->level_access;

        $data['user'] = $user;
		$data['group_user'] = $this->db->get_where('master_alias',['ori' => $this->session->userdata('group_user')])->row_array();
		$data['title'] = "CP - APPS | Reminder Dokumen";
		$data["js"] = "arsipData";

		$idJudul = '0';
		$ket = "";
		$det = "";
		$start = "";
		$end = "";
		$group = "";
		
		$jumlahData = $this->arsip->dokumenAktif($group_user, $department, $idJudul, $ket, $det, $start, $end, $group, $cost_center, 0, 0, false)->num_rows();
		$data['jumlahData'] = $jumlahData;

		$rpp = 5;
		$page = 1;

		$data['jumlahPaging'] = ceil($jumlahData / $rpp);
		$data['halaman'] = $page;
		$data['rpp'] = $rpp;

		$data['dokumenAktif'] = $this->arsip->dokumenAktif($group_user, $department, $idJudul, $ket, $det, $start, $end, $group, $cost_center, $rpp, $page, false)->result();
		$data['filterJudul'] = $this->arsip->filterJudul($department, $cost_center)->result();
		$data['filterGroup'] = $this->arsip->filterGroup($department, $cost_center)->result();
		$data['jenisDokumen'] = $this->arsip->getJenisDokumen()->result();
		// $data['list_dept'] = $this->arsip->list_dept()->result();
		$data['listDept'] = $this->arsip->getDept($user['plant'])->result();
		$data['list_cc'] = $this->arsip->list_cc()->result();
		if($department == "Administrator"){
			$data['department'] = "";
		}else{
			$data['department'] = $department;
		}
		
		$fg = $this->arsip->filterGroup($department, $cost_center)->result();
		$passFG = "";

		foreach($fg as $fg){
			$passFG .= $fg->group_doku . ",";
		}

		$passFG = substr($passFG,0,strlen($passFG)-1);

		$data["passFG"] = $passFG;

		$this->load->view('templates/dash_h',$data);
		$this->load->view('page_view/arsipData_new');
		$this->load->view('templates/dash_f',$data);
	}

	function getTipeDoku(){
		$id_jenis = $this->input->post('id_jenis');
		$tipeDoku = $this->arsip->getTipeDokumen($id_jenis)->result();

		echo json_encode($tipeDoku);
	}

	function getDokumen(){
		$id_jenis = $this->input->post('id_jenis');
		$id_tipe = $this->input->post('id_tipe');

		// $user = $this->db->get_where('z_master_user',['username' => $this->session->userdata('username')])->row_array();
		$token = $this->session->userdata('token');
		$user = $this->dash->getUserInfo($token)->row_array();
		$department = $user['department'];

		$dokumen = $this->arsip->getDokumen($id_jenis, $id_tipe, $department)->result();

		echo json_encode($dokumen);
	}

	function getDokumenDur(){
		$id_detail = $this->input->post('id_detail');

		$dokumen_dur = $this->arsip->getDokumenDur($id_detail)->result();

		echo json_encode($dokumen_dur);
	}

	function getDataLog(){
		$param = $this->input->post('param');
		$id_rec = $param['param'];
		$level_user = $param['level_user'];
		$logData = $this->arsip->getDataLog($id_rec)->result();
		// $logData = $this->arsip->getDataLog(37)->result();

		$idx = 0;
		$result = "";

		// var_dump($logData);
		
		foreach($logData as $ld){
			$getStep = $this->arsip->getStep($ld->id_detail, $ld->id_rec_detail)->result();
			$ld->data_step = $getStep;

			$result .= "<div class='card'>";
			$result .= 		"<div class='card-header' id='heading_" . $idx . "'>";
			
			if($ld->status !== "VALID"){$result .= "<div class='row'>";}
			
			$result .= "<button class='btn btn-link btn-block text-left text-dark text-decoration-none"; //Open Button Tag

			if($ld->status !== "VALID"){$result .= " font-weight-bold col-lg-10";}
			
			$result .= "' type='button' data-bs-toggle='collapse' data-bs-target='#collapse_" . $idx . "' aria-expanded='true' aria-controls='collapse_" . $idx . "'>";
			
			if($ld->status !== "VALID"){
				$result .= 	"<div class='row mx-2 bg'>";
				$result .= 		"<div class='col-sm-8 mx-0 p-0 my-1'>";
				$result .= 			"<p class='m-0 p-0'>" . "Periode : " . $ld->tanggal_berlaku . " - " . $ld->tanggal_berakhir . "</p>";
				$result .= 		"</div>";
				$result .= 		"<div class='col-sm-4 mx-0 p-0 my-1'>";
				$result .= 			"<div class='row m-0 p-0'>";
				$result .= 				"<p class='m-0 p-0 col-sm-5'>Step : " . $ld->step_now . "/" . $ld->max_step . "</p>";
				$result .= 				"<div class='info-box-content col-sm-7 mt-1'>";
				$result .= 					"<div class='progress m-0' style='height:18px;'>";
				$result .= 						"<div class='progress-bar progress-bar-striped bg-success progress-bar-animated' style='width:" . floor(($ld->step_now/$ld->max_step)*100) . "%'></div>";
				$result .= 					"</div>";
				$result .= 				"</div>";
				$result .= 			"</div>";
				$result .= 		"</div>";
				$result .= "</div>";
			}else{
				$result .= "Periode : " . $ld->tanggal_berlaku . " - " . $ld->tanggal_berakhir;
			}

			$result .= "</button>"; //Close Button Tag

			if($ld->status !== "VALID"){
				$result .= "<button class='btn btn-outline-success float-right col-lg-2' data-bs-toggle='modal' data-bs-target='#modal_update_step'"; 
				$result .= " onclick='updateModal(";
				$result .= $ld->id_rec_detail;
				// $result .= $ld->id_rec_detail . ", ";
				// $result .= $ld->id_rec . ", ";
				// $result .= "\"" . $ld->tanggal_berlaku . " - " . $ld->tanggal_berakhir . "\", ";
				// $result .= "\"" . $ld->nama_berkas . "\", ";
				// $result .= "\"" . $ld->milik . "\", ";
				// $result .= "\"" . $ld->step_now . "/" . $ld->max_step . "\", ";
				// $result .= "\"" . $ld->catatan_saat_ini . "\", ";
				// $result .= "\"" . floor(($ld->step_now/$ld->max_step)*100) . "%\"";
				$result .= ")'>";
				$result .= "Update";
				$result .= "</button>";
			}
			
			if($ld->status !== "VALID"){$result .= "</div>";}

			$result .= 		"</div>";
			$result .= 		"<div id='collapse_" . $idx . "' class='collapse"; 
			if($ld->status !== "VALID"){$result .= " show";} 
			$result .= 			"' aria-labelledby='heading_" . $idx . "' data-bs-parent='#accordion_log'>";
			$result .= 			"<div class='card-body'>";
			$result .= 				"<div class='table-responsive-sm'>";
			$result .= 					"<table class='table-sm'>";
			$result .= 						"<thead>";
			$result .= 							"<tr class='border-bottom'>";
			$result .= 								"<th width='5%' class ='text-center'>Step</th>";
			$result .= 								"<th width='15%' class ='text-center'>Progress</th>";
			$result .= 								"<th width='15%' class ='text-center'>Tgl Update</th>";
			$result .= 								"<th width='15%' class ='text-center'>Tgl Estimasi</th>";
			$result .= 								"<th width='20%' class ='text-center'>Note</th>";
			$result .= 								"<th width='20%' class ='text-center'>Nominal</th>";
			$result .= 								"<th width='15%' class ='text-center'>Berkas</th>";
			
			if($level_user >= 5){
				$result .= 								"<th width='15%' class ='text-center'>Edit</th>";
			}
			
			$result .= 							"</tr>";
			$result .= 						"</thead>";
			$result .= 						"<tbody>";
												foreach($getStep AS $gs){
													if($ld->status !== "VALID"){
														if($gs->tgl_update !== null){
			$result .= 										"<tr height='50px' class='text-success border-bottom text-center'>";
														}else{
			$result .= 										"<tr height='50px' class='text-muted border-bottom text-center'>";
														}
													}else{
			$result .= 									"<tr height='50px' class='border-bottom'>";
													}
			$result .= 										"<td class='align-middle text-center'>" . $gs->step . "</td>";
			$result .= 										"<td class='align-middle text-center'>" . $gs->prog . "</td>";
			$result .= 										"<td class='align-middle text-center'>" . $gs->tgl_update . "</td>";
														if($gs->tgl_estimasi !== "01 Jan 01" && $gs->tgl_estimasi !== "01 Jan 00"){
			$result .= 										"<td class='align-middle text-center'>" . $gs->tgl_estimasi . "</td>";
														}else{
			$result .= 										"<td class='align-middle text-center'></td>";												
														}
			$result .= 										"<td class='align-middle text-center'>" . $gs->note . "</td>";	
			$result .= 										"<td class='align-middle text-center'>" . $gs->nominal . "</td>";	
														if($gs->berkas == 'y'){
			$result .= 										"<td class='align-middle text-center text-light'><a class='badge badge-info p-2' style='cursor:pointer' onclick='downloadBerkas(".$ld->id_rec.",".$ld->id_rec_detail.",".$gs->id_rec_progress.")'>Unduh Berkas</a></td>";	
														}else{
			$result .= 										"<td></td>";
														}
														if($level_user >= 5){
															if($gs->tgl_update !== null){		
				$result .= 										"<td class='align-middle text-center text-light'><a class='badge badge-warning p-2' style='cursor:pointer' onclick='editProgress(\"".$gs->id_rec_progress."|".$id_rec."\")'><i class='fas fa-pencil-alt'></i></a></td>";
															}else{
				$result .= 										"<td></td>";
															}
														}
			$result .= 									"</tr>";
												}
			$result .= 						"</tbody>";
			$result .= 					"</table>";
			$result .= 				"</div>";
			$result .= 			"</div>";
			$result .= 		"</div>";
			$result .= "</div>";
			$idx++;
		}
		
		$result .= "<input type='number' id='durasi_doku_for_new_row' value=" . $ld->durasi . " hidden>";

		echo json_encode($result);
	}

	function getStep(){
		$id_d = $this->input->post('id_detail');
		$id_rd = $this->input->post('id_rec_detail');
		$getStep = $this->arsip->getStep($id_d, $id_rd)->result();

		echo json_encode($getStep);
	}

	function getDataFilterJudul(){
		// $user = $this->db->get_where('z_master_user',['username' => $this->session->userdata('username')])->row_array();
		$token = $this->session->userdata('token');
		$user = $this->dash->getUserInfo($token)->row_array();
		$department = $user['department'];
		$cost_center = $user['cost_center'];
		$group_user = $user['group_user'];

		$id_judul = $this->input->post('id_judul');
		$ket = $this->input->post('ket');
		$det = $this->input->post('det');
		$start = $this->input->post('periode_start');
		$end = $this->input->post('periode_end');
		$group = $this->input->post('group');
		$rpp = $this->input->post('rpp');
		$hn = $this->input->post('hn');
		// $oe = $this->input->post('oe');
		$oe = false;

		$jumlahData = $this->arsip->dokumenAktif($group_user, $department, $id_judul, $ket, $det, $start, $end, $group, $cost_center, 0, 0, $oe)->num_rows();

		$jumlahPaging = ceil($jumlahData / $rpp);
		$halaman = $hn;

		$result = $this->arsip->dokumenAktif($group_user, $department, $id_judul, $ket, $det, $start, $end, $group, $cost_center, $rpp, $hn, $oe)->result();

		$result_pass = array();

		array_push($result_pass, $result);
		array_push($result_pass, $jumlahPaging);
		array_push($result_pass, $halaman);
		array_push($result_pass, $jumlahData);
		array_push($result_pass, $group_user);

		echo json_encode($result_pass);
	}

	function test(){
		// var_dump(date('Y-m-d H:i:s'));

		for($i = 0; $i < 3; $i++){

		}

		$pesan = "Test Data";
		$no = "082228909916,081252888774";

		$this->sendWA($no, $pesan);

		// echo "a";
		// $folder = "assets/arsip_data/document/ir_65";
		// $folder = "assets/arsip_data/document/ir_65";
		// rmdir($folder);
		
		// if (!file_exists($folder)) {
		// 	mkdir($folder);
		// }

		// $folder = "assets/arsip_data/document/ir_65/ird_78/ip_11/*.*";
		// $folder = "assets/arsip_data/document/ir_65/ird_78/ip_12/*.*";

		// $filenames = glob($folder);

		// foreach ($filenames as $filename) {
		// 	// $this->zip->read_file($filename);
		// 	unlink($filename);
		// }
		
		// $this->zip->download('Berkas.zip');

		// $id_rec = 60;
		// $id_rec_detail = 73;
		// $id_prog = 10;
		
		// $folder = "assets/arsip_data/document/ir_" . $id_rec . "/";
		
		// if (!file_exists($folder)) {
		// 	mkdir($folder, 0755);
		// }
		
		// $folder = "assets/arsip_data/document/ir_" . $id_rec . "/ird_" . $id_rec_detail . "/";
		
		// if (!file_exists($folder)) {
		// 	mkdir($folder, 0755);
		// }
		
		// $folder = "assets/arsip_data/document/ir_" . $id_rec . "/ird_" . $id_rec_detail . "/ip_" . $id_prog . "/";
		
		// if (!file_exists($folder)) {
		// 	mkdir($folder, 0755);
		// }
	}

	function uploadFile(){
		$id_rec = $this->input->post('id_rec');
		$id_rec_detail = $this->input->post('id_rec_detail');
		$id_prog = $this->input->post('id_prog');

		// $id_rec = 1;
		// $id_rec_detail = 2;
		// $id_prog = 3;
		
		$folder = "assets/arsip_data/document/ir_" . $id_rec . "/";
		
		if (!file_exists($folder)) {
			mkdir($folder);
		}
		
		$folder = "assets/arsip_data/document/ir_" . $id_rec . "/ird_" . $id_rec_detail . "/";
		
		if (!file_exists($folder)) {
			mkdir($folder);
		}
		
		$folder = "assets/arsip_data/document/ir_" . $id_rec . "/ird_" . $id_rec_detail . "/ip_" . $id_prog . "/";
		
		if (!file_exists($folder)) {
			mkdir($folder);
		}
		
		for($f=0; $f<count($_FILES["upload_file"]["tmp_name"]); $f++ ){
			$move = move_uploaded_file($_FILES["upload_file"]["tmp_name"][$f], $folder . $_FILES["upload_file"]["name"][$f]);
		}
		
		if($move){
			echo true;
		}else{
			echo false;
		}
	}

	function downloadFile(){

		// // File path
		// $filepath1 = FCPATH.'/uploads/image1.jpg';
		// $filepath2 = FCPATH.'/uploads/document/users.csv';
	   
		// // Add file
		// $this->zip->read_file($filepath1);
		// $this->zip->read_file($filepath2);

		// // Download
		// $filename = "backup.zip";
		// $this->zip->download($filename);

		$folder = "assets/arsip_data/document/ir_6/ird_6/ip_5/files-1";
		$this->zip->read_file($folder);

		// $this->zip->archive('assets/arsip_data/document/ir_6/ird_6/ip_5.zip');

		$this->zip->download('ip_5.zip');
	}

	function insertRecDoku(){
		$param = $this->input->post('param');

		$result_id = $this->arsip->insertRecDoku($param);
		$result = true;

		if(count($param) > 10){
			if(count($param[10]) > 0){
				for($i = 0; $i < count($param[10]); $i++){
					$result = $this->arsip->insertRecContact($result_id, $param[10][$i]);
				}
			}
		}

		echo json_encode($result);
	}

	function updateRecDoku(){
		$param = $this->input->post('param');

		$result = $this->arsip->updateRecDoku($param);
		$result_det = $this->arsip->updateRecDokuDet($param);
		
		$listContactRow = $this->arsip->infoContact($param[5])->num_rows();
		$listContact = $this->arsip->infoContact($param[5])->result();

		$flag_same = true;
		$resultContact = false;

		if(count($param[9]) == $listContactRow){
			foreach($listContact as $lc){
				$is_it_there = array_search($lc->id_user, $param[9]);
				
				if($is_it_there === false){
					$flag_same = false;
				}
			}
		}else{
			$flag_same = false;
		}

		if($flag_same === false){
			$delContact = $this->arsip->deleteContact($param[5]);

			for($i = 0; $i < count($param[9]); $i++){
				$result_insert = $this->arsip->insertRecContact($param[5], $param[9][$i]);
			}
		}

		if($result > 0 || $result_det > 0 || $flag_same === false){
			echo json_encode(true);
		}else{
			echo json_encode(false);
		}

	}

	function renderData(){
		// $level = $this->input->post('level');
		// $user = $this->db->get_where('z_master_user',['username' => $this->session->userdata('username')])->row_array();
		$token = $this->session->userdata('token');
		$user = $this->dash->getUserInfo($token)->row_array();
		$department = $user['department'];
		$cost_center = $user['cost_center'];
		$group_user = $user['group_user'];

		$id_judul = '0';
		$ket = "";
		$det = "";
		$start = "";
		$end = "";
		$group = "";

		$jumlahData = $this->arsip->dokumenAktif($group_user, $department, $id_judul, $ket, $det, $start, $end, $group, $cost_center, 0, 0, false)->num_rows();
		
		$rpp = 5;
		$page = 1;

		$jumlahPaging = ceil($jumlahData / $rpp);
		$halaman = $page;

		$result = $this->arsip->dokumenAktif($group_user, $department, $id_judul, $ket, $det, $start, $end, $group, $cost_center, $rpp, $page, false)->result();

		$result_pass = array();

		array_push($result_pass, $result);
		array_push($result_pass, $jumlahPaging);
		array_push($result_pass, $halaman);
		array_push($result_pass, $jumlahData);
		array_push($result_pass, $group_user);

		echo json_encode($result_pass);
	}

	function infoDoku(){
		$id_rec = $this->input->post('param');
		// $id_rec = 276;

		$result = array();
		$result_contact_pass = array();

		$result_doku = $this->arsip->infoDoku($id_rec)->result();
		$result_contact = $this->arsip->infoContact($id_rec)->result();
		
		
		// $user = $this->db->get_where('master_user',['username' => $this->session->userdata('username')])->row_array();
		// $department = $user['department'];
		// $user_2 = $this->db->get_where('z_master_user',['username' => $this->session->userdata('username')])->row_array();
		$token = $this->session->userdata('token');
		$user = $this->dash->getUserInfo($token)->row_array();
		$cost_center = $user['cost_center'];
		// var_dump($result_doku);
		// $cost_center = $user['cost_center'];
		
		// var_dump($result_doku[0]->department);
		// echo "<br>";
		// var_dump($department);
		// echo "<br>";
		// echo "<br>";
		// echo "<br>";
		// var_dump($cost_center);
		// var_dump($result_doku[0]->id_cost_center);

		// $result_sh = $this->arsip->getStakeHolder($department, $result_doku[0]->department)->result();
		$result_sh_2 = $this->arsip->getStakeHolder2($cost_center, $result_doku[0]->id_cost_center)->result();

		// var_dump($result_sh_2);

		foreach($result_contact as $rc){
			array_push($result_contact_pass,$rc->id_user_new);
		}

		array_push($result, $result_doku);
		array_push($result, $result_contact_pass);
		// array_push($result, $result_sh);
		array_push($result, $result_sh_2);

		echo json_encode($result);
	}

	function detailLog(){
		$id_rd = $this->input->post('param');

		$result = $this->arsip->detailLog($id_rd)->result();

		echo json_encode($result);
	}

	function insertProg(){
		$param = $this->input->post('param');
		
		$id_rd = $param[0];
		$seq = $param[1];

		if($param[2] == ""){
			$est = '2001-01-01';
		}else{
			$est = $param[2];
		}

		$pay = $param[3];
		$no_doku = $param[4];
		$note = $param[5];
		$id_user = $param[6];
		$berkas = $param[7];

		$status_insert = $param[8];

		$getIdProg = $this->arsip->getIdProg($id_rd, $seq)->result();

		$id_prog = $getIdProg[0]->id_progress;
		$id_rec = $getIdProg[0]->id_rec;

		$result = $this->arsip->insertRecProg($id_rd, $id_prog, $est, $pay, $no_doku, $note, $id_user, $berkas);

		if($status_insert === "now"){
			if($seq == 1){
				$result_update = $this->arsip->changeStatus($id_rd, 2);
			}
		}

		if(count($param) > 9){
			$result_done = $this->arsip->changeStatus($id_rd, 1);
		}

		$arr_result = array();

		array_push($arr_result, $id_rec);
		array_push($arr_result, $id_prog);
		array_push($arr_result, $result);

		// var_dump($arr_result);

		echo json_encode($arr_result);
	}

	function nonAktifProg(){
		$param = $this->input->post('param');
		
		$id_prog = $param['id_rp'];
		$tgl_create = $param['tgl_crt'];

		$result = $this->arsip->nonaktifProg($id_prog, $tgl_create);

		echo json_encode($tgl_create);
	}
	
	function nonaktifDoku(){
		$param = $this->input->post('param');
		
		$id_doku = $param[0];

		// $pesan = "Dokumen " . $param[1] . ", " . $param[2] . " Dengan Detail Dokumen: " . $param[3] . " Telah Di *Nonaktifkan*";
		// $no = "6281252888774";

		// $this->sendWA($no, $pesan);

		$result = $this->arsip->nonaktifDoku($id_doku);

		echo json_encode($result);
	}

	function getIdRec(){
		$id_rd = $this->input->post('param');

		$result = $this->arsip->getIdRec($id_rd)->result();

		echo json_encode($result[0]->id_rec);
	}

	function getIdProg(){
		$param = $this->input->post('param');
		$id_rd = $param[0];
		$seq = $param[1];

		$getIdProg = $this->arsip->getIdProg($id_rd, $seq)->result();

		$id_prog = $getIdProg[0]->id_progress;
		$id_rec = $getIdProg[0]->id_rec;
		$batas_bawah = $getIdProg[0]->tanggal_jatuh_tempo;
		$durasi = $getIdProg[0]->durasi;

		$arr_result = array();

		array_push($arr_result, $id_rec);
		array_push($arr_result, $id_prog);
		array_push($arr_result, $batas_bawah);
		array_push($arr_result, $durasi);

		echo json_encode($arr_result);
	}

	function perpanjanganDokumen(){
		$param = $this->input->post('param');
		$id_rd = $param[0];
		$id_rec = $param[1];
		$id_prog = $param[2];
		$seq = $param[3];
		$est = $param[4];
		$pay = $param[5];
		$no_doku = $param[6];
		$note = $param[7];
		$id_user = $param[8];
		$berkas = $param[9];
		$tanggal_start = $param[10];
		$tanggal_end = $param[11];
		$rdur = $param[12];
		$status_input = $param[13];

		$result = $this->arsip->insertRecProg($id_rd, $id_prog, $est, $pay, $no_doku, $note, $id_user, $berkas);

		if($status_input === 'now'){
			$result_update = $this->arsip->changeStatus($id_rd, 1);
			$result_end = $this->arsip->insertRecDetNew($id_rec, $rdur, $tanggal_start, $tanggal_end);
		}else{
			
		}

		echo json_encode($result);
	}

	function sendWA($nomor, $message){
		$curl = curl_init();

		curl_setopt_array($curl, array(
		CURLOPT_URL => 'https://api.fonnte.com/send',
		CURLOPT_RETURNTRANSFER => true,
		CURLOPT_ENCODING => '',
		CURLOPT_MAXREDIRS => 10,
		CURLOPT_TIMEOUT => 0,
		CURLOPT_FOLLOWLOCATION => true,
		CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
		CURLOPT_CUSTOMREQUEST => 'POST',
		CURLOPT_POSTFIELDS => array(
		'target' => $nomor,
		'message' => $message,
		'delay' => '600',
		'countryCode' => '62', //optional
		),
		CURLOPT_HTTPHEADER => array(
			'Authorization: Li46RvGdGy+3e+7bUTJ9' //change TOKEN to your actual token
		),
		));

		$response = curl_exec($curl);

		curl_close($curl);
		// echo $response;
	}

	function downloadBerkas(){
		$id_rec = $this->input->get('id_rec');
		$id_rec_detail = $this->input->get('id_rec_detail');
		$id_progress = $this->input->get('id_progress');

		$folder = "assets/arsip_data/document/ir_" . $id_rec . "/ird_" . $id_rec_detail . "/ip_" . $id_progress . "/*.*";

		$filenames = glob($folder);

		foreach ($filenames as $filename) {
			$this->zip->read_file($filename);
		}
		
		// $nama_berkas = 'ip_' . $id_progress . '.zip';
		
		$this->zip->download('Berkas.zip');
	}

	function getDataGroup(){
		// $param = $this->input->post('param');
		// $user = $this->db->get_where('z_master_user',['username' => $this->session->userdata('username')])->row_array();
		
		$token = $this->session->userdata('token');
        $user = $this->dash->getUserInfo($token)->row_array();
		
		$department = $user['department'];
		$cost_center = $user['cost_center'];

		$fg = $this->arsip->filterGroup($department, $cost_center)->result();

		$result = array();

		foreach($fg as $fg){
			array_push($result, $fg->group_doku);
		}

		echo json_encode($result);
	}

	function getStakeholder(){
		// $user = $this->db->get_where('z_master_user',['username' => $this->session->userdata('username')])->row_array();
		
		$token = $this->session->userdata('token');
        $user = $this->dash->getUserInfo($token)->row_array();
		$department = $user['cost_center'];

		$dept_chosen = $this->input->post('param');
		$dept_break = explode("|",$dept_chosen);
		$cost_center = $dept_break[0];

		// $result = $this->arsip->getStakeHolder($department, $dept_chosen)->result();
		$result = $this->arsip->getStakeHolder2($department, $cost_center)->result();

		echo json_encode($result);
	}

	function dataClosed(){
		$id_prog_ed = $this->input->post('param');

		$result = $this->arsip->getDataProgClosed($id_prog_ed)->result();

		echo json_encode($result);
	}

	// function nonaktifProg(){
	// 	$id_prog_ed = $this->input->post('param');

	// 	$result = $this->arsip->nonaktifProg($id_prog_ed);

	// 	echo json_encode($result);
	// }

	function sendMail(){
		$config['smtp_port'] = '587';
		$config['smtp_host'] = 'smtp.gmail.com';

		// $this->email->from('no-reply@cpi.com', 'CPI JATIM');
		$this->email->to('muhihsanmuhsin@gmail.com');
		// $this->email->cc('another@another-example.com');
		// $this->email->bcc('them@their-example.com');

		$this->email->subject('Email Test');
		$this->email->message('Testing the email class.');

		$this->email->send();
	}
}
