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
		$this->load->library('image_lib');

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

		$iduser     = $user['id_user'];
		$department = $user['department'];
		$group_user = $user['group_user'];
		$id_menu = "11";

        $data['user'] = $user;
		// $data['group_user'] = $this->db->get_where('master_alias',['ori' => $this->session->userdata('group_user')])->row_array();
		$data['group_user'] = $group_user;
		$data['department'] = $department;
		$data['iduser'] = $iduser;
		$data['title'] = "CP - APPS | DOC Tracking";
		$data["js"] = "trackingDoc";

		if($group_user == 'doc_admin' || $group_user == 'admin_tis' || $group_user == 'spv_tis'){
			$data['allPT'] = $this->doc->getAllPT()->result();
		}else{
			$data['allPT'] = $this->doc->getListPTFarm($iduser,'search',$group_user,'0')->result();
		}

		$this->load->view('templates/dash_h',$data);
		$this->load->view('page_view/trackingDoc',$data);
		$this->load->view('templates/dash_f',$data);
	}

	function cleancatatan($string) {
		return preg_replace('/[^A-Za-z0-9\-]/', ' ', $string); // Removes special chars.
	}

	function getFarm(){
		$idPT = $this->input->post('param');

		$farm = $this->doc->getAllFarm($idPT)->result();

		echo json_encode($farm);
	}

	function getFarmList(){
		$data = $this->input->post('param');
		
		// $data = array(
		// 	"iduser"=> "51",
		// 	"type"=> "view",
		// 	"group_user"=> "koor_tis",
		// 	"idpt"=> "6"
		// );

		$iduser 	= $data['iduser'];
		$type 		= $data['type'];
		$group_user = $data['group_user'];
		$idpt 		= $data['idpt'];

		$list = $this->doc->getListPTFarm($iduser,$type,$group_user,$idpt)->result();

		$farm = $this->doc->getListFarm($list[0]->idf)->result();

		echo json_encode($farm);
	}
	
	function getDetailFarm(){
		$idfarm = $this->input->post('param');

		$farm = $this->doc->getIDFarm($idfarm)->result();

		echo json_encode($farm);
	}

	function getPT(){
		$idfarm = $this->input->post('param');
		
		$id_pt = $this->doc->getIDPT($idfarm)->result();

		echo json_encode($id_pt);
	}

	function getKompetitor(){
		$zero = $this->input->post('param');
		
		$idkomp = $this->doc->getIDKomp($zero)->result();

		echo json_encode($idkomp);
	}

	function getInfoContact(){
		$idfarm = $this->input->post('param');
		
		$contact = $this->doc->getInfoCont($idfarm)->result();

		echo json_encode($contact);
	}
	
	function getInfoQuiz(){
		$codehouse = $this->input->post('param');
		
		$listquiz = $this->doc->getAllQuiz($codehouse)->result();
		$listpert = $this->doc->getPerQuiz($codehouse)->result();
		$jmlhpert = $this->doc->getPerTotl($codehouse)->result();

		$result = array(
			"list_pertanyaan" => $listpert,
			"list_quiz" => $listquiz,
			"jumlah_pertanyaan" => $jmlhpert
		);

		echo json_encode($result);
	}

	function insertCI(){
		$data = $this->input->post('param');

		$total_ci = $data['total_ci'];
		$date_ci = $data['tanggal_ci'];
		$komp_ci = $data['komp_ci'];
		$id_farm = $data['id_farm'];
		$last_day_ci = "true";

		// date_default_timezone_set('Asia/Jakarta');
		// $todaydt = date('Y-m-d');

		$result = '';
		
		if($data['status_farm'] != '1'){
			$status_now = 1;

			$remainder = $this->doc->getTypeRemain($id_farm)->result();

			$typerem = $remainder[0]->type_remain;
			$spcweek = $remainder[0]->spc_day;

			$insertspc = '';
			if($typerem == 'spesial'){
				$insertspc = $spcweek;
			}

			$id_prog = $this->doc->insertProg($id_farm, $date_ci, $status_now, $insertspc);
			$insert_ci = $this->doc->insertCI($id_prog, $total_ci, $date_ci, $komp_ci);
			// $chkwatoday = $this->doc->getTodayIsWA($id_farm, $id_prog, $todaydt);

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
				$insert_ci = $this->doc->insertCI($id_prog, $total_ci, $date_ci, $komp_ci);

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

	function insertNEWCI(){
		$data = $this->input->post('param');

		// $data = [
		// 	"total_ci"=> "377",
		// 	"tanggal_ci"=> "2024-07-22",
		// 	"status_farm"=> "5",
		// 	"id_farm"=> "86",
		// 	"id_progress"=> "120",
		// 	"id_pt"=> "32",
		// 	"doc_next"=> "CPJF",
		// 	"namafarm"=> "SIAGA 2",
		// 	"id_user"=> "46"
		// ];

		$total_ci = $data['total_ci'];
		$date_ci = $data['tanggal_ci'];
		$id_farm = $data['id_farm'];
		$status_farm = $data['status_farm'];
		$id_progress = $data['id_progress'];
		$id_pt = $data['id_pt'];
		$doc_next = $data['doc_next'];
		$namafarm = $data['namafarm'];
		$userid = $data['id_user'];

		$status_old = 6;
		$status_new = 2;
		$active = 'n';

		$fixmarketProg = $this->doc->fixmarketFarmProg($id_progress, $date_ci);
		$updateProgOld = $this->doc->updateFarmProg($id_progress, $status_old);
		$activeProgOld = $this->doc->activeFarmProg($id_progress, $date_ci, $active);

		$remainder = $this->doc->getTypeRemain($id_farm)->result();

		$typerem = $remainder[0]->type_remain;
		$spcweek = $remainder[0]->spc_day;

		$insertspc = '';
		if($typerem == 'spesial'){
			$insertspc = $spcweek;
		}

		$id_prog_new = $this->doc->insertProg($id_farm, $date_ci, $status_new, $insertspc);
		$insert_ci = $this->doc->insertCI($id_prog_new, $total_ci, $date_ci ,$doc_next);

		$nama_kompetitor = $this->doc->getNamaKompetitor($doc_next)->result();
		$nama_kompetitor = $nama_kompetitor[0]->nama;

		$datakontak = $this->doc->getContact($id_farm)->result();
		$date_ci = strtotime($date_ci);
		$date_ci = date('d F Y',$date_ci);
		$date_nw = strtotime('now');
		$date_nw = date('d F Y',$date_nw);

		$id_ptx 	= $datakontak[0]->id_pt; 
		// $no_spvtis 	= $datakontak[0]->no_spvtis; 
		// $no_koortis = $datakontak[0]->no_koortis; 
		// $no_usrtis 	= $datakontak[0]->no_usrtis; 
		$no_spvdoc	= $datakontak[0]->no_spvdoc; 
		$no_dev	= $datakontak[0]->dev; 
		// $no_usrdoc	= $datakontak[0]->no_usrdoc; 

		$fixwa = NULL;

if($datakontak[0]->no_spvdoc){
$pesan_spvdoc = "*Survey DOC Remainder :*
					
Bahwa pada Hari Ini *" . $date_nw . "*,
Tim DOC telah melakukan input Chickin di _Farm Customer_ 
" . $namafarm . ", dengan informasi sebagai berikut:
DOC Dari : ". $nama_kompetitor ."
Tanggal Chickin : *" . $date_ci . "* 
Sebanyak : ".$total_ci." BOX

_Pesan ini adalah Pesan Otomatis Dari Sistem CPI Jatim_";
// *SPV TIS*
$fixwa = $this->wa($no_spvdoc, $pesan_spvdoc); // AKTIFKAN KEMBALI
if(strpos($fixwa, "success") !== false){ 
	$insertdwa = $this->doc->insertDWA($id_progress, 0, 'Next CI', $datakontak[0]->no_spvdoc.$pesan_spvdoc, $userid);
}
$fixwa = $this->wa($no_dev, $pesan_spvdoc."\n\nFOR DEV");
	if(strpos($fixwa, "success") !== false){ 
		$insertdwa = $this->doc->insertDWA($id_progress, 0, 'Next CI', $datakontak[0]->dev.$pesan_spvdoc."\n\nFOR DEV", $userid);
	}
}

		echo json_encode($id_pt);
	}

	function insertTIS(){
		$data = $this->input->post('param');

		// $data = array (
		// 	"id_farm_tis" => "59",
		// 	"id_progress_tis" => "118",
		// 	"status_farm_tis" => "2",
		// 	"tanggal_tis" => "2024-06-26",
		// 	"estimate_tis" => "1111/01/01",
		// 	"catatan_tis" => "ertyuwad",
		// 	"kosong_tis" => "false",
		// 	"user_id" => "48",
		// 	"user_dp" => "TIS",
		// 	"nilai_kandang" => 0,
		// 	"total_ci" => 0,
		// 	"doc_next" => 0,
		// 	"estimatekos_tis" => "2024-06-28",
		// 	"foto_name" => "TIS_20240626_133239_59_118.png"
		// );

		$id_farm	 = $data['id_farm_tis'];
        $id_progress = $data['id_progress_tis'];
        $status_farm = $data['status_farm_tis'];
        $tgl_survey  = $data['tanggal_tis'];
        $tgl_extima  = $data['estimate_tis'];
        $catatan 	 = $this->cleancatatan($data['catatan_tis']);
        $kosong 	 = $data['kosong_tis'];
        $userid 	 = $data['user_id'];
        $userdp 	 = $data['user_dp'];
        $foto_name	 = $data['foto_name'];
        $nilai_kandang	 = $data['nilai_kandang'];
		
        $tgl_extimak = $data['estimatekos_tis'];
		$result = '';

		$fotopath = "assets/tracking_doc/foto_progress/if_" . $id_farm . "/ip_" . $id_progress . "/" . $foto_name;
		$tanggalSurv = $this->doc->cekDateTis($tgl_survey)->result();
		$tanggalEsti = $this->doc->cekDateEst($tgl_extima)->result();

		// PENGAMBIL KONTAK PER FARM
		$datakontak = $this->doc->getContact($id_farm)->result();

		if(!$datakontak){
			$status_valid_wa = "wanull///wanull";
		}else{
			$status_valid_wa = 'Aman';
		}
		// $result = $datakontak;
		
		if($status_valid_wa == 'Aman'){
		// if($tanggalSurv[0]->status_valid_ds == 'Aman' && $tanggalEsti[0]->status_valid_de == 'Aman' && $status_valid_wa == 'Aman'){
			// $insert_tis = $this->doc->insertTIS($userid, $id_progress, $kosong, $tgl_extima, $tgl_survey, $catatan, $fotopath, $userdp, $id_farm, '', $nilai_kandang);
			$insert_tis = $this->doc->insertTIS($userid, $id_progress, $kosong, $tgl_extima, $tgl_survey, $catatan, $fotopath, $userdp, $id_farm, '', $nilai_kandang, $tgl_extimak);

			$tglSvy = $tanggalSurv[0]->status_valid_ds;
			$tglEst = $tanggalEsti[0]->status_valid_de;

			if($kosong == "true"){
				$update_status  = $this->doc->updateFarmProg($id_progress, 4);
				$update_harvest = $this->doc->harvestFarmProg($id_progress, $tgl_extima);

				$tgl_survey = strtotime($tgl_survey);
				$tgl_survey = date('d F Y',$tgl_survey);

				// TAMBAH TAMPILAN NAMA FARM UNTUK WA LANGSUNG JIKA SUDAH KOSONG
				$namafarm = $this->doc->getNMFarm($id_farm)->result();

				// PENGAMBIL KONTAK PER FARM
				$datakontak = $this->doc->getContact($id_farm)->result();

				$id_pt 		= $datakontak[0]->id_pt; 
				$no_spvtis 	= $datakontak[0]->no_spvtis; 
				$no_koortis	= $datakontak[0]->no_koortis; 
				$no_usrtis 	= $datakontak[0]->no_usrtis; 
				$no_spvdoc	= $datakontak[0]->no_spvdoc; 
				$no_usrdoc	= $datakontak[0]->no_usrdoc; 
				$no_dev		= $datakontak[0]->dev; 
				$id_log = $insert_tis;

				$fixwa = NULL;

				if($datakontak[0]->no_spvtis){
					$kontak = $datakontak[0]->no_spvtis;
					$pesan = "*Survey DOC Remainder :* \n\nBahwa pada Tanggal *" . $tgl_survey . "*, \nTim TIS telah melakukan Survey di _Farm Customer_ \n" . $namafarm[0]->nama_farm . ",\ndengan Tanggal Estimasi DOC Chickin berikutnya *" . $tgl_extima . "* \nCatatan Survey : \n".$catatan." \n \n_Pesan ini adalah Pesan Otomatis Dari Sistem CPI Jatim_";
					// $fixwa = $this->wa($no_spvtis, $pesan);
					$fixwa = $this->doc->insertWAMQ($pesan,$kontak);	// AKTIFKAN KEMBALI
						if(strpos($fixwa, "success") !== false){ 
							$insertdwa = $this->doc->insertDWA($id_progress, $id_log, 'Kosong', $datakontak[0]->no_spvtis."\n".$pesan, $userid);
						}
				}

				if($datakontak[0]->no_koortis){
					$kontak = $datakontak[0]->no_koortis;
					$pesan = "*Survey DOC Remainder :* \n\nBahwa pada Tanggal *" . $tgl_survey . "*, \nTim TIS telah melakukan Survey di _Farm Customer_ \n" . $namafarm[0]->nama_farm . ",\ndengan Tanggal Estimasi DOC Chickin berikutnya *" . $tgl_extima . "* \nCatatan Survey : \n".$catatan." \n \n_Pesan ini adalah Pesan Otomatis Dari Sistem CPI Jatim_";
					// $fixwa = $this->wa($no_spvtis, $pesan);
					$fixwa = $this->doc->insertWAMQ($pesan,$kontak); // AKTIFKAN KEMBALI
						if(strpos($fixwa, "success") !== false){ 
							$insertdwa = $this->doc->insertDWA($id_progress, $id_log, 'Kosong', $datakontak[0]->no_koortis."\n".$pesan, $userid);
						}
				}

				if($datakontak[0]->no_usrtis){
					$kontak = $datakontak[0]->no_usrtis;
					$pesan = "*Survey DOC Remainder :* \n\nBahwa pada Tanggal *" . $tgl_survey . "*, \nTim TIS telah melakukan Survey di _Farm Customer_ \n" . $namafarm[0]->nama_farm . ",\ndengan Tanggal Estimasi DOC Chickin berikutnya *" . $tgl_extima . "* \nCatatan Survey : \n".$catatan." \n \n_Pesan ini adalah Pesan Otomatis Dari Sistem CPI Jatim_";
					// $fixwa = $this->wa($no_usrtis, $pesan);
					$fixwa = $this->doc->insertWAMQ($pesan,$kontak); // AKTIFKAN KEMBALI
						if(strpos($fixwa, "success") !== false){ 
							$insertdwa = $this->doc->insertDWA($id_progress, $id_log, 'Kosong', $datakontak[0]->no_usrtis."\n".$pesan, $userid);
						}
				}

				if($datakontak[0]->no_spvdoc){
					$kontak = $datakontak[0]->no_spvdoc;
					$pesan = "*Survey DOC Remainder :* \n\nBahwa pada Tanggal *" . $tgl_survey . "*, \nTim TIS telah melakukan Survey di _Farm Customer_ \n" . $namafarm[0]->nama_farm . ",\ndengan Tanggal Estimasi DOC Chickin berikutnya *" . $tgl_extima . "* \nCatatan Survey : \n".$catatan." \n \n_Pesan ini adalah Pesan Otomatis Dari Sistem CPI Jatim_";
					// $fixwa = $this->wa($no_spvdoc, $pesan);
					$fixwa = $this->doc->insertWAMQ($pesan,$kontak); // AKTIFKAN KEMBALI
						if(strpos($fixwa, "success") !== false){ 
							$insertdwa = $this->doc->insertDWA($id_progress, $id_log, 'Kosong', $datakontak[0]->no_spvdoc."\n".$pesan, $userid);
						}
				}

				if($datakontak[0]->no_usrdoc){
					$kontak = $datakontak[0]->no_usrdoc;
					$pesan = "*Survey DOC Remainder :* \n\nBahwa pada Tanggal *" . $tgl_survey . "*, \nTim TIS telah melakukan Survey di _Farm Customer_ \n" . $namafarm[0]->nama_farm . ",\ndengan Tanggal Estimasi DOC Chickin berikutnya *" . $tgl_extima . "* \nCatatan Survey : \n".$catatan." \n \n_Pesan ini adalah Pesan Otomatis Dari Sistem CPI Jatim_";
					// $fixwa = $this->wa($no_usrdoc, $pesan);
					$fixwa = $this->doc->insertWAMQ($pesan,$kontak); // AKTIFKAN KEMBALI
						if(strpos($fixwa, "success") !== false){ 
							$insertdwa = $this->doc->insertDWA($id_progress, $id_log, 'Kosong', $datakontak[0]->no_usrdoc."\n".$pesan, $userid);
						}
				}

				if($datakontak[0]->no_dev){
					$kontak = $datakontak[0]->no_dev;
					$pesan = "*Survey DOC Remainder :* \n\nBahwa pada Tanggal *" . $tgl_survey . "*, \nTim TIS telah melakukan Survey di _Farm Customer_ \n" . $namafarm[0]->nama_farm . ",\ndengan Tanggal Estimasi DOC Chickin berikutnya *" . $tgl_extima . "* \nCatatan Survey : \n".$catatan." \n \n_Pesan ini adalah Pesan Otomatis Dari Sistem CPI Jatim_";
					// $fixwa = $this->wa($no_usrdoc, $pesan);
					$fixwa = $this->doc->insertWAMQ($pesan,$kontak); // AKTIFKAN KEMBALI
						if(strpos($fixwa, "success") !== false){ 
							$insertdwa = $this->doc->insertDWA($id_progress, $id_log, 'Kosong', $datakontak[0]->no_dev."\n".$pesan, $userid);
						}
				}

			}
			else{
				$update_resurvey = $this->doc->resurveyFarmProg($id_progress, $tgl_extimak);
				$update_status = $this->doc->updateFarmProg($id_progress, 3);
			}

			if($insert_tis){
				$id_pt = $this->doc->getIDPT($id_farm)->result();
				$id_pt = $id_pt[0]->id_pt;

				$result = array(
					"cek_survey" => $tglSvy,
					"cek_estimate" => $tglEst,
					"cek_nowa" => $status_valid_wa,
					"idpt" => $id_pt
				);

			}else{
				// $result = $insert_tis;
				$result = "ERROR CEK INSERT TIS";
			}

		}
		else{
		// 	$tglSvy = $tanggalSurv[0]->status_valid_ds;
		// 	$tglEst = $tanggalEsti[0]->status_valid_de;

			$result = array(
				// "cek_survey" => $tglSvy,
				// "cek_estimate" => $tglEst,
				"cek_nowa" => $status_valid_wa
			);

		}

		echo json_encode($result);
	}

	function testDA(){
		$date_nw = strtotime('now');
		$date_nw = date('d F Y',$date_nw);
		echo $nama_kompetitor = $this->doc->getNamaKompetitor('CPI')->result;
		echo $date_nw;
	}

	function insertFIX(){
		$data = $this->input->post('param');

		// $data = array (
		// 	"progressfix"=> "Kandang Belum Siap",
		// 	"total_fix"=> 0,
		// 	"tanggal_survey"=> "2024-06-28",
		// 	"tanggal_cinext"=> "1111/01/01",
		// 	"status_farm"=> "5",
		// 	"id_farm"=> "59",
		// 	"id_progress"=> "118",
		// 	"catatan_fix"=> "98a3h98 aw9j uisefh jsefh s0ef spef sef sef sef psef sef 990 7s90 yw3ru seiof psef se",
		// 	"user_id"=> "46",
		// 	"user_dp"=> "MARKETING_DOC",
		// 	"file_fix_lenght"=> 0,
		// 	"foto_name"=> "",
		// 	"kemungkinan"=> 0,
		// 	"doc_next"=> ""
		// );

		$id_farm	 = $data['id_farm'];
        $id_progress = $data['id_progress'];
        $status_farm = $data['status_farm'];
        $total_fix	 = $data['total_fix'];
        $tgl_survey  = $data['tanggal_survey'];
        $tgl_cinext  = $data['tanggal_cinext'];
        $progressfix = $data['progressfix'];
        $catatan 	 = $this->cleancatatan($data['catatan_fix']);
        $cekfotoisi	 = $data['file_fix_lenght'];
        $foto_name 	 = $data['foto_name'];
        $kemungkinan = $data['kemungkinan'];
        $doc_next 	 = $data['doc_next'];
        $userdp 	 = $data['user_dp'];
        $userid 	 = $data['user_id'];

		$result = '';

		// if($progressfix == "Sudah Deal dengan CPI" || $progressfix == "Telah Deal dengan Kompetitor"){
		if($progressfix == "Sudah Deal dengan CPJF"){
			$kosong = "fixye";
		}else{
			$kosong = "fixno";
		}
		
		if($cekfotoisi == 0){
			$fotopath = "";
		}else{
			$fotopath = "assets/tracking_doc/foto_progress/if_" . $id_farm . "/ip_" . $id_progress . "/" . $foto_name;
		}

		$insert_fixform = $this->doc->insertFIX($userid, $id_progress, $kosong, $tgl_cinext, $tgl_survey, $catatan, $fotopath, $userdp, $id_farm, $progressfix, $total_fix, $kemungkinan, $doc_next);

		$update_status = $this->doc->updateFarmProg($id_progress, 5);

		$result = $insert_fixform;
		
		// $update_status = $this->doc->updateFarmProg($id_progress, 6); // 6 INI FIX CHICKIN BERIKUTNYA TAPI BELUM RILIS

		echo json_encode($result);
	}

	function getInfoCI(){
		$data = $this->input->post('param');

		$remainder = $this->doc->getTypeRemain($data['idfarm'])->result();

		$typerem = $remainder[0]->type_remain;
		$spcweek = $remainder[0]->spc_day;

		if($typerem == 'spesial'){
			$infoLogCI = $this->doc->infoCILogSpc($data['idprogress'],$spcweek)->result();
		}else{
			$infoLogCI = $this->doc->infoCILog($data['idprogress'])->result();
		}

		$infoDateCI = $this->doc->infoDateCI($data['idprogress'])->result();
		$infoLogPT = $this->doc->getIDPT($data['idfarm'])->result();

		$result = array(
			"info_date_ci" => $infoDateCI,
			"info_log_ci" => $infoLogCI,
			"info_log_pt" => $infoLogPT
		);

		echo json_encode($result);
	}

	function getInfoTIS(){
		$id_prog = $this->input->post('param');

		$result = $this->doc->getAllDLog($id_prog)->result();

		echo json_encode($result);
	}

	function getGrade(){
		$id_farm = $this->input->post('param');

		$result = $this->doc->getGradeTIS($id_farm)->result();

		echo json_encode($result);
	}

	function getListGrade(){
		$id_farm = $this->input->post('param');

		$result = $this->doc->getListGradeTIS($id_farm)->result();

		echo json_encode($result);
	}

	// function getInfoDOC(){
	// 	$id_prog = $this->input->post('param');

	// 	$infoDateCI = $this->doc->infoDateCI($id_prog)->result();
	// 	$infoLogCI = $this->doc->infoCILog($id_prog)->result();

	// 	$result = array(
	// 		"info_date_ci" => $infoDateCI,
	// 		"info_log_ci" => $infoLogCI
	// 	);

	// 	echo json_encode($result);
	// }

	function uploadFileSurveyTIS(){
		// $id_survey = html_escape($this->input->post("id_survey"));
		$id_farm = html_escape($this->input->post("id_farm"));
		$tglsurv = html_escape($this->input->post("tglsurv"));
		$jmlpert = html_escape($this->input->post("jmlpert"));
		
		$of_survey = $this->doc->offLastDSurvey($id_farm);
		$id_survey = $this->doc->insertDSurvey($id_farm,$tglsurv);

		$folder = "assets/tracking_doc/foto_survey/srv_".$id_survey."/";

		$path_all = array();
		
		if (!file_exists($folder)) {
			mkdir($folder);
		}

		for($i = 0; $i < 6; $i++){
			${"gambar" . $i} = $_FILES["gambar".$i]["tmp_name"][0];
			${"gambar" . $i . "_tipe"} = $_FILES["gambar".$i]["type"][0];
			$file_type = explode("/", ${"gambar" . $i . "_tipe"});
			$target_path = $folder . "gambar" . ($i+1) . "." . $file_type[1];

			array_push($path_all, $target_path);

			$fupload = move_uploaded_file(${"gambar" . $i}, $target_path);

			if ($fupload) {
				if ($file_type[1] == 'jpeg'|| $file_type[1] == 'jpg' || $file_type[1] == 'png') {
					# code.
					list($width, $height) = getimagesize($target_path);
					$config2['image_library'] = 'gd2';
					$config2['source_image'] = $target_path; 
					$config2['maintain_ratio'] = true;
					$config2['quality'] = '30%' ; 
					$config2['width'] = $width - 1;
					$config2['height'] = $height - 1;
					$config2['new_image'] = $target_path;
					$this->image_lib->clear();
					$this->image_lib->initialize($config2);
					$this->image_lib->resize();
					$result = "Success";
				}
			}else {
				$result = "False";
			}
		}

		if($result){
			foreach($path_all as $p){
				$insertGambar = $this->doc->insertDetailSurvey($id_survey,"g",$p);
			}
		}

		for($i = 0; $i < $jmlpert; $i++){
			${"jawaban".$i} = html_escape($this->input->post("jawaban".$i));
			$insertJawaban = $this->doc->insertDetailSurvey($id_survey,"j",${"jawaban".$i});
		}

		if($insertJawaban){
			$result = true;
		}

		echo json_encode($result);
	}

	function uploadFile(){
		$id_farm = $this->input->post('id_farm');
		$id_progress = $this->input->post('id_progress');
		$department = $this->input->post('department');
		$namafoto = $this->input->post('namafoto');

		$folder = "assets/tracking_doc/foto_progress/if_" . $id_farm . "/";
		
		if (!file_exists($folder)) {
			mkdir($folder);
		}

		// if (!is_dir($folder))                             
		// {
		//   	mkdir($folder,'0777', true);
		// }
		
		$folder = "assets/tracking_doc/foto_progress/if_" . $id_farm . "/ip_" . $id_progress . "/";
		
		if (!file_exists($folder)) {
			mkdir($folder);
		}

		// if (!is_dir($folder))                             
		// {
		//   	mkdir($folder,'0777', true);
		// }

		$old_img_name = $_FILES["upload_file"]["tmp_name"][0];
		$pass_type = strval($_FILES["upload_file"]["type"][0]);
		$file_type = explode("/", $pass_type); // CEK TYPE FILE

		$target_path = $folder . $namafoto;
		$fupload = move_uploaded_file($old_img_name, $target_path);

		if ($fupload) {
			if ($file_type[1] == 'jpeg'|| $file_type[1] == 'jpg' || $file_type[1] == 'png') {
				# code.
				list($width, $height) = getimagesize($target_path);
				$config2['image_library'] = 'gd2'; // Choose the image library (gd2, imagick, etc.)
				$config2['source_image'] = $target_path; // Path to the original image file
				$config2['maintain_ratio'] = true; // Maintain aspect ratio while resizing
				$config2['quality'] = '30%' ; // Compression quality (0-100)
				$config2['width'] = $width - 1;
				$config2['height'] = $height - 1;
				$config2['new_image'] = $target_path; 
				// $config2['width'] = 800; // Atau lebar yang diinginkan
    			// $config2['height'] = 600; // Atau tinggi yang diinginkan
				// $config2['width'] = 1920; // Compression quality (0-100)
				// $config2['source_image'] = './' . $target_path; // Path to the original image file
				// $config2['new_image'] = './' . $target_path; 
				// var_dump($config2);

				// Initialize the image library with the config2uration settings
				$this->image_lib->clear();
				$this->image_lib->initialize($config2);
				$this->image_lib->resize();
				// $result = "Success"." - ".$folder." = ".$target_path." 00 ".$namafoto . "INI PASS >>>" . $pass_type . "<<< >>>" . $file_type[1];
				$result = "Success";
			}

		}else {
			$result = "False";
		}

		echo json_encode($result);
		
		// if($move){
		// 	echo true;
		// }else{
		// 	echo false;
		// }
	}

	function wa($target, $msg){
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
            'target' => $target,
            'message' => $msg,
            'countryCode' => '62', //optional
            'delay' => '180', //optional
        ),
          CURLOPT_HTTPHEADER => array(
            'Authorization: Li46RvGdGy+3e+7bUTJ9' //change TOKEN to your actual token
          ),
        ));
        
        $response = curl_exec($curl);
        
        curl_close($curl);
        // echo $response;
        return $response;
    }

	function tesdir(){
		$folder = "assets/tracking_doc/foto_progress/if_2/";
		
		if (!file_exists($folder)) {
			mkdir($folder);
			echo "belum ada loh!";
		}else{
			echo "sudah ada loh!";
		}

		$folder = "assets/tracking_doc/foto_progress/if_2/ip_62/";
		
		if (!file_exists($folder)) {
			mkdir($folder);
			echo "belum ada loh ininya!";
		}else{
			echo "sudah ada loh ininya!";
		}

		// if (!is_dir($folder))                             
		// {
		//   	mkdir($folder,'0777', true);
		// 	echo "belum ada loh ininya!";
		// }else{
		// 	echo "sudah ada loh ininya!";
		// }

	}

	function hapus(){
		// $folder = "assets/arsip_data/document/ir_65";
		$folder = "assets/tracking_doc/foto_progress/if_2/";
		// $folder = "assets/tracking_doc/foto_progress/if_2/ip_62/";

		// $folder = "assets/tracking_doc/foto_survey/srv_1";
		rmdir($folder); // BUAT HAPUS FOLDER
		
		//---------------------- // BUAT HAPUS FILE
		// $files = glob($folder . '/*'); // BUAT HAPUS FILE

		// foreach ($files as $file) { // BUAT HAPUS FILE
		// 	if (is_file($file)) {
		// 		unlink($file);
		// 	}
		// }
		//---------------------- // BUAT HAPUS FILE
		
	}

	function testrem(){
		$remainder = $this->doc->getTypeRemain('2')->result();

		// var_dump($remainder);

		echo $typerem = $remainder[0]->type_remain;
		echo $spcweek = $remainder[0]->spc_day;
	}

	function testwa(){
		$id_farm = 2;
		$tgl_survey = "2024-05-22";
		$tgl_extima = "2024-06-28";
		$catatan = "TESTING HARDCRIPT";
		$id_progress = "62";
		$id_log = "999";
		$userid = 48;

		$datakontak = $this->doc->getContact($id_farm)->result();
		$namafarm = $this->doc->getNMFarm($id_farm)->result();

		$id_pt 		= $datakontak[0]->id_pt; 
		$no_mgt 	= $datakontak[0]->no_mgt; 
		$no_spvtis 	= $datakontak[0]->no_spvtis; 
		$no_usrtis 	= $datakontak[0]->no_usrtis; 
		$no_spvdoc	= $datakontak[0]->no_spvdoc; 
		$no_usrdoc	= $datakontak[0]->no_usrdoc; 

		$fixwa = NULL;

		if($datakontak[0]->no_spvtis){
			$kontak = $datakontak[0]->no_spvtis;
			$pesan = "*Survey DOC Remainder :* \n\nBahwa pada Tanggal *" . $tgl_survey . "*, \nTim TIS telah melakukan Survey di _Farm Customer_ \n" . $namafarm[0]->nama_farm . ",\ndengan Tanggal Estimasi DOC Chickin berikutnya *" . $tgl_extima . "* \nCatatan Survey : \n".$catatan." \n*SPV TIS* \n_Pesan ini adalah Pesan Otomatis Dari Sistem CPI Jatim_";
			// $fixwa = $this->wa($no_spvtis, $pesan);
			// $fixwa = $this->doc->insertWAMQ($pesan,$kontak); // AKTIFKAN KEMBALI
			// 	if(strpos($fixwa, "success") !== false){ 
			// 		$insertdwa = $this->doc->insertDWA($id_progress, $id_log, 'Kosong', $datakontak[0]->no_spvtis."\n".$pesan, $userid);
			// 	}
		}

		if($datakontak[0]->no_usrtis){
			$kontak = $datakontak[0]->no_usrtis;
			$pesan = "*Survey DOC Remainder :* \n\nBahwa pada Tanggal *" . $tgl_survey . "*, \nTim TIS telah melakukan Survey di _Farm Customer_ \n" . $namafarm[0]->nama_farm . ",\ndengan Tanggal Estimasi DOC Chickin berikutnya *" . $tgl_extima . "* \nCatatan Survey : \n".$catatan." \n*USER TIS* \n_Pesan ini adalah Pesan Otomatis Dari Sistem CPI Jatim_";
			// $fixwa = $this->wa($no_usrtis, $pesan);
			// $fixwa = $this->doc->insertWAMQ($pesan,$kontak); // AKTIFKAN KEMBALI
			// 	if(strpos($fixwa, "success") !== false){ 
			// 		$insertdwa = $this->doc->insertDWA($id_progress, $id_log, 'Kosong', $datakontak[0]->no_usrtis."\n".$pesan, $userid);
			// 	}
		}

		if($datakontak[0]->no_spvdoc){
			$kontak = $datakontak[0]->no_spvdoc;
			$pesan = "*Survey DOC Remainder :* \n\nBahwa pada Tanggal *" . $tgl_survey . "*, \nTim TIS telah melakukan Survey di _Farm Customer_ \n" . $namafarm[0]->nama_farm . ",\ndengan Tanggal Estimasi DOC Chickin berikutnya *" . $tgl_extima . "* \nCatatan Survey : \n".$catatan." \n*SPV DOC* \n_Pesan ini adalah Pesan Otomatis Dari Sistem CPI Jatim_";
			// $fixwa = $this->wa($no_spvdoc, $pesan);
			// $fixwa = $this->doc->insertWAMQ($pesan,$kontak); // AKTIFKAN KEMBALI
			// 	if(strpos($fixwa, "success") !== false){ 
			// 		$insertdwa = $this->doc->insertDWA($id_progress, $id_log, 'Kosong', $datakontak[0]->no_spvdoc."\n".$pesan, $userid);
			// 	}
		}

		if($datakontak[0]->no_usrdoc){
			$kontak = $datakontak[0]->no_usrdoc;
			$pesan = "*Survey DOC Remainder :* \n\nBahwa pada Tanggal *" . $tgl_survey . "*, \nTim TIS telah melakukan Survey di _Farm Customer_ \n" . $namafarm[0]->nama_farm . ",\ndengan Tanggal Estimasi DOC Chickin berikutnya *" . $tgl_extima . "* \nCatatan Survey : \n".$catatan." \n*USER DOC* \n_Pesan ini adalah Pesan Otomatis Dari Sistem CPI Jatim_";
			// $fixwa = $this->wa($no_usrdoc, $pesan);
			// $fixwa = $this->doc->insertWAMQ($pesan,$kontak); // AKTIFKAN KEMBALI
			// 	if(strpos($fixwa, "success") !== false){ 
			// 		$insertdwa = $this->doc->insertDWA($id_progress, $id_log, 'Kosong', $datakontak[0]->no_usrdoc."\n".$pesan, $userid);
			// 	}
		}

	}

}
