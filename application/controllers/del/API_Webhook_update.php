<?php
defined('BASEPATH') OR exit('No direct script access allowed');

require 'asset/php/dompdf/vendor/autoload.php';

use Dompdf\Dompdf;
use Dompdf\Options;

class API_Webhook_update extends CI_Controller {

	function __construct(){
        parent::__construct();
		$this->load->model('M_Form', 'form');
		$this->load->model('M_API', 'api');
		$this->load->model('M_Attendance', 'matt');
		$this->load->model('M_KecelakaanKerja', 'mkk');

		date_default_timezone_set('Asia/Jakarta');
    }
	
	public function index(){
		header('Content-Type: application/json; charset=utf-8');

		$json = file_get_contents('php://input');
		$data = json_decode($json, true);

		// ================================= Setup Data Config Yang Diperlukan ================================= \\

		//Nomor WA User
		$to = $data["data"]["from_phone_number"];

		// Data Pesan User
		$message = $data["data"]["message"]; 
		
		// Id Button yang Dipilih User
		$action_id = $data["data"]["action_id"]; 
		
		// Data File Jika ada Upload File / Picture
		if(isset($data["data"]["file"])){
			$file = $data["data"]["file"]; 
		}

		// Merubah Format Nomor HP Agar Sesuai DB
		$sender = '0' . ltrim($to,'62');

		// ================================= Pengecekan Apakah Mode Approval / Pengisian Form ================================= \\


		// Mode Default -> Form (Pengisian Form)
		$mode = "form";
		$status = "";

		$sess = $this->form->cekSession($sender)->row();

		if($sess->status == 21){
			if($message != "#form#"){
				$mode = "verif_ot";
				$status = "note-reject-all";
				$plant = $sess->plant;
				$dept = $sess->department;
				$batch = $sess->iterasi;
			}
		}else if($sess->status == 22.1){
			if($message != "#form#"){
				$mode = "verif_ot";
				$status = "confirm-selection-action";
				$plant = $sess->plant;
				$dept = $sess->department;
				$batch = $sess->iterasi;
			}
		}else if($sess->status == 22.2){
			if($message != "#form#"){
				$mode = "verif_ot";
				$status = "reject-selection-note";
				$plant = $sess->plant;
				$dept = $sess->department;
				$batch = $sess->iterasi;
			}
		}

		// Pengecekan Mode Approval
		if($action_id){
			$break_id = explode("_", $action_id);

			$config = array();
			
			if($break_id[0] == "approval"){
				$mode = "start_approval";
			}else if($break_id[0] == "paging"){
				$mode = "paging";

				if($break_id[1] == "dept"){
					$lists = array();

					$id_form = $break_id[2];

					$from_get = (int)$break_id[3];
					$to_get = (int)$break_id[4];

					$approvalDept = $this->form->getApprovalDept($id_form)->result();
					$deptQuestion = $this->form->getDeptQuestion($id_form)->row();

					$totalApprovalDept = count($approvalDept);

					$total_data = $to_get - $from_get;

					$from_print = $from_get;

					$contNext = false;
					
					if($total_data > 8){
						$to_print = $from_get + 8;
						$contNext = true;
					}else{
						$to_print = $to_get;
					}

					if($from_get == 1){
						$contNext = true;
					}

					for($i = $from_print; $i <= $to_print; $i++){
						$config = [
							"id" => $approvalDept[$i-1]->department,
							"title" => $approvalDept[$i-1]->department,
							"description" => "   "
						];

						array_push($lists, $config);
					}

					if($from_get > 1){

						$from_before = $from_get - 8;
						$to_before = $to_get - 8;

						if($from_before < 9){
							$from_before = 1;
							$to_before = 9;
						}

						$config = [
							"id" => "paging_dept_" . $id_form . "_" . $from_before . "_" . $to_before,
							"title" => "Pilihan Sebelumnya <<",
							"description" => "   "
						];

						array_push($lists, $config);
					}

					if($contNext){
						$config = [
							"id" => "paging_dept_" . $id_form . "_" . ($to_print + 1) . "_" . $totalApprovalDept,
							"title" => "Pilihan Selanjutnya >>",
							"description" => "   "
						];

						array_push($lists, $config);
					}

					$config['msg'] = $deptQuestion->pertanyaan_dept;
					$config['title_lists'] = "List Department";
					$config['lists'] = $lists;

					$this->send_wa("lists", $to, $config);

				}
			}else if($break_id[0] == "startform"){
				$mode = "startform";

				$lists = array();

				$id_user_app = $break_id[1];
				$id_form = $break_id[2];
				$id_rec_h = $break_id[3];
				$id_pertanyaan = $break_id[4];
				$loop = $break_id[5];
				$seq = $break_id[6];

				$pertanyaan = $this->form->getFirstQuestion($id_form, $loop, $seq)->row();

				$config['msg'] = $pertanyaan->pertanyaan;
		
				if($pertanyaan->tipe_jawaban == 'date'){
					$config['msg'] .= "\\n_Format Jawaban : DD-MM-YYYY (Contoh: 23-10-2024)_";

					$this->send_wa("text", $to, $config);
				}else if($pertanyaan->tipe_jawaban == 'datetime'){
					$config['msg'] .= "\\n_Format Jawaban : DD-MM-YYYY JJ:MM (Contoh: 23-10-2024 15:30)_";

					$this->send_wa("text", $to, $config);
				}else if($pertanyaan->tipe_jawaban == 'time'){
					$config['msg'] .= "\\n_Format Jawaban : JJ:MM (Contoh: 15:30)_";

					$this->send_wa("text", $to, $config);
				}else if($pertanyaan->tipe_jawaban == 'list'){
					$config['msg'] .= "\\n_Penulisan List Jawaban Bisa Dipisah Menggunakan Karakter '|'_\\n_(Contoh: Nama 1|Nama 2|Nama 3)_";

					$this->send_wa("text", $to, $config);
				}else if($pertanyaan->tipe_jawaban == 'multi_option'){
					$config['msg'] .= "\\n_Jawaban Bisa Dipilih Lebih dari 1 (Contoh Format Jawaban : 1,2,3,4,5) *Jangan Gunakan Spasi*_";

					$this->send_wa("text", $to, $config);
				}else if($pertanyaan->tipe_jawaban == 'single_option'){
					$opsi = $this->form->getOpsiJawaban($id_pertanyaan)->result();

					foreach($opsi as $o){
						$config = [
							"id" => $o->id_opsi,
							"title" => $o->opsi_jawaban,
							"description" => "   "
						];

						array_push($lists, $config);
					}

					$config['msg'] = $pertanyaan->pertanyaan;
					$config['title_lists'] = "List Jawaban";
					$config['lists'] = $lists;

					$this->send_wa("lists", $to, $config);
				}else if($pertanyaan->tipe_jawaban == 'button'){
					$opsi = $this->form->getOpsiJawaban($pertanyaan->id_pertanyaan)->result();

					// $config['button'] = array(
					// 	[
					// 		"id" => "1",
					// 		"title" => "Konfirmasi"
					// 	],
					// 	[
					// 		"id" => "2",
					// 		"title" => "Anulir / Batalkan"
					// 	]
					// );

					foreach($opsi as $o){
						$data = [
							"id" => $o->id_opsi,
							"title" => $o->opsi_jawaban
						];

						array_push($lists, $data);
					}

					// $config['msg'] = $lists;
					$config['button'] = $lists;
					// $config['title_lists'] = "List Jawaban";

					$this->send_wa("button", $to, $config);
				}else{
					$this->send_wa("text", $to, $config);
				}

				$this->form->recordFormD($id_rec_h, $id_pertanyaan);

				$start_sess_cont = $this->form->contLoopSession($id_user_app, $id_form, $id_rec_h, $id_pertanyaan, $loop, $seq);
			}else if($break_id[0] == "verifOT"){
				if($break_id[1] == "confirm-all" || $break_id[1] == "confirm-selection" || $break_id[1] == "reject-all" || $break_id[1] == "reset-confirm-selection" || $break_id[1] == "reject-selection"){
					$status = $break_id[1];
					$plant = $break_id[2];
					$dept = $break_id[3];
					$batch = $break_id[4];

					if($break_id[1] == "reject-selection"){
						$no_reject = $break_id[5];
					}
				}else{
					$batch = $break_id[1];
					$plant = $break_id[2];
					$dept = $break_id[3];
				}

				$mode = "verif_ot";
			}
		}
		

		// ================================= Run Webhook ================================= \\

		// Declare Array Uhtuk Config Data Send WA
		$config = array();
		$lists = array();

		if($mode == "start_approval"){

			// --------------- Mode Kirim Approval Button (Untuk Start Approval) --------------- \\

			$sess = $this->form->cekSession($sender)->row();

			if($sess){
				$config['message'] = "Sudah Ada Approval yang Sedang Berjalan dan Harap User Menyelesaikan Approval Tersebut Telebih Dahulu";
			}

			$id_bongkar = explode("_",$action_id);

			//ID Rec Form H Approval
			$id_rec_h_approval = $id_bongkar[1];
			
			//ID Form Approval
			$id_form_approval = $id_bongkar[2];

			//Loop Approval
			$loop_app = $id_bongkar[3];

			// ID User Approval
			$user = $this->form->getUser($sender)->row();
			$id_user = $user->id_user;

			// var_dump($id_user);

			$seq_approval = $this->form->getApprovalAvailable($id_rec_h_approval, $sender)->row();

			// var_dump($seq_approval);

			$pertanyaanApproval = $this->form->getPertanyaanApproval($id_form_approval, $seq_approval->seq, $loop_app)->result();

			if(count($pertanyaanApproval) > 0){
				$this->form->recordFormD($id_rec_h_approval, $pertanyaanApproval[0]->id_pertanyaan);

				$config['msg'] = $pertanyaanApproval[0]->pertanyaan;

				if($pertanyaanApproval[0]->tipe_jawaban == 'date'){
					$config['msg'] .= "\\n_Format Jawaban : DD-MM-YYYY (Contoh: 23-10-2024)_";

					$this->send_wa("text", $to, $config);
				}else if($pertanyaanApproval[0]->tipe_jawaban == 'datetime'){
					$config['msg'] .= "\\n_Format Jawaban : DD-MM-YYYY JJ:MM (Contoh: 23-10-2024 15:30)_";

					$this->send_wa("text", $to, $config);
				}else if($pertanyaanApproval[0]->tipe_jawaban == 'time'){
					$config['msg'] .= "\\n_Format Jawaban : JJ:MM (Contoh: 15:30)_";

					$this->send_wa("text", $to, $config);
				}else if($pertanyaanApproval[0]->tipe_jawaban == 'list'){
					$config['msg'] .= "\\n_Penulisan List Jawaban Bisa Dipisah Menggunakan Karakter '|'_\\n_(Contoh: Nama 1|Nama 2|Nama 3)_";

					$this->send_wa("text", $to, $config);
				}else if($pertanyaanApproval[0]->tipe_jawaban == 'multi_option'){
					$config['msg'] .= "\\n_Jawaban Bisa Dipilih Lebih dari 1 (Contoh Format Jawaban : 1,2,3,4,5) *Jangan Gunakan Spasi*_";

					$opsi = $this->form->getOpsiJawaban($pertanyaanApproval[0]->id_pertanyaan)->result();

					$config['msg'] .= "\\n";
					
					foreach($opsi as $o){
						$config['msg'] .= "\\n" . $o->seq_jawaban . ". " . $o->opsi_jawaban;
					}

					$this->send_wa("text", $to, $config);
				}else if($pertanyaanApproval[0]->tipe_jawaban == 'single_option'){
					$opsi = $this->form->getOpsiJawaban($pertanyaanApproval[0]->id_pertanyaan)->result();

					foreach($opsi as $o){
						$data = [
							"id" => $o->id_opsi,
							"title" => $o->opsi_jawaban,
							"description" => "   "
						];

						array_push($lists, $data);
					}

					$config['lists'] = $lists;
					$config['title_lists'] = "List Jawaban";

					$this->send_wa("lists", $to, $config);
				}else if($pertanyaan[0]->tipe_jawaban == 'button'){
					$opsi = $this->form->getOpsiJawaban($pertanyaan[0]->id_pertanyaan)->result();

					// $config['button'] = array(
					// 	[
					// 		"id" => "1",
					// 		"title" => "Konfirmasi"
					// 	],
					// 	[
					// 		"id" => "2",
					// 		"title" => "Anulir / Batalkan"
					// 	]
					// );

					foreach($opsi as $o){
						$data = [
							"id" => $o->id_opsi,
							"title" => $o->opsi_jawaban
						];

						array_push($lists, $data);
					}

					// $config['msg'] = $lists;
					$config['button'] = $lists;
					// $config['title_lists'] = "List Jawaban";

					$this->send_wa("button", $to, $config);
				}else{
					$this->send_wa("text", $to, $config);
				}

				$start_sess_approval = $this->form->recordSessionApprovalQuestion($id_user, $id_form_approval, $id_rec_h_approval, $pertanyaanApproval[0]->id_pertanyaan, $seq_approval->loop_app, $seq_approval->seq);
			}else{
				// Memulai Session Approval
				$start_sess_approval = $this->form->recordSessionApproval($id_user, $id_form_approval, $id_rec_h_approval, $seq_approval->loop_app, $seq_approval->seq);
	
				if($start_sess_approval){
	
					// Get Title Form dan No Form Bedasarkan ID Rec Form H
					$no_form = $this->form->getNoForm($id_rec_h_approval)->row();
					
					/*// Penulisan Pesan Approval
					$config['msg'] = "Apakah Anda Akan Melakukan Konfirmasi Untuk Form: *" . $no_form->title . "*, Dengan Nomor Form: *" . $no_form->no_form . "*\\n\\nPratinjau Form:\\nhttps://cpipga.com/form/" . $no_form->no_form;
						
					// Config Button WA
					$config['button'] = array(
						[
							"id" => "1",
							"title" => "Menyetujui"
						],
						[
							"id" => "2",
							"title" => "Menolak"
						]
					);
					
					// Kirim Pesan WA
					$this->send_wa("button", $to, $config);*/

					$config['msg'] = "Apakah Anda Akan Melakukan Konfirmasi Untuk Form: *" . $no_form->title . "*, Dengan Nomor Form: *" . $no_form->no_form . "*\\n\\n*Pratinjau Form Tertaut Pada Pesan Ini*";
					$config['link'] = $this->convertPDF($id_rec_h_approval);

					$config['button'] = array(
						[
							"id" => "1",
							"title" => "Menyetujui"
						],
						[
							"id" => "2",
							"title" => "Menolak"
						]
					);

					$this->send_wa("preview", $to, $config);
				}
			}


		}else if($mode == "form"){

			// --------------- Mode Pengisian Form / Pengisian Approval --------------- \\

			//Record Semua Chat WA
			$this->recordLog($data);

			// Setup Alur Pengisian Form dan Alur Approval
			$sess = $this->form->cekSession($sender)->row();

			// Start Pengisian Form / Reset Session
			if($message == "#form#"){

				// Reset Session
				if($sess){
					$this->form->updateSession($sess->id_session, 0, 0, 0, 0, 0, 0, 0, 99);
				}

				// Mendapatkan List Form Yang Bisa di Akses Oleh User Pemohon
				$form = $this->form->getForm($sender)->result();

				if($form){

					$user = $this->form->getUser($sender)->row();
					$id_user = $user->id_user;

					// Set New Session
					$record_sess = $this->form->recordSession($id_user);

					// Setup dan Send Pesan List Form Ke User
					if($record_sess){
						$msg = "";
						$msg = "Silahkan Memilih Jenis Form";
	
						$lists = array();
	
						foreach($form as $f){
							$config = [
								"id" => $f->id_form,
								"title" => $f->title,
								"description" => "   "
							];
	
							array_push($lists, $config);
						}

						var_dump($lists);
	
						$config['msg'] = $msg;
						$config['title_lists'] = "Menu";
						$config['lists'] = $lists;

						// print_r($config);

						$this->send_wa("lists", $to, $config);

						die();
					}

				}else{
					// Setup dan Send Pesan Jika User Tidak Memiliki Level Akses Form
					$config['msg'] = "Anda Tidak Memiliki Level Akses Form yang Bisa Dipilih";
					$this->send_wa("text", $to, $config);
					
					die();
				}
			}else if($message == "#daftar"){
				
			}else{
				if($sess->status == 0){
					
					// --------------- Session Status 0 --------------- \\

					// Get Pilihan ID Form
					$id_form = $action_id;

					$flagDl = false;
					$flagDlCanGo = false;

					if($id_form == 9){
						$flagDl = true;

						$nopeg = $this->matt->getUser($sender)->result();
						$DL = $this->matt->checkDL($nopeg[0]->nopeg)->result();

						if($DL){
							$startdl = new DateTime($DL[0]->start);
							$enddl = new DateTime($DL[0]->end);
							$timenow = new DateTime(date('Y-m-d H:i:s'));

							if($timenow >= $startdl && $timenow <= $enddl){
								$flagDlCanGo = true;
							}
						}
					}

					if($flagDl){
						if($flagDlCanGo){
							$lists = array();
			
							$pertanyaan = $this->form->getFirstQuestion($id_form, $sess->iterasi, 0)->row();
			
							$id_pertanyaan_pertama = $pertanyaan->id_pertanyaan;
		
							$config['msg'] = $pertanyaan->pertanyaan;
	
							// var_dump($pertanyaan->tipe_jawaban);
			
							if($pertanyaan->tipe_jawaban == 'date'){
								$config['msg'] .= "\\n_Format Jawaban : DD-MM-YYYY (Contoh: 23-10-2024)_";
		
								$this->send_wa("text", $to, $config);
							}else if($pertanyaan->tipe_jawaban == 'datetime'){
								$config['msg'] .= "\\n_Format Jawaban : DD-MM-YYYY JJ:MM (Contoh: 23-10-2024 15:30)_";
		
								$this->send_wa("text", $to, $config);
							}else if($pertanyaan->tipe_jawaban == 'time'){
								$config['msg'] .= "\\n_Format Jawaban : JJ:MM (Contoh: 15:30)_";
		
								$this->send_wa("text", $to, $config);
							}else if($pertanyaan->tipe_jawaban == 'list'){
								$config['msg'] .= "\\n_Penulisan List Jawaban Bisa Dipisah Menggunakan Karakter '|'_\\n_(Contoh: Nama 1|Nama 2|Nama 3)_";
			
								$this->send_wa("text", $to, $config);
							}else if($pertanyaan->tipe_jawaban == 'multi_option'){
								$config['msg'] .= "\\n_Jawaban Bisa Dipilih Lebih dari 1 (Contoh Format Jawaban : 1,2,3,4,5) *Jangan Gunakan Spasi*_";
	
								$opsi = $this->form->getOpsiJawaban($pertanyaan->id_pertanyaan)->result();
	
								$config['msg'] .= "\\n";
								
								foreach($opsi as $o){
									$config['msg'] .= "\\n" . $o->seq_jawaban . ". " . $o->opsi_jawaban;
								}
		
								$this->send_wa("text", $to, $config);
							}else if($pertanyaan->tipe_jawaban == 'single_option'){
								$opsi = $this->form->getOpsiJawaban($id_pertanyaan_pertama)->result();
		
								foreach($opsi as $o){
									$config = [
										"id" => $o->id_opsi,
										"title" => $o->opsi_jawaban,
										"description" => "   "
									];
			
									array_push($lists, $config);
								}
	
								$config['title_lists'] = "List Jawaban";
								$config['lists'] = $lists;
	
								$this->send_wa("lists", $to, $config);
							}else if($pertanyaan->tipe_jawaban == 'button'){
								$opsi = $this->form->getOpsiJawaban($pertanyaan->id_pertanyaan)->result();
		
								foreach($opsi as $o){
									$data = [
										"id" => $o->id_opsi,
										"title" => $o->opsi_jawaban
									];
			
									array_push($lists, $data);
								}
	
								// $config['msg'] = $lists;
								$config['button'] = $lists;
								// $config['title_lists'] = "List Jawaban";
		
								$this->send_wa("button", $to, $config);
							}else{
								$this->send_wa("text", $to, $config);
							}
	
							$user = $this->form->getUser($sender)->row();
							$plantApproval = $user->plant;
							$id_user_save = $user->id_user;
							$department = $user->id_user;
	
							$id_rec_h = $this->form->recordFormH($id_form, $id_user_save, $plantApproval, $department);
	
							$rec_d = $this->form->recordFormD($id_rec_h, $id_pertanyaan_pertama);
		
							$update_sess = $this->form->updateSession($sess->id_session, $id_form, $id_rec_h, $id_pertanyaan_pertama, 0, 0, $sess->iterasi, $sess->on_approval, 1);
						}else{
							$config['msg'] = "Tidak Ada Memo Dinas Luar Saat Ini.\\nTanggal & Jam Sekarang : " . date('d F Y H:i');

							$this->send_wa("text", $to, $config);

							$form = $this->form->getForm($sender)->result();

							$msg = "";
							$msg = "Silahkan Memilih Jenis Form";
		
							$lists = array();
		
							foreach($form as $f){
								$config = [
									"id" => $f->id_form,
									"title" => $f->title,
									"description" => "   "
								];
		
								array_push($lists, $config);
							}

							var_dump($lists);
		
							$config['msg'] = $msg;
							$config['title_lists'] = "Menu";
							$config['lists'] = $lists;

							$this->send_wa("lists", $to, $config);
							die();
						}
					}else{
						// Get List Department Pada ID Form yang Dipilih
						$approvalDept = $this->form->getApprovalDept($id_form)->result();
						$deptQuestion = $this->form->getDeptQuestion($id_form)->row();
	
						if($approvalDept){
							$totalApprovalDept = count($approvalDept);
		
							$lists = array();
		
							if($totalApprovalDept > 10){
								for($i = 1; $i <= 9; $i++){
									$config = [
										"id" => $approvalDept[$i-1]->department,
										"title" => $approvalDept[$i-1]->department,
										"description" => "   "
									];
		
									array_push($lists, $config);
								}
		
								$config = [
									"id" => "paging_dept_" . $id_form . "_10_" . $totalApprovalDept,
									"title" => "Pilihan Selanjutnya >>",
									"description" => "   "
								];
		
								array_push($lists, $config);
							}else{
								foreach($approvalDept as $ad){
									$config = [
										"id" => $ad->department,
										"title" => $ad->department,
										"description" => "   "
									];
			
									array_push($lists, $config);
								}
							}
		
							// Update Session 0.1 [Pilih Department]
							$update_sess = $this->form->updateSession($sess->id_session, $id_form, 0, 0, 0, $sess->seq_app, 1, $sess->on_approval, 0.1);
		
							$config['msg'] = $deptQuestion->pertanyaan_dept;
							$config['title_lists'] = "List Department";
							$config['lists'] = $lists;
		
							$this->send_wa("lists", $to, $config);
						}else{
							$lists = array();
			
							$pertanyaan = $this->form->getFirstQuestion($id_form, $sess->iterasi, 0)->row();
			
							$id_pertanyaan_pertama = $pertanyaan->id_pertanyaan;
		
							$config['msg'] = $pertanyaan->pertanyaan;
	
							// var_dump($pertanyaan->tipe_jawaban);
			
							if($pertanyaan->tipe_jawaban == 'date'){
								$config['msg'] .= "\\n_Format Jawaban : DD-MM-YYYY (Contoh: 23-10-2024)_";
		
								$this->send_wa("text", $to, $config);
							}else if($pertanyaan->tipe_jawaban == 'datetime'){
								$config['msg'] .= "\\n_Format Jawaban : DD-MM-YYYY JJ:MM (Contoh: 23-10-2024 15:30)_";
		
								$this->send_wa("text", $to, $config);
							}else if($pertanyaan->tipe_jawaban == 'time'){
								$config['msg'] .= "\\n_Format Jawaban : JJ:MM (Contoh: 15:30)_";
		
								$this->send_wa("text", $to, $config);
							}else if($pertanyaan->tipe_jawaban == 'list'){
								$config['msg'] .= "\\n_Penulisan List Jawaban Bisa Dipisah Menggunakan Karakter '|'_\\n_(Contoh: Nama 1|Nama 2|Nama 3)_";
			
								$this->send_wa("text", $to, $config);
							}else if($pertanyaan->tipe_jawaban == 'multi_option'){
								$config['msg'] .= "\\n_Jawaban Bisa Dipilih Lebih dari 1 (Contoh Format Jawaban : 1,2,3,4,5) *Jangan Gunakan Spasi*_";
	
								$opsi = $this->form->getOpsiJawaban($pertanyaan->id_pertanyaan)->result();
	
								$config['msg'] .= "\\n";
								
								foreach($opsi as $o){
									$config['msg'] .= "\\n" . $o->seq_jawaban . ". " . $o->opsi_jawaban;
								}
		
								$this->send_wa("text", $to, $config);
							}else if($pertanyaan->tipe_jawaban == 'single_option'){
								$opsi = $this->form->getOpsiJawaban($id_pertanyaan_pertama)->result();
		
								foreach($opsi as $o){
									$config = [
										"id" => $o->id_opsi,
										"title" => $o->opsi_jawaban,
										"description" => "   "
									];
			
									array_push($lists, $config);
								}
	
								$config['title_lists'] = "List Jawaban";
								$config['lists'] = $lists;
								$config['msg'] = $pertanyaan->pertanyaan;
	
								$this->send_wa("lists", $to, $config);
							}else if($pertanyaan->tipe_jawaban == 'button'){
								$opsi = $this->form->getOpsiJawaban($pertanyaan->id_pertanyaan)->result();
		
								foreach($opsi as $o){
									$data = [
										"id" => $o->id_opsi,
										"title" => $o->opsi_jawaban
									];
			
									array_push($lists, $data);
								}
	
								// $config['msg'] = $lists;
								$config['button'] = $lists;
								// $config['title_lists'] = "List Jawaban";
		
								$this->send_wa("button", $to, $config);
							}else{
								$this->send_wa("text", $to, $config);
							}
	
							$user = $this->form->getUser($sender)->row();
							$plantApproval = $user->plant;
							$id_user_save = $user->id_user;
							$department = $user->id_user;
	
							$id_rec_h = $this->form->recordFormH($id_form, $id_user_save, $plantApproval, $department);
	
							$rec_d = $this->form->recordFormD($id_rec_h, $id_pertanyaan_pertama);
		
							$update_sess = $this->form->updateSession($sess->id_session, $id_form, $id_rec_h, $id_pertanyaan_pertama, 0, 0, $sess->iterasi, $sess->on_approval, 1);
						}
					}


				}else if($sess->status == 0.1){

					// --------------- Session Status 0.1 --------------- \\

					$flag_approval = false;

					$user = $this->form->getUser($sender)->row();

					$departmentApproval = $action_id;
					$plantApproval = $user->plant;
					$id_user_save = $user->id_user;
					$idFormApproval = $sess->id_form;

					$id_rec_h = $this->form->recordFormH($idFormApproval, $id_user_save, $plantApproval, $departmentApproval);

					$dataApprovalChoose = $this->form->getApprovalCtr($idFormApproval, $departmentApproval, $plantApproval)->result();
					$dataApprovalNoChoose = $this->form->getApprovalNoChoose($idFormApproval, $departmentApproval, $plantApproval)->result();

					if(count($dataApprovalNoChoose) > 0){
						foreach($dataApprovalNoChoose as $danc){
							$record_data_approval = $this->form->recordApproval($id_rec_h, $danc->id_alur, '', 0, $danc->seq_app, $sess->iterasi);
						}
					}

					if(count($dataApprovalChoose) > 0){
						$flag_approval = true;

						$userApprovalChoose = $this->form->getApprovalBySeq($idFormApproval, $departmentApproval, $plantApproval, $dataApprovalChoose[0]->seq_app)->result();

						$msg_list_approval = "Silahkan Memilih " . $userApprovalChoose[0]->caption . "\\n\\n";
						$ctr = 1;

						foreach($userApprovalChoose as $uac){
							$msg_list_approval .= $ctr . ". " . $uac->nama . "\\n";
							$ctr++;
						}
					}

					if($flag_approval){
						$update_sess = $this->form->updateSession($sess->id_session, $sess->id_form, $id_rec_h, 0, 0, $dataApprovalChoose[0]->seq_app, $sess->iterasi, $sess->on_approval, 0.11);
						
						$config['msg'] = $msg_list_approval;

						$this->send_wa("text", $to, $config);
					}else{
						$lists = array();

						$pertanyaan = $this->form->getFirstQuestion($action_id, $sess->iterasi, 0)->row();

						var_dump($pertanyaan);
	
						$id_pertanyaan_pertama = $pertanyaan->id_pertanyaan;
	
						$config['msg'] = $pertanyaan->pertanyaan;
	
						if($pertanyaan->tipe_jawaban == 'date'){
							$config['msg'] .= "\\n_Format Jawaban : DD-MM-YYYY (Contoh: 23-10-2024)_";
	
							$this->send_wa("text", $to, $config);
						}else if($pertanyaan->tipe_jawaban == 'datetime'){
							$config['msg'] .= "\\n_Format Jawaban : DD-MM-YYYY JJ:MM (Contoh: 23-10-2024 15:30)_";
	
							$this->send_wa("text", $to, $config);
						}else if($pertanyaan->tipe_jawaban == 'time'){
							$config['msg'] .= "\\n_Format Jawaban : JJ:MM (Contoh: 15:30)_";
	
							$this->send_wa("text", $to, $config);
						}else if($pertanyaan->tipe_jawaban == 'list'){
							$config['msg'] .= "\\n_Penulisan List Jawaban Bisa Dipisah Menggunakan Karakter '|'_\\n_(Contoh: Nama 1|Nama 2|Nama 3)_";
		
							$this->send_wa("text", $to, $config);
						}else if($pertanyaan->tipe_jawaban == 'multi_option'){
							$config['msg'] .= "\\n_Jawaban Bisa Dipilih Lebih dari 1 (Contoh Format Jawaban : 1,2,3,4,5) *Jangan Gunakan Spasi*_";
	
							$this->send_wa("text", $to, $config);
						}else if($pertanyaan->tipe_jawaban == 'single_option'){
							$opsi = $this->form->getOpsiJawaban($id_pertanyaan_pertama)->result();
	
							foreach($opsi as $o){
								$config = [
									"id" => $o->id_opsi,
									"title" => $o->opsi_jawaban,
									"description" => "   "
								];
		
								array_push($lists, $config);
							}

							$config['msg'] = $msg_list_approval;
							$config['title_lists'] = "List Jawaban";
							$config['lists'] = $lists;

							$this->send_wa("lists", $to, $config);
						}else if($pertanyaan->tipe_jawaban == 'button'){
							$opsi = $this->form->getOpsiJawaban($pertanyaan->id_pertanyaan)->result();
	
							foreach($opsi as $o){
								$data = [
									"id" => $o->id_opsi,
									"title" => $o->opsi_jawaban
								];
		
								array_push($lists, $data);
							}

							// $config['msg'] = $lists;
							$config['button'] = $lists;
							// $config['title_lists'] = "List Jawaban";
	
							$this->send_wa("button", $to, $config);
						}else{
							$this->send_wa("text", $to, $config);
						}

						$id_rec_h = $this->form->recordFormH($id_form, $id_user, $plant, $departmentApproval);

						$this->form->recordFormD($id_rec_h, $id_pertanyaan_pertama);
	
						$update_sess = $this->form->updateSession($sess->id_session, $sess->id_form, $id_rec_h, $id_pertanyaan_pertama, 0, $sess->seq_app, $sess->iterasi, $sess->on_approval, 1);
					}
				}else if($sess->status == 0.11){
					$user = $this->form->getUser($sender)->row();

					if(filter_var($message, FILTER_VALIDATE_INT)){
						$departmentApproval = $this->form->getDeptFromRecH($sess->id_rec_h)->row();
	
						$departmentApproval = $departmentApproval->department;
						$plantApproval = $user->plant;
						$id_user_save = $user->id_user;
						$idFormApproval = $sess->id_form;
						$seq_now = $sess->seq_app;
	
						$userApprovalChoose = $this->form->getApprovalBySeq($idFormApproval, $departmentApproval, $plantApproval, $sess->seq_app)->result();
	
						$userChoose = (int)$message - 1;

						$dataApprovalChoose = $this->form->getApprovalCtr($idFormApproval, $departmentApproval, $plantApproval)->result();

						if((int)$message > count($userApprovalChoose) || $userChoose < 0){
							$deptForm = $this->form->getDeptFromRecH($sess->id_rec_h)->result();

							$userApprovalChoose = $this->form->getApprovalBySeq($sess->id_form, $deptForm[0]->department, $deptForm[0]->plant, $sess->seq_app)->result();

							$msg_list_approval = "*Jawaban Tidak Ada Dalam Pilihan*\\n\\nSilahkan Memilih " . $userApprovalChoose[0]->caption . "\\n\\n";
							$ctr = 1;

							foreach($userApprovalChoose as $uac){
								$msg_list_approval .= $ctr . ". " . $uac->nama . "\\n";
								$ctr++;
							}

							$config['msg'] = $msg_list_approval;

							$this->send_wa("text", $to, $config);
						}else{
							$record_data_approval = $this->form->recordApproval($sess->id_rec_h, $userApprovalChoose[$userChoose]->id_alur, '', 0, $sess->seq_app, $sess->iterasi);

							$flag_done_check = false;
							$seq_hit = 0;
		
							for($i = 0; $i < count($dataApprovalChoose); $i++){
								if(!$flag_done_check){
									if($i <= (count($dataApprovalChoose) - 2)){
										if($dataApprovalChoose[$i]->seq_app == $seq_now){
											$seq_hit = $dataApprovalChoose[$i+1]->seq_app;
											$flag_done_check = true;
										}
									}
								}
							}
		
							if($flag_done_check){
								$userApprovalChoose_next = $this->form->getApprovalBySeq($idFormApproval, $departmentApproval, $plantApproval, $seq_hit)->result();
		
								$msg_list_approval = "Silahkan Memilih " . $userApprovalChoose_next[0]->caption . "\\n\\n";
								$ctr = 1;
		
								foreach($userApprovalChoose_next as $uac){
									$msg_list_approval .= $ctr . ". " . $uac->nama . "\\n";
									$ctr++;
								}
		
								$update_sess = $this->form->updateSession($sess->id_session, $sess->id_form, $sess->id_rec_h, 0, 0, $seq_hit, $sess->iterasi, $sess->on_approval, 0.11);
		
								$config['msg'] = $msg_list_approval;
		
								$this->send_wa("text", $to, $config);
							}else{
								$lists = array();
		
								$pertanyaan = $this->form->getFirstQuestion($sess->id_form, $sess->iterasi, 0)->row();
				
								$id_pertanyaan_pertama = $pertanyaan->id_pertanyaan;
			
								$config['msg'] = $pertanyaan->pertanyaan;
		
								// var_dump($pertanyaan->tipe_jawaban);
				
								if($pertanyaan->tipe_jawaban == 'date'){
									$config['msg'] .= "\\n_Format Jawaban : DD-MM-YYYY (Contoh: 23-10-2024)_";
			
									$this->send_wa("text", $to, $config);
								}else if($pertanyaan->tipe_jawaban == 'datetime'){
									$config['msg'] .= "\\n_Format Jawaban : DD-MM-YYYY JJ:MM (Contoh: 23-10-2024 15:30)_";
			
									$this->send_wa("text", $to, $config);
								}else if($pertanyaan->tipe_jawaban == 'time'){
									$config['msg'] .= "\\n_Format Jawaban : JJ:MM (Contoh: 15:30)_";
			
									$this->send_wa("text", $to, $config);
								}else if($pertanyaan->tipe_jawaban == 'list'){
									$config['msg'] .= "\\n_Penulisan List Jawaban Bisa Dipisah Menggunakan Karakter '|'_\\n_(Contoh: Nama 1|Nama 2|Nama 3)_";
				
									$this->send_wa("text", $to, $config);
								}else if($pertanyaan->tipe_jawaban == 'multi_option'){
									$config['msg'] .= "\\n_Jawaban Bisa Dipilih Lebih dari 1 (Contoh Format Jawaban : 1,2,3,4,5) *Jangan Gunakan Spasi*_";
		
									$opsi = $this->form->getOpsiJawaban($pertanyaan->id_pertanyaan)->result();
		
									$config['msg'] .= "\\n";
									
									foreach($opsi as $o){
										$config['msg'] .= "\\n" . $o->seq_jawaban . ". " . $o->opsi_jawaban;
									}
			
									$this->send_wa("text", $to, $config);
								}else if($pertanyaan->tipe_jawaban == 'single_option'){
									$opsi = $this->form->getOpsiJawaban($id_pertanyaan_pertama)->result();
			
									foreach($opsi as $o){
										$config = [
											"id" => $o->id_opsi,
											"title" => $o->opsi_jawaban,
											"description" => "   "
										];
				
										array_push($lists, $config);
									}
		
									$config['title_lists'] = "List Jawaban";
									$config['lists'] = $lists;
		
									$this->send_wa("lists", $to, $config);
								}else if($pertanyaan->tipe_jawaban == 'button'){
									$opsi = $this->form->getOpsiJawaban($pertanyaan->id_pertanyaan)->result();
		
									// $config['button'] = array(
									// 	[
									// 		"id" => "1",
									// 		"title" => "Konfirmasi"
									// 	],
									// 	[
									// 		"id" => "2",
									// 		"title" => "Anulir / Batalkan"
									// 	]
									// );
			
									foreach($opsi as $o){
										$data = [
											"id" => $o->id_opsi,
											"title" => $o->opsi_jawaban
										];
				
										array_push($lists, $data);
									}
		
									// $config['msg'] = $lists;
									$config['button'] = $lists;
									// $config['title_lists'] = "List Jawaban";
			
									$this->send_wa("button", $to, $config);
								}else{
									$this->send_wa("text", $to, $config);
								}
		
								$rec_d = $this->form->recordFormD($sess->id_rec_h, $id_pertanyaan_pertama);
			
								$update_sess = $this->form->updateSession($sess->id_session, $sess->id_form, $sess->id_rec_h, $id_pertanyaan_pertama, 0, 0, $sess->iterasi, $sess->on_approval, 1);
							}
						}
					}else{
						$deptForm = $this->form->getDeptFromRecH($sess->id_rec_h)->result();

						$userApprovalChoose = $this->form->getApprovalBySeq($sess->id_form, $deptForm[0]->department, $deptForm[0]->plant, $sess->seq_app)->result();

						$msg_list_approval = "*Jawaban Tidak Ada Dalam Pilihan*\\n\\nSilahkan Memilih " . $userApprovalChoose[0]->caption . "\\n\\n";
						$ctr = 1;

						foreach($userApprovalChoose as $uac){
							$msg_list_approval .= $ctr . ". " . $uac->nama . "\\n";
							$ctr++;
						}

						$config['msg'] = $msg_list_approval;

						$this->send_wa("text", $to, $config);
					}
				}else if($sess->status == 1){
					$pertanyaan = $this->form->getPertanyaan($sess->id_pertanyaan)->result();

					$file_now = 0;

					if($pertanyaan[0]->tipe_jawaban == "single_option" || $pertanyaan[0]->tipe_jawaban == "button"){
						$message = $action_id;
					}else if($pertanyaan[0]->tipe_jawaban == "picture" || $pertanyaan[0]->tipe_jawaban == "document"){
						$message = $file;
					}

					$result = $this->check_input($pertanyaan[0]->tipe_jawaban, $message, $sess->id_pertanyaan, $sender);

					if(!$result['status'] || $result['status'] == 'error'){
						$config['msg'] = "*Jawaban Tidak Benar*\\n" . $pertanyaan[0]->pertanyaan;

						if($pertanyaan[0]->tipe_jawaban == 'date'){
							$config['msg'] .= "\\n_Format Jawaban : DD-MM-YYYY (Contoh: 23-10-2024)_";
	
							$this->send_wa("text", $to, $config);
						}else if($pertanyaan[0]->tipe_jawaban == 'datetime'){
							$config['msg'] .= "\\n_Format Jawaban : DD-MM-YYYY JJ:MM (Contoh: 23-10-2024 15:30)_";
	
							$this->send_wa("text", $to, $config);
						}else if($pertanyaan[0]->tipe_jawaban == 'time'){
							$config['msg'] .= "\\n_Format Jawaban : JJ:MM (Contoh: 15:30)_";
	
							$this->send_wa("text", $to, $config);
						}else if($pertanyaan[0]->tipe_jawaban == 'list'){
							$config['msg'] .= "\\n_Penulisan List Jawaban Bisa Dipisah Menggunakan Karakter '|'_\\n_(Contoh: Nama 1|Nama 2|Nama 3)_";
		
							$this->send_wa("text", $to, $config);
						}else if($pertanyaan[0]->tipe_jawaban == 'multi_option'){
							$config['msg'] .= "\\n_Jawaban Bisa Dipilih Lebih dari 1 (Contoh Format Jawaban : 1,2,3,4,5) *Jangan Gunakan Spasi*_";

							$opsi = $this->form->getOpsiJawaban($pertanyaan[0]->id_pertanyaan)->result();

							$config['msg'] .= "\\n";
							
							foreach($opsi as $o){
								$config['msg'] .= "\\n" . $o->seq_jawaban . ". " . $o->opsi_jawaban;
							}
	
							$this->send_wa("text", $to, $config);
						}else if($pertanyaan[0]->tipe_jawaban == 'single_option'){
							$opsi = $this->form->getOpsiJawaban($pertanyaan[0]->id_pertanyaan)->result();
	
							foreach($opsi as $o){
								$data = [
									"id" => $o->id_opsi,
									"title" => $o->opsi_jawaban,
									"description" => "   "
								];
		
								array_push($lists, $data);
							}

							// $config['msg'] = $lists;
							$config['lists'] = $lists;
							$config['title_lists'] = "List Jawaban";
	
							$this->send_wa("lists", $to, $config);
						}else if($pertanyaan[0]->tipe_jawaban == 'button'){
							$opsi = $this->form->getOpsiJawaban($pertanyaan[0]->id_pertanyaan)->result();

							// $config['button'] = array(
							// 	[
							// 		"id" => "1",
							// 		"title" => "Konfirmasi"
							// 	],
							// 	[
							// 		"id" => "2",
							// 		"title" => "Anulir / Batalkan"
							// 	]
							// );
	
							foreach($opsi as $o){
								$data = [
									"id" => $o->id_opsi,
									"title" => $o->opsi_jawaban
								];
		
								array_push($lists, $data);
							}

							// $config['msg'] = $lists;
							$config['button'] = $lists;
							// $config['title_lists'] = "List Jawaban";
	
							$this->send_wa("button", $to, $config);
						}else{
							$this->send_wa("text", $to, $config);
						}
					}else if($result['status'] == "answer"){
						$id_rec_d = $this->form->getIDRecD($sess->id_rec_h, $sess->id_pertanyaan)->row();

						// Kalau Jawaban Termasuk Caption
						if($pertanyaan[0]->as_caption){
							$caption = $this->form->getCaptionForm($sess->id_rec_h)->row();

							$caption_form = $caption->caption_form;

							if($caption_form){
								$caption_form .= " | " . $result['result'];
							}else{
								$caption_form .= $result['result'];
							}

							$updateCaption = $this->form->updateCaptionRecH($sess->id_rec_h, $caption_form);
						}

						$send_data = $result['result'];

						$resultUpdate = $this->form->updateValueRecordFormD($id_rec_d->id_rec_form_d, $send_data);

						$checkQuestion = $this->form->checkNextQuestion($sess->id_form, $sess->id_rec_h, $sess->on_approval, $sess->iterasi, $sess->seq_app)->result();

						if($checkQuestion[0]->status == "NO"){
							$seq_next = (int)$checkQuestion[0]->jawaban + 1;

							$nextQuestion = $this->form->getPertanyaanBySeq($sess->id_form, $seq_next, $sess->seq_app, $sess->on_approval, $sess->iterasi)->result();

							$config['msg'] = $nextQuestion[0]->pertanyaan;

							if($nextQuestion[0]->tipe_jawaban == 'date'){
								$config['msg'] .= "\\n_Format Jawaban : DD-MM-YYYY (Contoh: 23-10-2024)_";
		
								$this->send_wa("text", $to, $config);
							}else if($nextQuestion[0]->tipe_jawaban == 'datetime'){
								$config['msg'] .= "\\n_Format Jawaban : DD-MM-YYYY JJ:MM (Contoh: 23-10-2024 15:30)_";
		
								$this->send_wa("text", $to, $config);
							}else if($nextQuestion[0]->tipe_jawaban == 'time'){
								$config['msg'] .= "\\n_Format Jawaban : JJ:MM (Contoh: 15:30)_";
		
								$this->send_wa("text", $to, $config);
							}else if($nextQuestion[0]->tipe_jawaban == 'list'){
								$config['msg'] .= "\\n_Penulisan List Jawaban Bisa Dipisah Menggunakan Karakter '|'_\\n_(Contoh: Nama 1|Nama 2|Nama 3)_";
			
								$this->send_wa("text", $to, $config);
							}else if($nextQuestion[0]->tipe_jawaban == 'multi_option'){
								$config['msg'] .= "\\n_Jawaban Bisa Dipilih Lebih dari 1 (Contoh Format Jawaban : 1,2,3,4,5) *Jangan Gunakan Spasi*_";

								$opsi = $this->form->getOpsiJawaban($nextQuestion[0]->id_pertanyaan)->result();

								$config['msg'] .= "\\n";
								
								foreach($opsi as $o){
									$config['msg'] .= "\\n" . $o->seq_jawaban . ". " . $o->opsi_jawaban;
								}
		
								$this->send_wa("text", $to, $config);
							}else if($nextQuestion[0]->tipe_jawaban == 'single_option'){
								$opsi = $this->form->getOpsiJawaban($nextQuestion[0]->id_pertanyaan)->result();
		
								foreach($opsi as $o){
									$data = [
										"id" => $o->id_opsi,
										"title" => $o->opsi_jawaban,
										"description" => "   "
									];
			
									array_push($lists, $data);
								}

								$config['lists'] = $lists;
								$config['title_lists'] = "List Jawaban";
		
								$this->send_wa("lists", $to, $config);
							}else if($nextQuestion[0]->tipe_jawaban == 'button'){
								$opsi = $this->form->getOpsiJawaban($nextQuestion[0]->id_pertanyaan)->result();
		
								foreach($opsi as $o){
									$data = [
										"id" => $o->id_opsi,
										"title" => $o->opsi_jawaban
									];
			
									array_push($lists, $data);
								}
	
								$config['button'] = $lists;
		
								$this->send_wa("button", $to, $config);
							}else{
								$this->send_wa("text", $to, $config);
							}

							$rec_d = $this->form->recordFormD($sess->id_rec_h, $nextQuestion[0]->id_pertanyaan);

							if(strpos($nextQuestion[0]->seq, ".")){
								$break_seq = explode(".", $nextQuestion[0]->seq);

								$update_sess = $this->form->updateSession($sess->id_session, $sess->id_form, $sess->id_rec_h, $nextQuestion[0]->id_pertanyaan, 0, $sess->seq_app, $sess->iterasi, $sess->on_approval, 1.2);
							}else{
								$update_sess = $this->form->updateSession($sess->id_session, $sess->id_form, $sess->id_rec_h, $nextQuestion[0]->id_pertanyaan, 0, $sess->seq_app, $sess->iterasi, $sess->on_approval, 1);
							}
						}else{
							$update_sess = $this->form->updateSession($sess->id_session, $sess->id_form, $sess->id_rec_h, 0, 0, $sess->seq_app, $sess->iterasi, $sess->on_approval, 2);

							$infoForm = $this->form->getDetailForm($sess->id_rec_h)->row();

							if($infoForm->template_form){
								$config['msg'] = "Silahkan Melihat Pratinjau Dari Form yang Sudah Diisi Pada Form Tertaut Di Pesan Ini\\n\\nApakah Anda Ingin Mengonfirmasi Form Tersebut?";
								$config['link'] = $this->convertPDF($sess->id_rec_h);

								if($sess->on_approval == 1){
									$config['button'] = array(
										[
											"id" => "1",
											"title" => "Konfirmasi"
										],
										[
											"id" => "2",
											"title" => "Anulir"
										]
									);
								}else{
									$config['button'] = array(
										[
											"id" => "1",
											"title" => "Konfirmasi"
										],
										[
											"id" => "2",
											"title" => "Anulir / Batalkan"
										]
									);
								}

								$this->send_wa("preview", $to, $config);
							}else{
								$config['msg'] = "Silahkan Melihat Pratinjau Dari Form yang Sudah Diisi Pada Form Dibawah Ini:\\n\\nhttps://cpipga.com/form/" . $sess->id_rec_h . "\\n\\nApakah Anda Ingin Mengonfirmasi Form Tersebut?";
	
								if($sess->on_approval == 1){
									$config['button'] = array(
										[
											"id" => "1",
											"title" => "Konfirmasi"
										],
										[
											"id" => "2",
											"title" => "Anulir"
										]
									);
								}else{
									$config['button'] = array(
										[
											"id" => "1",
											"title" => "Konfirmasi"
										],
										[
											"id" => "2",
											"title" => "Anulir / Batalkan"
										]
									);
								}
	
								$this->send_wa("button", $to, $config);
							}
						}
					}else if($result['status'] == "hold"){
						$config['msg'] = $result['result'];

						if($pertanyaan[0]->as_caption){
							$caption = $this->form->getCaptionForm($sess->id_rec_h)->row();

							$caption_form = $caption->caption_form;

							if($caption_form){
								$caption_form .= " | " . $result['sub_result'];
							}else{
								$caption_form .= $result['sub_result'];
							}

							$updateCaption = $this->form->updateCaptionRecH($sess->id_rec_h, $caption_form);
						}

						$id_rec_d = $this->form->getIDRecD($sess->id_rec_h, $sess->id_pertanyaan)->row();

						// $opsi_jawaban = $this->form->getOpsiJawabanValue($result['id_opsi'])->result();
						// $resultUpdate = $this->form->updateValueRecordFormD($id_rec_d->id_rec_form_d, $opsi_jawaban[0]->opsi_jawaban);

						$resultUpdate = $this->form->updateValueRecordFormD($id_rec_d->id_rec_form_d, $result['id_opsi']);

						$update_sess = $this->form->updateSession($sess->id_session, $sess->id_form, $sess->id_rec_h, $sess->id_pertanyaan, $result['id_opsi'], $sess->seq_app, $sess->iterasi, $sess->on_approval, 1.1);

						$this->send_wa("text", $to, $config);
					}
				}else if($sess->status == 1.1){
					$id_rec_d = $this->form->getIDRecDHasValue($sess->id_rec_h, $sess->id_pertanyaan)->row();

					$resultUpdate = $this->form->updateSubValueRecordFormD($id_rec_d->id_rec_form_d, $message);

					$checkQuestion = $this->form->checkNextQuestion($sess->id_form, $sess->id_rec_h, $sess->on_approval, $sess->iterasi, $sess->seq_app)->result();

					$caption = $this->form->getCaptionForm($sess->id_rec_h)->row();

					$caption_form = $caption->caption_form;

					$caption_form .= " - " . $message;

					$updateCaption = $this->form->updateCaptionRecH($sess->id_rec_h, $caption_form);

					if($checkQuestion[0]->status == "NO"){
						$seq_next = (int)$checkQuestion[0]->jawaban + 1;

						$nextQuestion = $this->form->getPertanyaanBySeq($sess->id_form, $seq_next, $sess->seq_app, $sess->on_approval, $sess->iterasi)->result();

						$config['msg'] = $nextQuestion[0]->pertanyaan;

						if($nextQuestion[0]->tipe_jawaban == 'date'){
							$config['msg'] .= "\\n_Format Jawaban : DD-MM-YYYY (Contoh: 23-10-2024)_";
	
							$this->send_wa("text", $to, $config);
						}else if($nextQuestion[0]->tipe_jawaban == 'datetime'){
							$config['msg'] .= "\\n_Format Jawaban : DD-MM-YYYY JJ:MM (Contoh: 23-10-2024 15:30)_";
	
							$this->send_wa("text", $to, $config);
						}else if($nextQuestion[0]->tipe_jawaban == 'time'){
							$config['msg'] .= "\\n_Format Jawaban : JJ:MM (Contoh: 15:30)_";
	
							$this->send_wa("text", $to, $config);
						}else if($nextQuestion[0]->tipe_jawaban == 'list'){
							$config['msg'] .= "\\n_Penulisan List Jawaban Bisa Dipisah Menggunakan Karakter '|'_\\n_(Contoh: Nama 1|Nama 2|Nama 3)_";
		
							$this->send_wa("text", $to, $config);
						}else if($nextQuestion[0]->tipe_jawaban == 'multi_option'){
							$config['msg'] .= "\\n_Jawaban Bisa Dipilih Lebih dari 1 (Contoh Format Jawaban : 1,2,3,4,5) *Jangan Gunakan Spasi*_";

							$opsi = $this->form->getOpsiJawaban($nextQuestion[0]->id_pertanyaan)->result();

							$config['msg'] .= "\\n";
							
							foreach($opsi as $o){
								$config['msg'] .= "\\n" . $o->seq_jawaban . ". " . $o->opsi_jawaban;
							}
	
							$this->send_wa("text", $to, $config);
						}else if($nextQuestion[0]->tipe_jawaban == 'single_option'){
							$opsi = $this->form->getOpsiJawaban($nextQuestion[0]->id_pertanyaan)->result();
	
							foreach($opsi as $o){
								$config = [
									"id" => $o->id_opsi,
									"title" => $o->opsi_jawaban,
									"description" => "   "
								];
		
								array_push($lists, $config);
							}

							$config['lists'] = $lists;
							$config['title_lista'] = "List Jawaban";

							$this->send_wa("lists", $to, $config);
						}else if($nextQuestion[0]->tipe_jawaban == 'button'){
							$opsi = $this->form->getOpsiJawaban($nextQuestion[0]->id_pertanyaan)->result();

							// $config['button'] = array(
							// 	[
							// 		"id" => "1",
							// 		"title" => "Konfirmasi"
							// 	],
							// 	[
							// 		"id" => "2",
							// 		"title" => "Anulir / Batalkan"
							// 	]
							// );
	
							foreach($opsi as $o){
								$data = [
									"id" => $o->id_opsi,
									"title" => $o->opsi_jawaban
								];
		
								array_push($lists, $data);
							}

							// $config['msg'] = $lists;
							$config['button'] = $lists;
							// $config['title_lists'] = "List Jawaban";
	
							$this->send_wa("button", $to, $config);
						}else{
							$this->send_wa("text", $to, $config);
						}

						$rec_d = $this->form->recordFormD($sess->id_rec_h, $nextQuestion[0]->id_pertanyaan);

						$update_sess = $this->form->updateSession($sess->id_session, $sess->id_form, $sess->id_rec_h, $nextQuestion[0]->id_pertanyaan, 0, $sess->seq_app, $sess->iterasi, $sess->on_approval, 1);
					}else{
						$update_sess = $this->form->updateSession($sess->id_session, $sess->id_form, $sess->id_rec_h, 0, 0, $sess->seq_app, $sess->iterasi, $sess->on_approval, 2);

						$no_form = $this->form->getNoForm($sess->id_rec_h)->result();

						// $config['msg'] = "Silahkan Melihat Pratinjau Dari Form yang Sudah Diisi Pada Form Dibawah Ini:\\n\\nhttps://cpipga.com/form/" . $no_form[0]->no_form . "\\n\\nApakah Anda Ingin Mengonfirmasi Form Tersebut?";

						// $config['button'] = array(
						// 	[
						// 		"id" => "1",
						// 		"title" => "Konfirmasi"
						// 	],
						// 	[
						// 		"id" => "2",
						// 		"title" => "Anulir / Batalkan"
						// 	]
						// );

						// $this->send_wa("button", $to, $config);

						$config['msg'] = "Silahkan Melihat Pratinjau Dari Form yang Sudah Diisi Pada Form Tertaut Di Pesan Ini\\n\\nApakah Anda Ingin Mengonfirmasi Form Tersebut?";
						$config['link'] = $this->convertPDF($sess->id_rec_h);

						$config['button'] = array(
							[
								"id" => "1",
								"title" => "Konfirmasi"
							],
							[
								"id" => "2",
								"title" => "Anulir / Batalkan"
							]
						);

						$this->send_wa("preview", $to, $config);
					}
				}else if($sess->status == 1.2){
					$pertanyaan = $this->form->getPertanyaan($sess->id_pertanyaan)->result();

					$file_now = 0;

					if($pertanyaan[0]->tipe_jawaban == "single_option" || $pertanyaan[0]->tipe_jawaban == "button"){
						$message = $action_id;
					}else if($pertanyaan[0]->tipe_jawaban == "picture" || $pertanyaan[0]->tipe_jawaban == "document"){
						$message = $file;
					}

					$result = $this->check_input($pertanyaan[0]->tipe_jawaban, $message, $sess->id_pertanyaan, $sender);

					if(!$result['status'] || $result['status'] == 'error'){
						$config['msg'] = "*Jawaban Tidak Benar*\\n" . $pertanyaan[0]->pertanyaan;

						if($pertanyaan[0]->tipe_jawaban == 'date'){
							$config['msg'] .= "\\n_Format Jawaban : DD-MM-YYYY (Contoh: 23-10-2024)_";
	
							$this->send_wa("text", $to, $config);
						}else if($pertanyaan[0]->tipe_jawaban == 'datetime'){
							$config['msg'] .= "\\n_Format Jawaban : DD-MM-YYYY JJ:MM (Contoh: 23-10-2024 15:30)_";
	
							$this->send_wa("text", $to, $config);
						}else if($pertanyaan[0]->tipe_jawaban == 'time'){
							$config['msg'] .= "\\n_Format Jawaban : JJ:MM (Contoh: 15:30)_";
	
							$this->send_wa("text", $to, $config);
						}else if($pertanyaan[0]->tipe_jawaban == 'list'){
							$config['msg'] .= "\\n_Penulisan List Jawaban Bisa Dipisah Menggunakan Karakter '|'_\\n_(Contoh: Nama 1|Nama 2|Nama 3)_";
		
							$this->send_wa("text", $to, $config);
						}else if($pertanyaan[0]->tipe_jawaban == 'multi_option'){
							$config['msg'] .= "\\n_Jawaban Bisa Dipilih Lebih dari 1 (Contoh Format Jawaban : 1,2,3,4,5) *Jangan Gunakan Spasi*_";

							$opsi = $this->form->getOpsiJawaban($pertanyaan[0]->id_pertanyaan)->result();

							$config['msg'] .= "\\n";
							
							foreach($opsi as $o){
								$config['msg'] .= "\\n" . $o->seq_jawaban . ". " . $o->opsi_jawaban;
							}
	
							$this->send_wa("text", $to, $config);
						}else if($pertanyaan[0]->tipe_jawaban == 'single_option'){
							$opsi = $this->form->getOpsiJawaban($pertanyaan[0]->id_pertanyaan)->result();
	
							foreach($opsi as $o){
								$data = [
									"id" => $o->id_opsi,
									"title" => $o->opsi_jawaban,
									"description" => "   "
								];
		
								array_push($lists, $data);
							}

							// $config['msg'] = $lists;
							$config['lists'] = $lists;
							$config['title_lists'] = "List Jawaban";
	
							$this->send_wa("lists", $to, $config);
						}else if($pertanyaan[0]->tipe_jawaban == 'button'){
							$opsi = $this->form->getOpsiJawaban($pertanyaan[0]->id_pertanyaan)->result();
	
							foreach($opsi as $o){
								$data = [
									"id" => $o->id_opsi,
									"title" => $o->opsi_jawaban
								];
		
								array_push($lists, $data);
							}

							$config['button'] = $lists;
	
							$this->send_wa("button", $to, $config);
						}else{
							$this->send_wa("text", $to, $config);
						}
					}else if($result['status'] == "answer"){
						$id_rec_d = $this->form->getIDRecD($sess->id_rec_h, $sess->id_pertanyaan)->row();

						$send_data = $result['result'];

						$resultUpdate = $this->form->updateValueRecordFormD($id_rec_d->id_rec_form_d, $send_data);

						$checkQuestion = $this->form->checkNextQuestion($sess->id_form, $sess->id_rec_h, $sess->on_approval, $sess->iterasi, $sess->seq_app)->result();

						if($checkQuestion[0]->status == "NO"){
							$seq_next = (int)$checkQuestion[0]->jawaban + 1;

							$nextQuestion = $this->form->getPertanyaanBySeq($sess->id_form, $seq_next, $sess->seq_app, $sess->on_approval, $sess->iterasi)->result();

							if(strpos($nextQuestion[0]->seq, ".")){
								$config['msg'] = $nextQuestion[0]->pertanyaan;
	
								if($nextQuestion[0]->tipe_jawaban == 'date'){
									$config['msg'] .= "\\n_Format Jawaban : DD-MM-YYYY (Contoh: 23-10-2024)_";
			
									$this->send_wa("text", $to, $config);
								}else if($nextQuestion[0]->tipe_jawaban == 'datetime'){
									$config['msg'] .= "\\n_Format Jawaban : DD-MM-YYYY JJ:MM (Contoh: 23-10-2024 15:30)_";
			
									$this->send_wa("text", $to, $config);
								}else if($nextQuestion[0]->tipe_jawaban == 'time'){
									$config['msg'] .= "\\n_Format Jawaban : JJ:MM (Contoh: 15:30)_";
			
									$this->send_wa("text", $to, $config);
								}else if($nextQuestion[0]->tipe_jawaban == 'list'){
									$config['msg'] .= "\\n_Penulisan List Jawaban Bisa Dipisah Menggunakan Karakter '|'_\\n_(Contoh: Nama 1|Nama 2|Nama 3)_";
				
									$this->send_wa("text", $to, $config);
								}else if($nextQuestion[0]->tipe_jawaban == 'multi_option'){
									$config['msg'] .= "\\n_Jawaban Bisa Dipilih Lebih dari 1 (Contoh Format Jawaban : 1,2,3,4,5) *Jangan Gunakan Spasi*_";
	
									$opsi = $this->form->getOpsiJawaban($nextQuestion[0]->id_pertanyaan)->result();
	
									$config['msg'] .= "\\n";
									
									foreach($opsi as $o){
										$config['msg'] .= "\\n" . $o->seq_jawaban . ". " . $o->opsi_jawaban;
									}
			
									$this->send_wa("text", $to, $config);
								}else if($nextQuestion[0]->tipe_jawaban == 'single_option'){
									$opsi = $this->form->getOpsiJawaban($nextQuestion[0]->id_pertanyaan)->result();
			
									foreach($opsi as $o){
										$data = [
											"id" => $o->id_opsi,
											"title" => $o->opsi_jawaban,
											"description" => "   "
										];
				
										array_push($lists, $data);
									}
	
									$config['lists'] = $lists;
									$config['title_lists'] = "List Jawaban";
			
									$this->send_wa("lists", $to, $config);
								}else if($nextQuestion[0]->tipe_jawaban == 'button'){
									$opsi = $this->form->getOpsiJawaban($nextQuestion[0]->id_pertanyaan)->result();
			
									foreach($opsi as $o){
										$data = [
											"id" => $o->id_opsi,
											"title" => $o->opsi_jawaban
										];
				
										array_push($lists, $data);
									}
		
									$config['button'] = $lists;
			
									$this->send_wa("button", $to, $config);
								}else{
									$this->send_wa("text", $to, $config);
								}
	
								$rec_d = $this->form->recordFormD($sess->id_rec_h, $nextQuestion[0]->id_pertanyaan);

								$update_sess = $this->form->updateSession($sess->id_session, $sess->id_form, $sess->id_rec_h, $nextQuestion[0]->id_pertanyaan, 0, $sess->seq_app, $sess->iterasi, $sess->on_approval, 1.2);
							}else{
								$config['msg'] = "Apakah Masih Ada Data Yang Ingin Ditambahkan ?";

								$config['button'] = array(
									[
										"id" => "1",
										"title" => "Iya"
									],
									[
										"id" => "2",
										"title" => "Tidak"
									]
								);
								
								$this->send_wa("button", $to, $config);

								$update_sess = $this->form->updateSession($sess->id_session, $sess->id_form, $sess->id_rec_h, $sess->id_pertanyaan, 0, $sess->seq_app, $sess->iterasi, $sess->on_approval, 1.22);
							}
						}else{
							$update_sess = $this->form->updateSession($sess->id_session, $sess->id_form, $sess->id_rec_h, 0, 0, $sess->seq_app, $sess->iterasi, $sess->on_approval, 2);

							$no_form = $this->form->getNoForm($sess->id_rec_h)->row();

							$config['msg'] = "Silahkan Melihat Pratinjau Dari Form yang Sudah Diisi Pada Form Tertaut Di Pesan Ini\\n\\nApakah Anda Ingin Mengonfirmasi Form Tersebut?";
							$config['link'] = $this->convertPDF($sess->id_rec_h);

							if($sess->on_approval == 1){
								$config['button'] = array(
									[
										"id" => "1",
										"title" => "Konfirmasi"
									],
									[
										"id" => "2",
										"title" => "Anulir"
									]
								);
							}else{
								$config['button'] = array(
									[
										"id" => "1",
										"title" => "Konfirmasi"
									],
									[
										"id" => "2",
										"title" => "Anulir / Batalkan"
									]
								);
							}

							$this->send_wa("preview", $to, $config);

						}
					}else if($result['status'] == "hold"){
						$config['msg'] = $result['result'];

						if($pertanyaan[0]->as_caption){
							$caption = $this->form->getCaptionForm($sess->id_rec_h)->row();

							$caption_form = $caption->caption_form;

							if($caption_form){
								$caption_form .= " | " . $result['sub_result'];
							}else{
								$caption_form .= $result['sub_result'];
							}

							$updateCaption = $this->form->updateCaptionRecH($sess->id_rec_h, $caption_form);
						}

						$id_rec_d = $this->form->getIDRecD($sess->id_rec_h, $sess->id_pertanyaan)->row();

						$resultUpdate = $this->form->updateValueRecordFormD($id_rec_d->id_rec_form_d, $result['id_opsi']);

						$update_sess = $this->form->updateSession($sess->id_session, $sess->id_form, $sess->id_rec_h, $sess->id_pertanyaan, $result['id_opsi'], $sess->seq_app, $sess->iterasi, $sess->on_approval, 1.21);

						$this->send_wa("text", $to, $config);
					}
				}else if($sess->status == 1.21){

				}else if($sess->status == 1.22){
					if($action_id == 1){
						$last_quest = $this->form->getPertanyaan($sess->id_pertanyaan)->result();

						$break_seq = explode(".", $last_quest[0]->seq);

						$seq_reset = $break_seq[0] . ".1";

						$pertanyaan_loop = $this->form->getPertanyaanBySeqNumber($sess->id_form, $seq_reset)->result();

						$config['msg'] = $pertanyaan_loop[0]->pertanyaan;

						if($pertanyaan_loop[0]->tipe_jawaban == 'date'){
							$config['msg'] .= "\\n_Format Jawaban : DD-MM-YYYY (Contoh: 23-10-2024)_";
	
							$this->send_wa("text", $to, $config);
						}else if($pertanyaan_loop[0]->tipe_jawaban == 'datetime'){
							$config['msg'] .= "\\n_Format Jawaban : DD-MM-YYYY JJ:MM (Contoh: 23-10-2024 15:30)_";
	
							$this->send_wa("text", $to, $config);
						}else if($pertanyaan_loop[0]->tipe_jawaban == 'time'){
							$config['msg'] .= "\\n_Format Jawaban : JJ:MM (Contoh: 15:30)_";
	
							$this->send_wa("text", $to, $config);
						}else if($pertanyaan_loop[0]->tipe_jawaban == 'list'){
							$config['msg'] .= "\\n_Penulisan List Jawaban Bisa Dipisah Menggunakan Karakter '|'_\\n_(Contoh: Nama 1|Nama 2|Nama 3)_";
		
							$this->send_wa("text", $to, $config);
						}else if($pertanyaan_loop[0]->tipe_jawaban == 'multi_option'){
							$config['msg'] .= "\\n_Jawaban Bisa Dipilih Lebih dari 1 (Contoh Format Jawaban : 1,2,3,4,5) *Jangan Gunakan Spasi*_";

							$opsi = $this->form->getOpsiJawaban($pertanyaan_loop[0]->id_pertanyaan)->result();

							$config['msg'] .= "\\n";
							
							foreach($opsi as $o){
								$config['msg'] .= "\\n" . $o->seq_jawaban . ". " . $o->opsi_jawaban;
							}
	
							$this->send_wa("text", $to, $config);
						}else if($pertanyaan_loop[0]->tipe_jawaban == 'single_option'){
							$opsi = $this->form->getOpsiJawaban($pertanyaan_loop[0]->id_pertanyaan)->result();
	
							foreach($opsi as $o){
								$data = [
									"id" => $o->id_opsi,
									"title" => $o->opsi_jawaban,
									"description" => "   "
								];
		
								array_push($lists, $data);
							}

							$config['lists'] = $lists;
							$config['title_lists'] = "List Jawaban";
	
							$this->send_wa("lists", $to, $config);
						}else if($pertanyaan_loop[0]->tipe_jawaban == 'button'){
							$opsi = $this->form->getOpsiJawaban($pertanyaan_loop[0]->id_pertanyaan)->result();
	
							foreach($opsi as $o){
								$data = [
									"id" => $o->id_opsi,
									"title" => $o->opsi_jawaban
								];
		
								array_push($lists, $data);
							}

							$config['button'] = $lists;
	
							$this->send_wa("button", $to, $config);
						}else{
							$this->send_wa("text", $to, $config);
						}

						$rec_d = $this->form->recordFormD($sess->id_rec_h, $pertanyaan_loop[0]->id_pertanyaan);

						$update_sess = $this->form->updateSession($sess->id_session, $sess->id_form, $sess->id_rec_h, $pertanyaan_loop[0]->id_pertanyaan, 0, $sess->seq_app, $sess->iterasi, $sess->on_approval, 1.2);
					}else if($action_id == 2){
						$checkQuestion = $this->form->checkNextQuestion($sess->id_form, $sess->id_rec_h, $sess->on_approval, $sess->iterasi, $sess->seq_app)->result();

						if($checkQuestion[0]->status == "NO"){
							$seq_next = (int)$checkQuestion[0]->jawaban + 1;

							$nextQuestion = $this->form->getPertanyaanBySeq($sess->id_form, $seq_next, $sess->seq_app, $sess->on_approval, $sess->iterasi)->result();

							$config['msg'] = $nextQuestion[0]->pertanyaan;
	
							if($nextQuestion[0]->tipe_jawaban == 'date'){
								$config['msg'] .= "\\n_Format Jawaban : DD-MM-YYYY (Contoh: 23-10-2024)_";
		
								$this->send_wa("text", $to, $config);
							}else if($nextQuestion[0]->tipe_jawaban == 'datetime'){
								$config['msg'] .= "\\n_Format Jawaban : DD-MM-YYYY JJ:MM (Contoh: 23-10-2024 15:30)_";
		
								$this->send_wa("text", $to, $config);
							}else if($nextQuestion[0]->tipe_jawaban == 'time'){
								$config['msg'] .= "\\n_Format Jawaban : JJ:MM (Contoh: 15:30)_";
		
								$this->send_wa("text", $to, $config);
							}else if($nextQuestion[0]->tipe_jawaban == 'list'){
								$config['msg'] .= "\\n_Penulisan List Jawaban Bisa Dipisah Menggunakan Karakter '|'_\\n_(Contoh: Nama 1|Nama 2|Nama 3)_";
			
								$this->send_wa("text", $to, $config);
							}else if($nextQuestion[0]->tipe_jawaban == 'multi_option'){
								$config['msg'] .= "\\n_Jawaban Bisa Dipilih Lebih dari 1 (Contoh Format Jawaban : 1,2,3,4,5) *Jangan Gunakan Spasi*_";

								$opsi = $this->form->getOpsiJawaban($nextQuestion[0]->id_pertanyaan)->result();

								$config['msg'] .= "\\n";
								
								foreach($opsi as $o){
									$config['msg'] .= "\\n" . $o->seq_jawaban . ". " . $o->opsi_jawaban;
								}
		
								$this->send_wa("text", $to, $config);
							}else if($nextQuestion[0]->tipe_jawaban == 'single_option'){
								$opsi = $this->form->getOpsiJawaban($nextQuestion[0]->id_pertanyaan)->result();
		
								foreach($opsi as $o){
									$data = [
										"id" => $o->id_opsi,
										"title" => $o->opsi_jawaban,
										"description" => "   "
									];
			
									array_push($lists, $data);
								}

								$config['lists'] = $lists;
								$config['title_lists'] = "List Jawaban";
		
								$this->send_wa("lists", $to, $config);
							}else if($nextQuestion[0]->tipe_jawaban == 'button'){
								$opsi = $this->form->getOpsiJawaban($nextQuestion[0]->id_pertanyaan)->result();
		
								foreach($opsi as $o){
									$data = [
										"id" => $o->id_opsi,
										"title" => $o->opsi_jawaban
									];
			
									array_push($lists, $data);
								}
	
								$config['button'] = $lists;
		
								$this->send_wa("button", $to, $config);
							}else{
								$this->send_wa("text", $to, $config);
							}

							$rec_d = $this->form->recordFormD($sess->id_rec_h, $nextQuestion[0]->id_pertanyaan);

							$update_sess = $this->form->updateSession($sess->id_session, $sess->id_form, $sess->id_rec_h, $nextQuestion[0]->id_pertanyaan, 0, $sess->seq_app, $sess->iterasi, $sess->on_approval, 1);
						}else{
							$update_sess = $this->form->updateSession($sess->id_session, $sess->id_form, $sess->id_rec_h, 0, 0, $sess->seq_app, $sess->iterasi, $sess->on_approval, 2);

							$no_form = $this->form->getNoForm($sess->id_rec_h)->row();

							$config['msg'] = "Silahkan Melihat Pratinjau Dari Form yang Sudah Diisi Pada Form Tertaut Di Pesan Ini\\n\\nApakah Anda Ingin Mengonfirmasi Form Tersebut?";
							$config['link'] = $this->convertPDF($sess->id_rec_h);

							if($sess->on_approval == 1){
								$config['button'] = array(
									[
										"id" => "1",
										"title" => "Konfirmasi"
									],
									[
										"id" => "2",
										"title" => "Anulir"
									]
								);
							}else{
								$config['button'] = array(
									[
										"id" => "1",
										"title" => "Konfirmasi"
									],
									[
										"id" => "2",
										"title" => "Anulir / Batalkan"
									]
								);
							}

							$this->send_wa("preview", $to, $config);

						}
					}
				}else if($sess->status == 2){
					if($action_id == 1){
						$deadMessage = false;

						if($sess->id_form == 9){
							$nopeg = $this->matt->getUser($sender)->result();
							$DL = $this->matt->checkDL($nopeg[0]->nopeg)->result();

							if($DL){
								$startdl = new DateTime($DL[0]->start);
								$enddl = new DateTime($DL[0]->end);
								$timenow = new DateTime(date('Y-m-d H:i:s'));
	
								if($timenow >= $startdl && $timenow <= $enddl){
									$dataDL = $this->matt->getValueData($sess->id_rec_h)->result();

									$statusDL = "";
									$taskDL = "";
									$pictureDL = "";

									foreach($dataDL as $dd){
										if($dd->id_pertanyaan == 97){
											$statusDL = $dd->value;
										}else if($dd->id_pertanyaan == 98){
											$pictureDL = $dd->value;
										}else if($dd->id_pertanyaan == 99){
											$taskDL = $dd->value;
										}
									}

									$saveDL = $this->matt->insertDataDL($DL[0]->id_spl, $statusDL, $taskDL, $pictureDL, $nopeg[0]->nopeg, $DL[0]->plant);
								}else{
									$config['msg'] = "Anda Sudah Melewati Waktu Absensi";

									$this->send_wa("text", $to, $config);

									$deadMessage = true;
								}
							}
						}

						if($sess->id_form == 7){
							$kkinfo = $this->mkk->kkInfo($sess->id_rec_h)->result();
							$kkkorban = $this->mkk->kkKorban($sess->id_rec_h)->result();
							$pelapor = $this->mkk->pelapor($sender)->result();
							
							//Penulisan Pelapor
							$config['dataPelapor'] = "- " . $pelapor[0]->nama . " (" . $pelapor[0]->nopeg . ")\\\\n- " . $pelapor[0]->vendor . "/" . $pelapor[0]->department . "/" . $pelapor[0]->plant;

							//Penulisan Kronologi Kejadian
							$config['dataKronologi'] = $kkinfo[0]->kronologi;

							//Penulisan Tanggal Kejadian
							$date = explode("-", $kkinfo[0]->tanggal);
							$day = str_pad($date[0],2,"0", STR_PAD_LEFT);
							$month = $date[1];
							$year = $date[2];

							$datecode = date_create($year . "-" . $month . "-" . $day);

							$hariCode = date_format($datecode,"N");

							if($hariCode == 1){
								$hariCode = "Senin";
							}elseif($hariCode == 2){
								$hariCode = "Selasa";
							}elseif($hariCode == 3){
								$hariCode = "Rabu";
							}elseif($hariCode == 4){
								$hariCode = "Kamis";
							}elseif($hariCode == 5){
								$hariCode = "Jumat";
							}elseif($hariCode == 6){
								$hariCode = "Sabtu";
							}elseif($hariCode == 7){
								$hariCode = "Minggu";
							}

							$monthCode = date_format($datecode,"n");

							if($monthCode == 1){
								$monthCode = "Januari";
							}elseif($monthCode == 2){
								$monthCode = "Februari";
							}elseif($monthCode == 3){
								$monthCode = "Maret";
							}elseif($monthCode == 4){
								$monthCode = "April";
							}elseif($monthCode == 5){
								$monthCode = "Mei";
							}elseif($monthCode == 6){
								$monthCode = "Juni";
							}elseif($monthCode == 7){
								$monthCode = "Juli";
							}elseif($monthCode == 8){
								$monthCode = "Agustus";
							}elseif($monthCode == 9){
								$monthCode = "September";
							}elseif($monthCode == 10){
								$monthCode = "Oktober";
							}elseif($monthCode == 11){
								$monthCode = "November";
							}elseif($monthCode == 12){
								$monthCode = "Desember";
							}

							$config['tanggalKejadian'] = $hariCode . ", " . date_format($datecode,"d") . " " . $monthCode . " " . date_format($datecode,"Y");

							//Penulisan Korban
							$korban = "";

							$newLine = false;
							foreach($kkkorban as $kk){
								if($newLine){
									$korban .= "\\\\n";
									$newLine = false;
								}

								if($kk->seq == 2.1){
									$korban .= "- Nama: " . $kk->value . "\\\\n";
								}elseif($kk->seq == 2.2){
									$korban .= "- Kondisi: " . $kk->value . "\\\\n";
								}elseif($kk->seq == 2.3){
									$korban .= "- Identifikasi: " . $kk->value . "\\\\n";
								}elseif($kk->seq == 2.4){
									$korban .= "- Lokasi Perawatan: " . $kk->value . "\\\\n";
									$newLine = true;
								}
							}

							$config['korban'] = $korban;

							$this->send_wa("notif_kk", "62811319129", $config);
						}

						if($sess->on_approval == 1){
							$update_sess = $this->form->updateSession($sess->id_session, $sess->id_form, $sess->id_rec_h, 0, 0, $sess->seq_app, $sess->iterasi, $sess->on_approval, 10);

							// Get Title Form dan No Form Bedasarkan ID Rec Form H
							$no_form = $this->form->getNoForm($sess->id_rec_h)->row();
							
							/*// Penulisan Pesan Approval
							$config['msg'] = "Apakah Anda Akan Melakukan Konfirmasi Untuk Form: *" . $no_form->title . "*, Dengan Nomor Form: *" . $no_form->no_form . "*\\n\\nPratinjau Form:\\nhttps://cpipga.com/form/" . $no_form->no_form;
								
							// Config Button WA
							$config['button'] = array(
								[
									"id" => "1",
									"title" => "Menyetujui"
								],
								[
									"id" => "2",
									"title" => "Menolak"
								]
							);
							
							// Kirim Pesan WA
							$this->send_wa("button", $to, $config);*/

							$config['msg'] = "Apakah Anda Akan Melakukan Konfirmasi Untuk Form: *" . $no_form->title . "*, Dengan Nomor Form: " . $no_form->no_form . "\\n\\n*Pratinjau Form Tertaut Pada Pesan Ini*";
							$config['link'] = $this->convertPDF($sess->id_rec_h);

							$config['button'] = array(
								[
									"id" => "1",
									"title" => "Menyetujui"
								],
								[
									"id" => "2",
									"title" => "Menolak"
								]
							);

							$this->send_wa("preview", $to, $config);
						}else{
							$createdStatus = $this->form->recordFormHCreated($sess->id_rec_h);
	
							$update_sess = $this->form->updateSession($sess->id_session, $sess->id_form, $sess->id_rec_h, 0, 0, $sess->seq_app, $sess->iterasi, $sess->on_approval, 7);
	
							$app_user = $this->form->getAppUser($sess->id_rec_h)->result();
	
							$msg = "";
	
							$no_form = $this->form->getNoForm($sess->id_rec_h)->result();
	
							if($app_user){
								$config['msg'] = "Form " . $no_form[0]->no_form . " Telah Di kirim Ke Semua User Approval untuk Dilakukan Persetujuan";
	
								$this->send_wa("text", $to, $config);
								
								$formTitle = $this->form->getTitleForm($no_form[0]->no_form)->result();
								
								$contact_user = '62' . ltrim($app_user[0]->no_hp,'0');
	
								$config['id_rec_h'] = $sess->id_rec_h;
								$config['id_form'] = $sess->id_form;
								$config['loop'] = $sess->iterasi;
								$config['title'] = $formTitle[0]->title;
								$config['no_form'] = $no_form[0]->no_form;
								$config['link'] = $this->convertPDF($sess->id_rec_h);
	
								$this->send_wa("approval", $contact_user, $config);
							}else{
								if(!$deadMessage){
									$config['msg'] = "Form " . $no_form[0]->no_form . " Telah Berhasil Disimpan";

									var_dump($config['msg']);
		
									$this->send_wa("text", $to, $config);
								}
							}
	
							$update_sess = $this->form->updateSession($sess->id_session, 0, 0, 0, 0, $sess->seq_app, $sess->iterasi, $sess->on_approval, 99);
						}
					}else if($action_id == 2){
						$update_sess = $this->form->updateSession($sess->id_session, $sess->id_form, $sess->id_rec_h, 0, 0, $sess->seq_app, $sess->iterasi, $sess->on_approval, 3);

						if($sess->on_approval == 1){
							$config['msg'] = "Apakah Anda Mau Melakukan Anulir Jawaban Form Atau Pratinjau Pengisian Form ?";
						}else{
							$config['msg'] = "Apakah Anda Mau Melakukan Anulir Jawaban Form Atau Membatalkan Pengisian Form ?";
						}
						

						if($sess->on_approval == 1){
							$config['button'] = array(
								[
									"id" => "1",
									"title" => "Anulir Form"
								],
								[
									"id" => "3",
									"title" => "Pratinjau Form"
								]
							);
						}else{
							$config['button'] = array(
								[
									"id" => "1",
									"title" => "Anulir Form"
								],
								[
									"id" => "2",
									"title" => "Batalkan Form"
								],
								[
									"id" => "3",
									"title" => "Pratinjau Form"
								]
							);
						}


						$this->send_wa("button", $to, $config);
					}
				}else if($sess->status == 3){
					if($action_id == 1){
						$update_sess = $this->form->updateSession($sess->id_session, $sess->id_form, $sess->id_rec_h, 0, 0, $sess->seq_app, $sess->iterasi, $sess->on_approval, 5);

						$allPertanyaan = $this->form->getAllPertanyaan($sess->id_form, $sess->seq_app, $sess->on_approval, $sess->iterasi)->result();

						$config['msg'] = "Silahkan Memilih Nomor Pertanyaan yang Ingin Dirubah:\\n";

						$ctr = 1;

						foreach($allPertanyaan as $ap){
							// $config['msg'] .= "\\n" . $ap->seq . ". " . $ap->pertanyaan;
							$config['msg'] .= "\\n" . $ctr . ". " . $ap->pertanyaan;
							$ctr++;
						}

						$config['msg'] .= "\\n\\n" . "99. Kembali Pratinjau Form";

						$this->send_wa("text", $to, $config);
					}else if($action_id == 2){
						$update_sess = $this->form->updateSession($sess->id_session, $sess->id_form, $sess->id_rec_h, 0, 0, $sess->seq_app, $sess->iterasi, $sess->on_approval, 4);

						$no_form = $this->form->getNoForm($sess->id_rec_h)->result();

						$msg = "Form : _*" . $no_form[0]->no_form . "*_ Berhasil Dibatalkan.";

						$update_sess = $this->form->updateSession($sess->id_session, 0, 0, 0, 0, $sess->seq_app, $sess->iterasi, $sess->on_approval, 99);

						$this->send_wa_text($to, $msg);
					}else if($action_id == 3){
						$update_sess = $this->form->updateSession($sess->id_session, $sess->id_form, $sess->id_rec_h, 0, 0, $sess->seq_app, $sess->iterasi, $sess->on_approval, 2);

						$no_form = $this->form->getNoForm($sess->id_rec_h)->result();

						// $config['msg'] = "Silahkan Melihat Pratinjau Dari Form yang Sudah Diisi Pada Form Dibawah Ini:\\n\\nhttps://cpipga.com/form/" . $no_form[0]->no_form . "\\n\\nApakah Anda Ingin Mengonfirmasi Form Tersebut?";

						// $config['button'] = array(
						// 	[
						// 		"id" => "1",
						// 		"title" => "Konfirmasi"
						// 	],
						// 	[
						// 		"id" => "2",
						// 		"title" => "Anulir / Batalkan"
						// 	]
						// );

						// $this->send_wa("button", $to, $config);

						$config['msg'] = "Silahkan Melihat Pratinjau Dari Form yang Sudah Diisi Pada Form Tertaut Di Pesan Ini\\n\\nApakah Anda Ingin Mengonfirmasi Form Tersebut?";
						$config['link'] = $this->convertPDF($sess->id_rec_h);

						if($sess->on_approval == 1){
							$config['button'] = array(
								[
									"id" => "1",
									"title" => "Konfirmasi"
								],
								[
									"id" => "2",
									"title" => "Anulir"
								]
							);
						}else{
							$config['button'] = array(
								[
									"id" => "1",
									"title" => "Konfirmasi"
								],
								[
									"id" => "2",
									"title" => "Anulir / Batalkan"
								]
							);
						}

						$this->send_wa("preview", $to, $config);
					}
				}else if($sess->status == 5){
					if($message == 99){
						$update_sess = $this->form->updateSession($sess->id_session, $sess->id_form, $sess->id_rec_h, 0, 0, $sess->seq_app, $sess->iterasi, $sess->on_approval, 2);

						$no_form = $this->form->getNoForm($sess->id_rec_h)->result();

						// $config['msg'] = "Silahkan Melihat Pratinjau Dari Form yang Sudah Diisi Pada Form Dibawah Ini:\\n\\nhttps://cpipga.com/form/" . $no_form[0]->no_form . "\\n\\nApakah Anda Ingin Mengonfirmasi Form Tersebut?";

						// $config['button'] = array(
						// 	[
						// 		"id" => "1",
						// 		"title" => "Konfirmasi"
						// 	],
						// 	[
						// 		"id" => "2",
						// 		"title" => "Anulir / Batalkan"
						// 	]
						// );

						// $this->send_wa("button", $to, $config);

						$config['msg'] = "Silahkan Melihat Pratinjau Dari Form yang Sudah Diisi Pada Form Tertaut Di Pesan Ini\\n\\nApakah Anda Ingin Mengonfirmasi Form Tersebut?";
						$config['link'] = $this->convertPDF($sess->id_rec_h);

						$config['button'] = array(
							[
								"id" => "1",
								"title" => "Konfirmasi"
							],
							[
								"id" => "2",
								"title" => "Anulir / Batalkan"
							]
						);

						$this->send_wa("preview", $to, $config);
					}else{
						$pertanyaanNow = $this->form->getPertanyaanBySeq($sess->id_form, $message, $sess->seq_app, $sess->on_approval, $sess->iterasi)->result();

						var_dump($pertanyaanNow);
	
						$jawabanSebelumnya = $this->form->getJawaban($sess->id_rec_h, $pertanyaanNow[0]->id_pertanyaan)->result();
	
						if($pertanyaanNow){
							$config['msg'] = "Jawaban Sebelumnya : _*" . $jawabanSebelumnya[0]->value;

							if($jawabanSebelumnya[0]->sub_value != ""){
								$config['msg'] .= " - " . $jawabanSebelumnya[0]->sub_value;
							}
	
							$config['msg'] .= "*_\\n\\nSilahkan Memilih / Memasukkan Jawaban Yang Baru\\n\\n";

							$pertanyaanEdit = $this->form->getPertanyaan($pertanyaanNow[0]->id_pertanyaan)->result();
	
							$config['msg'] .= $pertanyaanEdit[0]->pertanyaan;

							if($pertanyaanEdit[0]->tipe_jawaban == 'date'){
								$config['msg'] .= "\\n_Format Jawaban : DD-MM-YYYY (Contoh: 23-10-2024)_";
		
								$this->send_wa("text", $to, $config);
							}else if($pertanyaanEdit[0]->tipe_jawaban == 'datetime'){
								$config['msg'] .= "\\n_Format Jawaban : DD-MM-YYYY JJ:MM (Contoh: 23-10-2024 15:30)_";
		
								$this->send_wa("text", $to, $config);
							}else if($pertanyaanEdit[0]->tipe_jawaban == 'time'){
								$config['msg'] .= "\\n_Format Jawaban : JJ:MM (Contoh: 15:30)_";
		
								$this->send_wa("text", $to, $config);
							}else if($pertanyaanEdit[0]->tipe_jawaban == 'list'){
								$config['msg'] .= "\\n_Penulisan List Jawaban Bisa Dipisah Menggunakan Karakter '|'_\\n_(Contoh: Nama 1|Nama 2|Nama 3)_";
			
								$this->send_wa("text", $to, $config);
							}else if($pertanyaanEdit[0]->tipe_jawaban == 'multi_option'){
								$config['msg'] .= "\\n_Jawaban Bisa Dipilih Lebih dari 1 (Contoh Format Jawaban : 1,2,3,4,5) *Jangan Gunakan Spasi*_";
	
								$opsi = $this->form->getOpsiJawaban($pertanyaanEdit[0]->id_pertanyaan)->result();
	
								$config['msg'] .= "\\n";
								
								foreach($opsi as $o){
									$config['msg'] .= "\\n" . $o->seq_jawaban . ". " . $o->opsi_jawaban;
								}
		
								$this->send_wa("text", $to, $config);
							}else if($pertanyaanEdit[0]->tipe_jawaban == 'single_option'){
								$lists = array();

								$opsi = $this->form->getOpsiJawaban($pertanyaanEdit[0]->id_pertanyaan)->result();
		
								foreach($opsi as $o){
									$config = [
										"id" => $o->id_opsi,
										"title" => $o->opsi_jawaban,
										"description" => "   "
									];
			
									array_push($lists, $config);
								}

								$config['msg'] = "Jawaban Sebelumnya : _*" . $jawabanSebelumnya[0]->value;

								if($jawabanSebelumnya[0]->sub_value != ""){
									$config['msg'] .= " - " . $jawabanSebelumnya[0]->sub_value;
								}
		
								$config['msg'] .= "*_\\n\\nSilahkan Memilih / Memasukkan Jawaban Yang Baru\\n\\n";

								$config['lists'] = $lists;
								$config['title_lists'] = "List Jawaban";
		
								$this->send_wa("lists", $to, $config);
							}else if($pertanyaanEdit[0]->tipe_jawaban == 'button'){
								$opsi = $this->form->getOpsiJawaban($pertanyaanEdit[0]->id_pertanyaan)->result();
		
								foreach($opsi as $o){
									$data = [
										"id" => $o->id_opsi,
										"title" => $o->opsi_jawaban
									];
			
									array_push($lists, $data);
								}
	
								// $config['msg'] = $lists;
								$config['button'] = $lists;
								// $config['title_lists'] = "List Jawaban";
		
								$this->send_wa("button", $to, $config);
							}else if($pertanyaanEdit[0]->tipe_jawaban == 'picture' || $pertanyaanEdit[0]->tipe_jawaban == 'picture'){
								$config['msg'] = $pertanyaanEdit[0]->pertanyaan;

								$this->send_wa("text", $to, $config);
							}else{
								$this->send_wa("text", $to, $config);
							}

							$update_sess = $this->form->updateSession($sess->id_session, $sess->id_form, $sess->id_rec_h, $pertanyaanEdit[0]->id_pertanyaan, 0, $sess->seq_app, $sess->iterasi, $sess->on_approval, 6);
						}else{
							$allPertanyaan = $this->form->getAllPertanyaan($sess->id_form, $sess->seq_app, $sess->on_approval, $sess->iterasi)->result();

							$config['msg'] = "Silahkan Memilih Nomor Pertanyaan yang Ingin Dirubah:\\n";

							foreach($allPertanyaan as $ap){
								$config['msg'] .= "\\n" . $ap->seq . ". " . $ap->pertanyaan;
							}

							$config['msg'].= "\\n\\n" . "99. Kembali Pratinjau Form";

							$this->send_wa("text", $to, $config);
						}
					}
				}else if($sess->status == 6){
					$pertanyaan = $this->form->getPertanyaan($sess->id_pertanyaan)->result();

					if($pertanyaan[0]->tipe_jawaban == "single_option" || $pertanyaan[0]->tipe_jawaban == "button"){
						$message = $action_id;
					}else if($pertanyaan[0]->tipe_jawaban == "picture" || $pertanyaan[0]->tipe_jawaban == "document"){
						$message = $file;
					}

					$result = $this->check_input($pertanyaan[0]->tipe_jawaban, $message, $sess->id_pertanyaan, $sender);
					
					if(!$result['status'] || $result['status'] == 'error'){
						$config['msg'] = "Jawaban Tidak Benar\\n" . $pertanyaan[0]->pertanyaan;

						if($pertanyaan[0]->tipe_jawaban == 'date'){
							$config['msg'] .= "\\n_Format Jawaban : DD-MM-YYYY (Contoh: 23-10-2024)_";
	
							$this->send_wa("text", $to, $config);
						}else if($pertanyaan[0]->tipe_jawaban == 'datetime'){
							$config['msg'] .= "\\n_Format Jawaban : DD-MM-YYYY JJ:MM (Contoh: 23-10-2024 15:30)_";
	
							$this->send_wa("text", $to, $config);
						}else if($pertanyaan[0]->tipe_jawaban == 'time'){
							$config['msg'] .= "\\n_Format Jawaban : JJ:MM (Contoh: 15:30)_";
	
							$this->send_wa("text", $to, $config);
						}else if($pertanyaan[0]->tipe_jawaban == 'list'){
							$config['msg'] .= "\\n_Penulisan List Jawaban Bisa Dipisah Menggunakan Karakter '|'_\\n_(Contoh: Nama 1|Nama 2|Nama 3)_";
		
							$this->send_wa("text", $to, $config);
						}else if($pertanyaan[0]->tipe_jawaban == 'multi_option'){
							$config['msg'] .= "\\n_Jawaban Bisa Dipilih Lebih dari 1 (Contoh Format Jawaban : 1,2,3,4,5) *Jangan Gunakan Spasi*_";

							$opsi = $this->form->getOpsiJawaban($pertanyaan[0]->id_pertanyaan)->result();

							$config['msg'] .= "\\n";
							
							foreach($opsi as $o){
								$config['msg'] .= "\\n" . $o->seq_jawaban . ". " . $o->opsi_jawaban;
							}
	
							$this->send_wa("text", $to, $config);
						}else if($pertanyaan[0]->tipe_jawaban == 'single_option'){
							$opsi = $this->form->getOpsiJawaban($pertanyaan[0]->id_pertanyaan)->result();
	
							foreach($opsi as $o){
								$config = [
									"id" => $o->id_opsi,
									"title" => $o->opsi_jawaban,
									"description" => "   "
								];
		
								array_push($lists, $config);
							}

							$config['msg'] = "Jawaban Tidak Benar\\n" . $pertanyaan[0]->pertanyaan;
							$config['lists'] = $lists;
							$config['title_lists'] = "List Jawaban";
	
							$this->send_wa("lists", $to, $config);
						}else if($pertanyaan[0]->tipe_jawaban == 'button'){
							$opsi = $this->form->getOpsiJawaban($pertanyaan[0]->id_pertanyaan)->result();
	
							foreach($opsi as $o){
								$data = [
									"id" => $o->id_opsi,
									"title" => $o->opsi_jawaban
								];
		
								array_push($lists, $data);
							}

							$config['button'] = $lists;
	
							$this->send_wa("button", $to, $config);
						}else{
							$this->send_wa("text", $to, $config);
						}
					}else if($result['status'] == "answer"){
						$id_rec_d = $this->form->getJawaban($sess->id_rec_h, $sess->id_pertanyaan)->result();

						$send_data = $result['result'];

						$allCaption = $this->form->getSeqCaption($sess->id_form, $sess->id_pertanyaan)->result();

						if($allCaption){
							$caption = $this->form->getCaptionForm($sess->id_rec_h)->result();
	
							$caption_form = $caption[0]->caption_form;
	
							$caption_break = explode(" | ", $caption_form);
	
							$caption_break[$allCaption[0]->nomor] = $send_data;
	
							$caption_anulir = implode(" | ", $caption_break);
	
							$updateCaption = $this->form->updateCaptionRecH($sess->id_rec_h, $caption_anulir);
						}

						$resultUpdate = $this->form->updateValueRecordFormD($id_rec_d[0]->id_rec_form_d, $send_data);
						$resultUpdate = $this->form->updateSubValueRecordFormD($id_rec_d[0]->id_rec_form_d, "");

						$update_sess = $this->form->updateSession($sess->id_session, $sess->id_form, $sess->id_rec_h, 0, 0, $sess->seq_app, $sess->iterasi, $sess->on_approval, 5);

						$allPertanyaan = $this->form->getAllPertanyaan($sess->id_form, $sess->seq_app, $sess->on_approval, $sess->iterasi)->result();

						$config['msg'] = "_*Jawaban Berhasil Dirubah*_\\n\\nSilahkan Memilih Nomor Pertanyaan yang Ingin Dirubah:\\n\\n";

						$ctr = 1;

						foreach($allPertanyaan as $ap){
							$config['msg'] .= $ctr . ". " . $ap->pertanyaan . "\\n";
							$ctr++;
						}

						$config['msg'] .= "\\n\\n99. Kembali Pratinjau Form";

						$this->send_wa("text", $to, $config);
					}else if($result['status'] == "hold"){
						$config['msg'] = $result['result'];

						$id_rec_d = $this->form->getJawaban($sess->id_rec_h, $sess->id_pertanyaan)->result();

						$opsi_jawaban = $this->form->getOpsiJawabanValue($result['id_opsi'])->result();

						$allCaption = $this->form->getSeqCaption($sess->id_form, $sess->id_pertanyaan)->result();

						$caption = $this->form->getCaptionForm($sess->id_rec_h)->result();

						$caption_form = $caption[0]->caption_form;

						$caption_break = explode(" | ", $caption_form);

						$caption_break[$allCaption[0]->nomor] = $result['sub_result'];

						$caption_anulir = implode(" | ", $caption_break);

						$updateCaption = $this->form->updateCaptionRecH($sess->id_rec_h, $caption_anulir);

						$resultUpdate = $this->form->updateValueRecordFormD($id_rec_d[0]->id_rec_form_d, $opsi_jawaban[0]->opsi_jawaban);

						$update_sess = $this->form->updateSession($sess->id_session, $sess->id_form, $sess->id_rec_h, $sess->id_pertanyaan, $result['id_opsi'], $sess->seq_app, $sess->iterasi, $sess->on_approval, 6.1);

						$this->send_wa("text", $to, $config);
					}
				}else if($sess->status == 6.1){
					$id_rec_d = $this->form->getJawaban($sess->id_rec_h, $sess->id_pertanyaan)->result();

					$resultUpdate = $this->form->updateSubValueRecordFormD($id_rec_d[0]->id_rec_form_d, $message);

					$update_sess = $this->form->updateSession($sess->id_session, $sess->id_form, $sess->id_rec_h, $pertanyaanNow[0]->id_pertanyaan, 0, $sess->seq_app, $sess->iterasi, $sess->on_approval, 5);

					$allCaption = $this->form->getSeqCaption($sess->id_form, $sess->id_pertanyaan)->result();

					$caption = $this->form->getCaptionForm($sess->id_rec_h)->result();

					$caption_form = $caption[0]->caption_form;

					$caption_break = explode(" | ", $caption_form);

					$caption_break[$allCaption[0]->nomor] = $caption_break[$allCaption[0]->nomor] . " - " . $message;

					$caption_anulir = implode(" | ", $caption_break);

					$updateCaption = $this->form->updateCaptionRecH($sess->id_rec_h, $caption_anulir);

					$allPertanyaan = $this->form->getAllPertanyaan($sess->id_form, $sess->seq_app, $sess->on_approval, $sess->iterasi)->result();

					$config['msg'] = "_*Jawaban Berhasil Dirubah*_\\n\\nSilahkan Memilih Nomor Pertanyaan yang Ingin Dirubah:\\n";
					foreach($allPertanyaan as $ap){
						$config['msg'] .= "\\n" . $ap->seq . ". " . $ap->pertanyaan;
					}

					$config['msg'] .= "\\n\\n99. Kembali Pratinjau Form";

					$this->send_wa("text", $to, $config);
				}else if($sess->status == 10){
					if($action_id == 1){
						$approvalAvail = $this->form->getApprovalAvailable($sess->id_rec_h, $sess->id_user)->result();
						// $totalAppAvail = count($approvalAvail);
	
						// $pertanyaanApp = $this->form->getPertanyaanForApp($sess->id_form, $approvalAvail[0]->seq, $sess->id_rec_h)->result();
						// $totalPertanyaan = count($pertanyaanApp);

						// if($totalPertanyaan > 0){
							// $this->form->recordFormD($sess->id_rec_h, $pertanyaanApp[0]->id_pertanyaan);

							// $update_sess = $this->form->updateSession($sess->id_session, $sess->id_form, $sess->id_rec_h, $pertanyaanApp[0]->id_pertanyaan, 0, $sess->seq_app, $sess->iterasi, $sess->on_approval, 10.1);

							// $config['msg'] = $pertanyaanApp[0]->pertanyaan;

							// $this->send_wa("text", $to, $config);
						// }else{
							if($sess->id_form == 5 && $sess->iterasi == 1 && $sess->seq_app == 6){
								$tingkatResiko = $this->form->getJawabanById($sess->id_rec_h, 40)->row();

								if($tingkatResiko->value == "Tinggi"){
									$extraApp = $this->form->getExtraAPP($sess->id_rec_h, $sess->id_form)->result();

									foreach($extraApp AS $ea){
										$record_data_approval = $this->form->recordApproval($sess->id_rec_h, $ea->id_alur, '', 0, $ea->seq_app, $sess->iterasi);
									}
								}
							}

							$update_sess = $this->form->updateSession($sess->id_session, $sess->id_form, $sess->id_rec_h, 0, 0, $sess->seq_app, $sess->iterasi, $sess->on_approval, 11);


							$config['msg'] = "Silahkan Masukkan Catatan\\n(_Jika Tidak Ada Catatan Bisa Dibalas Dengan '-'_)";

							$this->send_wa("text", $to, $config);
						// }
					}else if($action_id == 2){
						$update_sess = $this->form->updateSession($sess->id_session, $sess->id_form, $sess->id_rec_h, 0, 0, $sess->seq_app, $sess->iterasi, $sess->on_approval, 13);

						$config['msg'] = "Silahkan Masukkan Catatan\\n(_Jika Tidak Ada Catatan Bisa Dibalas Dengan '-'_)";

						$this->send_wa("text", $to, $config);
					}
				}else if($sess->status == 10.1){
					$pertanyaan = $this->form->getPertanyaan($sess->id_pertanyaan)->result();

					if($pertanyaan[0]->tipe_jawaban == "single_option"){
						$message = $action_id;
					}else if($pertanyaan[0]->tipe_jawaban == "picture" || $pertanyaan[0]->tipe_jawaban == "document"){
						$message = $file;
					}

					$result = $this->check_input($pertanyaan[0]->tipe_jawaban, $message, $sess->id_pertanyaan, $sender);

					if(!$result['status'] || $result['status'] == 'error'){
						$config['msg'] = "*Jawaban Tidak Benar*\\n" . $pertanyaan[0]->pertanyaan;

						if($pertanyaan[0]->tipe_jawaban == 'date'){
							$config['msg'] .= "\\n_Format Jawaban : DD-MM-YYYY (Contoh: 23-10-2024)_";
	
							$this->send_wa("text", $to, $config);
						}else if($pertanyaan[0]->tipe_jawaban == 'datetime'){
							$config['msg'] .= "\\n_Format Jawaban : DD-MM-YYYY JJ:MM (Contoh: 23-10-2024 15:30)_";
	
							$this->send_wa("text", $to, $config);
						}else if($pertanyaan[0]->tipe_jawaban == 'time'){
							$config['msg'] .= "\\n_Format Jawaban : JJ:MM (Contoh: 15:30)_";
	
							$this->send_wa("text", $to, $config);
						}else if($pertanyaan[0]->tipe_jawaban == 'list'){
							$config['msg'] .= "\\n_Penulisan List Jawaban Bisa Dipisah Menggunakan Karakter '|'_\\n_(Contoh: Nama 1|Nama 2|Nama 3)_";
		
							$this->send_wa("text", $to, $config);
						}else if($pertanyaan[0]->tipe_jawaban == 'multi_option'){
							$config['msg'] .= "\\n_Jawaban Bisa Dipilih Lebih dari 1 (Contoh Format Jawaban : 1,2,3,4,5) *Jangan Gunakan Spasi*_";

							$opsi = $this->form->getOpsiJawaban($pertanyaan[0]->id_pertanyaan)->result();

							$config['msg'] .= "\\n";
							
							foreach($opsi as $o){
								$msg .= "\\n" . $o->seq_jawaban . ". " . $o->opsi_jawaban;
							}
	
							$this->send_wa("text", $to, $config);
						}else if($pertanyaan[0]->tipe_jawaban == 'single_option'){
							$opsi = $this->form->getOpsiJawaban($id_pertanyaan_pertama)->result();
	
							foreach($opsi as $o){
								$config = [
									"id" => $o->id_opsi,
									"title" => $o->opsi_jawaban,
									"description" => "   "
								];
		
								array_push($lists, $config);
							}

							$config['lists'] = $lists;
							$config['title_lists'] = "List Jawaban";
	
							$this->send_wa("lists", $to, $config);
						}else if($pertanyaan[0]->tipe_jawaban == 'button'){
							$opsi = $this->form->getOpsiJawaban($pertanyaan[0]->id_pertanyaan)->result();
	
							foreach($opsi as $o){
								$data = [
									"id" => $o->id_opsi,
									"title" => $o->opsi_jawaban
								];
		
								array_push($lists, $data);
							}

							// $config['msg'] = $lists;
							$config['button'] = $lists;
							// $config['title_lists'] = "List Jawaban";
	
							$this->send_wa("button", $to, $config);
						}else{
							$this->send_wa("text", $to, $config);
						}
					}else if($result['status'] == "answer"){
						$id_rec_d = $this->form->getIDRecD($sess->id_rec_h, $sess->id_pertanyaan)->row();

						if($pertanyaan[0]->as_caption){
							$caption = $this->form->getCaptionForm($sess->id_rec_h)->row();

							$caption_form = $caption->caption_form;

							if($caption_form){
								$caption_form .= " | " . $result['result'];
							}else{
								$caption_form .= $result['result'];
							}

							$updateCaption = $this->form->updateCaptionRecH($sess->id_rec_h, $caption_form);
						}

						$send_data = $result['result'];

						$resultUpdate = $this->form->updateValueRecordFormD($id_rec_d->id_rec_form_d, $send_data);	

						$approvalAvail = $this->form->getApprovalAvailable($sess->id_rec_h, $sess->id_user)->result();
						// $totalAppAvail = count($approvalAvail);
	
						$pertanyaanApp = $this->form->getPertanyaanForApp($sess->id_form, $approvalAvail[0]->seq, $sess->id_rec_h)->result();
						$totalPertanyaan = count($pertanyaanApp);

						if($totalPertanyaan > 0){
							$this->form->recordFormD($sess->id_rec_h, $pertanyaanApp[0]->id_pertanyaan);

							$update_sess = $this->form->updateSession($sess->id_session, $sess->id_form, $sess->id_rec_h, $pertanyaanApp[0]->id_pertanyaan, 0, $sess->seq_app, $sess->iterasi, $sess->on_approval, 10.1);

							$config['msg'] = $pertanyaanApp[0]->pertanyaan;

							$this->send_wa("text", $to, $config);
						}else{
							$update_sess = $this->form->updateSession($sess->id_session, $sess->id_form, $sess->id_rec_h, 0, 0, $sess->seq_app, $sess->iterasi, $sess->on_approval, 11);

							$config['msg'] = "Silahkan Masukkan Catatan\\n(_Jika Tidak Ada Catatan Bisa Dibalas Dengan '-'_)";

							$this->send_wa("text", $to, $config);
						}
					}
				}else if($sess->status == 11){
					
					$userPemohon = $this->form->getUserPemohon($sess->id_rec_h)->result();

					$no_form = $this->form->getNoForm($sess->id_rec_h)->result();

					$alurApp = $this->form->getAlurApp($sess->id_user, $sess->id_form, $userPemohon[0]->plant)->result();

					$UserAppNow = $this->form->nextAppUser($sess->id_rec_h)->result();

					$updateApproval = $this->form->updateApproval($sess->id_rec_h, $UserAppNow[0]->id_alur, $message, 1, $sess->iterasi);

					$nextUserApp = $this->form->nextAppUser($sess->id_rec_h)->result();

					$config['title'] = $no_form[0]->title;
					$config['no_form'] = $no_form[0]->no_form;
					$config['pesan'] = " Telah Berhasil *_Disetujui_*";
					$config['link'] = $this->convertPDF($sess->id_rec_h);

					$this->send_wa("notif_done", $to, $config);

					if($nextUserApp){
						$contact_user = '62' . ltrim($nextUserApp[0]->no_hp,'0');

						// =============== Send Message With Template =============== \\
						
						$user_approval_now = $this->form->getUser($to)->result();

						$msg_to_pemohon = "Telah _*Disetujui*_ Oleh: *" . $UserAppNow[0]->jabatan . " - " . $UserAppNow[0]->caption . "* Dengan Catatan : *" . $message . "*. Form Ini Sedang Dikirim Menuju: *" . $nextUserApp[0]->jabatan . " - " . $nextUserApp[0]->caption . "* Untuk Dilakukan Persetujuan Form";
						
						$config['id_rec_h'] = $sess->id_rec_h;
						$config['id_form'] = $sess->id_form;
						$config['loop'] = $sess->iterasi;
						$config['title'] = $no_form[0]->title;
						$config['no_form'] = $no_form[0]->no_form;
						$config['link'] = $this->convertPDF($sess->id_rec_h);

						$this->send_wa("approval", $contact_user, $config);

						// $config['pesan'] = $msg_to_pemohon;

						// $this->send_wa("notif", $to_pemohon, $config);
						
						// $this->send_wa_notif($to_pemohon, $no_form[0]->title, $no_form[0]->no_form, $msg_to_pemohon);

						// $msg = "Form: " . $no_form[0]->title . ", Dengan No Form: " . $no_form[0]->no_form . " Telah Berhasil *_Disetujui_* Dengan Catatan : " . $message;

						// $config['msg'] = $msg;

						// $this->send_wa("notif", $to, $config);

						// $this->send_wa_text($to, $msg);

						// =============== Send Message Without Template =============== \\

						/*$msg_to_pemohon = "Form _*" . $no_form[0]->no_form . "*_ Telah Terkirim Ke: _*" . $nextUserApp[0]->caption . "*_ untuk Dilkukan Approval";
						$this->send_wa_text($to_pemohon, $msg_to_pemohon);

						$msg = "_*Notifikasi*_\\nForm : _*" . $no_form[0]->title . "*_ Dengan Nomor : _*" . $no_form[0]->no_form . "*_ Menunggu Untuk Approval";
						$button = array(
							[
								"id" => "approval_" . $sess->id_rec_h . "_" . $sess->id_form,
								"title" => "Memulai Approval"
							]
						);
						$this->send_wa_button($contact_user, $msg, $button);*/
					}else{
						$loopApp = $this->form->getAppLoop($sess->id_form)->result();

						$totalLoop = count($loopApp);

						$nextLoop = 0;

						for($i = 0; $i < $totalLoop; $i++){
							if($loopApp[$i]->loop_app == $sess->iterasi){
								if($i !== ($totalLoop - 1)){
									$nextLoop = $loopApp[$i+1]->loop_app;
								}
							}
						}

						if($nextLoop){
							$allApp = $this->form->getAllAppBefore($sess->id_rec_h, $sess->iterasi)->result();

							foreach($allApp as $aa){
								$this->form->recordApproval($sess->id_rec_h, $aa->id_alur, "", 0, $aa->seq, $nextLoop);
							}

							$nextUser = $this->form->getUserAnswerNext($sess->id_rec_h, $sess->id_form, $nextLoop)->row();

							$lists = array();

							$pertanyaan = $this->form->getFirstQuestion($sess->id_form, $nextLoop, $nextUser->seq)->row();
		
							$id_pertanyaan_pertama = $pertanyaan->id_pertanyaan;

							$send_to = $this->form->getUserByID($nextUser->id_user_app)->row();
							$send_to = '62' . ltrim($send_to->no_hp,'0');

							$no_form = $this->form->getNoForm($sess->id_rec_h)->row();

							$config["id_user_app"] = $nextUser->id_user_app;
							$config["id_form"] = $sess->id_form;
							$config["id_rec_h"] = $sess->id_rec_h;
							$config["id_pertanyaan"] = $id_pertanyaan_pertama;
							$config["loop"] = $nextLoop;
							$config["seq"] = $nextUser->seq;
							$config["no_form"] = $no_form->no_form;
							$config["title"] = $no_form->title;
							$config["pesan"] = "(Pemeriksaan Saat Pekerjaan Berlangsung dan Pasca Pekerjaan)";
							$config["link"] = $this->convertPDF($sess->id_rec_h);

							$this->send_wa("cont", $send_to, $config);
		
							// $config['msg'] = $pertanyaan->pertanyaan;
		
							// if($pertanyaan->tipe_jawaban == 'date'){
							// 	$config['msg'] .= "\\n_Format Jawaban : DD-MM-YYYY (Contoh: 23-10-2024)_";
		
							// 	$this->send_wa("text", $to, $config);
							// }else if($pertanyaan->tipe_jawaban == 'datetime'){
							// 	$config['msg'] .= "\\n_Format Jawaban : DD-MM-YYYY JJ:MM (Contoh: 23-10-2024 15:30)_";
		
							// 	$this->send_wa("text", $to, $config);
							// }else if($pertanyaan->tipe_jawaban == 'time'){
							// 	$config['msg'] .= "\\n_Format Jawaban : JJ:MM (Contoh: 15:30)_";
		
							// 	$this->send_wa("text", $to, $config);
							// }else if($pertanyaan->tipe_jawaban == 'multi_option'){
							// 	$config['msg'] .= "\\n_Jawaban Bisa Dipilih Lebih dari 1 (Contoh Format Jawaban : 1,2,3,4,5) *Jangan Gunakan Spasi*_";
		
							// 	$this->send_wa("text", $to, $config);
							// }else if($pertanyaan->tipe_jawaban == 'single_option'){
							// 	$opsi = $this->form->getOpsiJawaban($id_pertanyaan_pertama)->result();
		
							// 	foreach($opsi as $o){
							// 		$config = [
							// 			"id" => $o->id_opsi,
							// 			"title" => $o->opsi_jawaban,
							// 			"description" => "   "
							// 		];
			
							// 		array_push($lists, $config);
							// 	}

							// 	$config['msg'] = $pertanyaan->pertanyaan;
							// 	$config['title_lists'] = "List Jawaban";
							// 	$config['lists'] = $lists;

							// 	$this->send_wa("lists", $to, $config);
							// }else{
							// 	$this->send_wa("text", $to, $config);
							// }

							// $start_sess_cont = $this->form->contLoopSession($nextUser->id_user_app, $sess->id_form, $sess->id_rec_h, $id_pertanyaan_pertama, $nextLoop, $nextUser->seq);
						}else{
							$approvedStatus = $this->form->recordFormHApproved($sess->id_rec_h);
	
							// =============== Send Message With Template =============== \\
	
							// $msg_to_pemohon = "Telah Berhasil Disetujui";
	
							// $this->send_wa_notif($to_pemohon, $no_form[0]->title, $no_form[0]->no_form, $msg_to_pemohon);
	
							// $this->send_wa_text($to, $msg);
	
							// $config['msg'] = "Telah Berhasil Disetujui";
	
							// $this->send_wa("text", $to, $config);
	
							// =============== Send Message Without Template =============== \\
	
							/*$msg_to_pemohon = "Form _*" . $no_form[0]->no_form . "*_ Telah Berhasil *Disetujui*";
							$this->send_wa_text($to_pemohon, $msg_to_pemohon);*/
						}

						// var_dump($no_form);
						// var_dump($no_form->title);

						// echo "here";
						$no_form = $this->form->getNoForm($sess->id_rec_h)->row();

						$config['title'] = $no_form->title;
						$config['no_form'] = $no_form->no_form;
						$config['pesan'] = " Telah Berhasil *_Disetujui_* Oleh Semua User Approval";
						$config['link'] = $this->convertPDF($sess->id_rec_h);

						$to_pemohon = $userPemohon[0]->no_hp;

						$to_pemohon = '62' . ltrim($to_pemohon,'0');

						$this->send_wa("notif_done", $to_pemohon, $config);
					}

					$update_sess = $this->form->updateSession($sess->id_session, $sess->id_form, $sess->id_rec_h, 0, 0, $sess->seq_app, $sess->iterasi, $sess->on_approval, 12);

					$update_sess = $this->form->updateSession($sess->id_session, 0, 0, 0, 0, $sess->seq_app, $sess->iterasi, $sess->on_approval, 99);
				}else if($sess->status == 13){
					$userPemohon = $this->form->getUserPemohon($sess->id_rec_h)->result();

					$to_pemohon = $userPemohon[0]->no_hp;

					$to_pemohon = '62' . ltrim($to_pemohon,'0');

					$no_form = $this->form->getNoForm($sess->id_rec_h)->result();

					// $user_app = $this->form->getUser($sender)->result();

					// $alurApp = $this->form->getAlurApp($user_app[0]->id_user, $sess->id_form, $userPemohon[0]->plant)->result();

					// $recordRejectForm = $this->form->recordApproval($sess->id_rec_h, $alurApp[0]->id_alur, $message, 0);

					$UserAppNow = $this->form->nextAppUser($sess->id_rec_h)->result();

					$updateApproval = $this->form->updateApproval($sess->id_rec_h, $UserAppNow[0]->id_alur, $message, 2, $sess->iterasi);

					$msg = "Form: " . $no_form[0]->title . ", Dengan No Form: " . $no_form[0]->no_form . " Telah *_Ditolak_* Dengan Catatan : " . $message;
					
					// $this->send_wa_text($to, $msg);

					$config['msg'] = $msg;

					$this->send_wa("text", $to, $config);

					// $user_approval_now = $this->form->getUser($to)->result();

					// $msg_to_pemohon = "Telah _*Ditolak*_ Oleh: " . $user_approval_now[0]->nama . " Dengan Catatan : " . $message;
					// $this->send_wa_notif($to_pemohon, $no_form[0]->title, $no_form[0]->no_form, $msg_to_pemohon);

					$msg_to_pemohon = "Telah _*Ditolak*_ Oleh: *" . $UserAppNow[0]->jabatan . " - " . $UserAppNow[0]->caption . "* Dengan Catatan : *" . $message . "*";

					// $this->send_wa_notif($to_pemohon, $no_form[0]->title, $no_form[0]->no_form, $msg_to_pemohon);

					$config['title'] = $no_form[0]->title;
					$config['no_form'] = $no_form[0]->no_form;
					$config['pesan'] = $msg_to_pemohon;
					$config['link'] = $this->convertPDF($sess->id_rec_h);

					$this->send_wa("notif_done", $to_pemohon, $config);

					$update_sess = $this->form->updateSession($sess->id_session, $sess->id_form, $sess->id_rec_h, 0, 0, $sess->seq_app, $sess->iterasi, $sess->on_approval, 14);

					$update_sess = $this->form->updateSession($sess->id_session, 0, 0, 0, 0, $sess->seq_app, $sess->iterasi, $sess->on_approval, 99);

				}
			}
		}else if($mode == "verif_ot"){
			$dataOT = $this->matt->dataOT($plant, $batch, $dept)->result();
			
			$sess = $this->form->cekSession($sender)->row();

			$already_verified = false;

			if($status == ""){
				$message = "Permintaan Verifikasi Lembur:\\n\\n";
	
				$ctr = 1;
				$tgl = "";
	
				foreach($dataOT as $do){
					if($ctr <= 1){
						$tgl = $do->tanggal;
						$start_hour=date_create($do->start);
						$start_hour=date_format($start_hour,"H:i");
	
						$end_hour=date_create($do->end);
						$end_hour=date_format($end_hour,"H:i");

						$date = explode("-", $tgl);
						$day = str_pad($date[0],2,"0", STR_PAD_LEFT);
						$month = $date[1];
						$year = $date[2];

						$datecode = date_create($year . "-" . $month . "-" . $day);

						$hariCode = date_format($datecode,"N");

						if($hariCode == 1){
							$hariCode = "Senin";
						}elseif($hariCode == 2){
							$hariCode = "Selasa";
						}elseif($hariCode == 3){
							$hariCode = "Rabu";
						}elseif($hariCode == 4){
							$hariCode = "Kamis";
						}elseif($hariCode == 5){
							$hariCode = "Jumat";
						}elseif($hariCode == 6){
							$hariCode = "Sabtu";
						}elseif($hariCode == 7){
							$hariCode = "Minggu";
						}

						$monthCode = date_format($datecode,"n");

						if($monthCode == 1){
							$monthCode = "Januari";
						}elseif($monthCode == 2){
							$monthCode = "Februari";
						}elseif($monthCode == 3){
							$monthCode = "Maret";
						}elseif($monthCode == 4){
							$monthCode = "April";
						}elseif($monthCode == 5){
							$monthCode = "Mei";
						}elseif($monthCode == 6){
							$monthCode = "Juni";
						}elseif($monthCode == 7){
							$monthCode = "Juli";
						}elseif($monthCode == 8){
							$monthCode = "Agustus";
						}elseif($monthCode == 9){
							$monthCode = "September";
						}elseif($monthCode == 10){
							$monthCode = "Oktober";
						}elseif($monthCode == 11){
							$monthCode = "November";
						}elseif($monthCode == 12){
							$monthCode = "Desember";
						}

						$tanggal_write = $hariCode . ", " . date_format($datecode,"d") . " " . $monthCode . " " . date_format($datecode,"Y");

						$tgl = $tanggal_write;
	
						$message .= "Tanggal: " . $tgl . "\\n\\n";
						$message .= $ctr . ". " . $do->nama . " (" . $do->nopeg . ")\\n        " . $do->task . "\\n        *" . $start_hour . " - " . $end_hour . "* (*" . $do->ot . "* Jam)\\n        Total OT Berjalan: *" . $do->total_ot . "* Jam\\n";
						// $message .= $ctr . ". " . $do->nama . " (" . $do->nopeg . ")\\n- " . $do->task . "\\n- *" . $start_hour . " - " . $end_hour . "* (*" . $do->ot . "* Jam)\\n- Total OT Berjalan: *" . $do->total_ot . "* Jam\\n";
					}else{
						if($tgl == $do->tanggal){
							$start_hour=date_create($do->start);
							$start_hour=date_format($start_hour,"H:i");
	
							$end_hour=date_create($do->end);
							$end_hour=date_format($end_hour,"H:i");
	
							$message .= $ctr . ". " . $do->nama . " (" . $do->nopeg . ")\\n        " . $do->task . "\\n        *" . $start_hour . " - " . $end_hour . "* (*" . $do->ot . "* Jam)\\n        Total OT Berjalan: *" . $do->total_ot . "* Jam\\n";
							// $message .= $ctr . ". " . $do->nama . " (" . $do->nopeg . ")\\n- " . $do->task . "\\n- *" . $start_hour . " - " . $end_hour . "* (*" . $do->ot . "* Jam)\\n- Total OT Berjalan: *" . $do->total_ot . "* Jam\\n";
						}else{
							$tgl = $do->tanggal;
							$start_hour=date_create($do->start);
							$start_hour=date_format($start_hour,"H:i");
	
							$end_hour=date_create($do->end);
							$end_hour=date_format($end_hour,"H:i");

							$date = explode("-", $tgl);
							$day = str_pad($date[0],2,"0", STR_PAD_LEFT);
							$month = $date[1];
							$year = $date[2];

							$datecode = date_create($year . "-" . $month . "-" . $day);

							$hariCode = date_format($datecode,"N");

							if($hariCode == 1){
								$hariCode = "Senin";
							}elseif($hariCode == 2){
								$hariCode = "Selasa";
							}elseif($hariCode == 3){
								$hariCode = "Rabu";
							}elseif($hariCode == 4){
								$hariCode = "Kamis";
							}elseif($hariCode == 5){
								$hariCode = "Jumat";
							}elseif($hariCode == 6){
								$hariCode = "Sabtu";
							}elseif($hariCode == 7){
								$hariCode = "Minggu";
							}

							$monthCode = date_format($datecode,"n");

							if($monthCode == 1){
								$monthCode = "Januari";
							}elseif($monthCode == 2){
								$monthCode = "Februari";
							}elseif($monthCode == 3){
								$monthCode = "Maret";
							}elseif($monthCode == 4){
								$monthCode = "April";
							}elseif($monthCode == 5){
								$monthCode = "Mei";
							}elseif($monthCode == 6){
								$monthCode = "Juni";
							}elseif($monthCode == 7){
								$monthCode = "Juli";
							}elseif($monthCode == 8){
								$monthCode = "Agustus";
							}elseif($monthCode == 9){
								$monthCode = "September";
							}elseif($monthCode == 10){
								$monthCode = "Oktober";
							}elseif($monthCode == 11){
								$monthCode = "November";
							}elseif($monthCode == 12){
								$monthCode = "Desember";
							}

							$tanggal_write = $hariCode . ", " . date_format($datecode,"d") . " " . $monthCode . " " . date_format($datecode,"Y");

							$tgl = $tanggal_write;
	
							$message .= "\\nTanggal: " . $tgl . "\\n\\n";
							$message .= $ctr . ". " . $do->nama . " (" . $do->nopeg . ")\\n        " . $do->task . "\\n        *" . $start_hour . " - " . $end_hour . "* (*" . $do->ot . "* Jam)\\n        Total OT Berjalan: *" . $do->total_ot . "* Jam\\n";
							// $message .= $ctr . ". " . $do->nama . " (" . $do->nopeg . ")\\n- " . $do->task . "\\n- *" . $start_hour . " - " . $end_hour . "* (*" . $do->ot . "* Jam)\\n- Total OT Berjalan: *" . $do->total_ot . "* Jam\\n";
						}
					}
					$ctr++;
				}
	
				$config['button'] = array(
					[
						"id" => "verifOT_confirm-all_" . $plant . "_" . $dept . "_" . $batch . "_",
						"title" => "Verifikasi Semua"
					],
					[
						"id" => "verifOT_confirm-selection_" . $plant . "_" . $dept . "_" . $batch . "_",
						"title" => "Seleksi Verifikasi"
					],
					[
						"id" => "verifOT_reject-all_" . $plant . "_" . $dept . "_" . $batch . "_",
						"title" => "Tolak Semua"
					]
				);
	
				$config['msg'] = $message;
	
				$this->send_wa("button", "6282228909916", $config);
			}else if($status == "confirm-all"){
				foreach($dataOT as $do){
					if($do->verified > 0){
						$already_verified = true;
					}

					if(!$already_verified){
						$user = $this->form->getUser($sender)->row();
						$nopeg = $user->nopeg;

						$verifiedAll = $this->matt->confirmOT($do->id_data_verif, $nopeg);
					}
				}

				if($already_verified){
					$config['msg'] = "Untuk SPL Tersebut Sudah Dilakukan Verifikasi";
				}else{
					$config['msg'] = "Semua SPL Berhasil Terverifikasi.";
				}

				$this->send_wa("text", "6282228909916", $config);
			}else if($status == "confirm-selection"){
				$user = $this->form->getUser($sender)->row();
				$id_user = $user->id_user;
				
				$record_sess = $this->form->recordSession($id_user);

				$this->form->updateSession($record_sess, 0, 0, 0, 0, 0, 0, 0, 22.1);
				$this->form->updateSessionExtendedInfo($record_sess, $plant, $dept, $batch);

				$config['msg'] = "Silahkan Masukkan Nomor Yang Akan di Verifikasi (Contoh Format : 1,2,3,4)";

				$this->send_wa("text", "6282228909916", $config);
			}else if($status == "reset-confirm-selection"){
				$user = $this->form->getUser($sender)->row();
				$id_user = $user->id_user;
				
				$record_sess = $this->form->recordSession($id_user);

				$this->form->updateSession($record_sess, 0, 0, 0, 0, 0, 0, 0, 22.1);
				$this->form->updateSessionExtendedInfo($record_sess, $plant, $dept, $batch);

				$config['msg'] = "Silahkan Masukkan Nomor Yang Akan di Verifikasi (Contoh Format : 1,2,3,4)";

				$this->send_wa("text", "6282228909916", $config);
			}else if($status == "reject-selection"){
				$no_reject = explode(",", $no_reject);

				$ctr = 1;
				foreach($dataOT as $do){
					if(gettype(array_search(strval($ctr),$no_reject)) == "integer"){
						$user = $this->form->getUser($sender)->row();
						$nopeg = $user->nopeg;

						$rejectData = $this->matt->rejectOT($do->id_data_verif, $nopeg);
					}

					$ctr++;
				}
				
				$this->form->updateSession($sess->id_session, 0, 0, 0, 0, 0, 0, 0, 22.2);
				$this->form->updateSessionExtendedInfo($sess->id_session, $plant, $dept, $batch);

				$config['msg'] = "Silahkan Masukkan Pesan Menolak SPL. (Jika Tidak Ada Pesan Maka Bisa Dibalas Dengan Karakter Minus '-')";

				$this->send_wa("text", "6282228909916", $config);
			}else if($status == "reject-selection-note"){
				foreach($dataOT as $do){
					if($do->verified == 2){
						$this->matt->updateNoteOT($do->id_data_verif, $message);
					}
				}
				
				$this->form->updateSession($sess->id_session, 0, 0, 0, 0, 0, 0, 0, 99);

				$config['msg'] = "Semua SPL Berhasil Terverifikasi.";

				$this->send_wa("text", "6282228909916", $config);
			}else if($status == "confirm-selection-action"){
				$no_verif = explode(",", $message);
				$no_reject = "";

				$ctr = 1;
				foreach($dataOT as $do){
					if(gettype(array_search(strval($ctr),$no_verif)) == "integer"){
						$user = $this->form->getUser($sender)->row();
						$nopeg = $user->nopeg;

						$verifiedData = $this->matt->confirmOT($do->id_data_verif, $nopeg);
					}else{
						$no_reject .= $ctr . ",";
					}

					$ctr++;
				}

				var_dump($no_reject);

				$no_reject = substr($no_reject, 0, strlen($no_reject) - 1);

				$this->form->updateSession($sess->id_session, 0, 0, 0, 0, 0, 0, 0, 22.2);

				$config['msg'] = "Apakah Nomor " . $no_reject . " Akan Ditolak ?";

				$config['button'] = array(
					[
						"id" => "verifOT_reject-selection_" . $plant . "_" . $dept . "_" . $batch . "_" . $no_reject,
						"title" => "Iya"
					],
					[
						"id" => "verifOT_reset-confirm-selection_" . $plant . "_" . $dept . "_" . $batch . "_",
						"title" => "Tidak"
					]
				);

				$this->send_wa("button", "6282228909916", $config);
			}else if($status == "reject-all"){
				foreach($dataOT as $do){
					if($do->verified > 0){
						$already_verified = true;
					}

					if(!$already_verified){
						$user = $this->form->getUser($sender)->row();
						$nopeg = $user->nopeg;

						$rejectAll = $this->matt->rejectOT($do->id_data_verif, $nopeg);
					}
				}

				if($already_verified){
					$config['msg'] = "Untuk SPL Tersebut Sudah Dilakukan Verifikasi";
				}else{
					$config['msg'] = "Silahkan Masukkan Pesan Menolak SPL. (Jika Tidak Ada Pesan Maka Bisa Dibalas Dengan Karakter Minus '-')";

					$user = $this->form->getUser($sender)->row();
					$id_user = $user->id_user;
					
					$record_sess = $this->form->recordSession($id_user);

					$this->form->updateSession($record_sess, 0, 0, 0, 0, 0, 0, 0, 21);
					$this->form->updateSessionExtendedInfo($record_sess, $plant, $dept, $batch);
				}

				$this->send_wa("text", "6282228909916", $config);
			}else if($status == "note-reject-all"){
				if($message != "-"){
					foreach($dataOT as $do){
						$this->matt->updateNoteOT($do->id_data_verif, $message);
					}
				}

				$this->form->updateSession($sess->id_session, 0, 0, 0, 0, 0, 0, 0, 99);

				$config['msg'] = "Semua SPL Berhasil Terverifikasi.";

				$this->send_wa("text", "6282228909916", $config);
			}
		}
	}

	function check_input($type, $jwb, $id_pty, $sender){
		$data = [
			"status" => "",
			"result" => "",
			"sub_result" => "",
			"id_opsi" => 0
		];

		$sess = $this->form->cekSession($sender)->row();

		if($type == "date"){
			$check_content = strpos($jwb, "-");

			if(!$check_content){
				$data['status'] = "error";
			}else{
				$date_breakdown = explode("-", $jwb);

				if(count($date_breakdown) == 3){
					$day = $date_breakdown[0];
					$month = $date_breakdown[1];
					$year = $date_breakdown[2];

					if((int)$day > 0 && (int)$day < 32){
						if((int)$month > 0 && (int)$month < 13){
							if((int)$year > 2000 && (int)$month <= 9999){
								$data['status'] = "answer";
								$data['result'] = $jwb;
							}else{
								$data['status'] = "error";
							}
						}else{
							$data['status'] = "error";
						}
					}else{
						$data['status'] = "error";
					}
				}else{
					$data['status'] = "error";
				}
			}
		}else if($type == "time"){
			$check_content = strpos($jwb, ":");

			if(!$check_content){
				$data['status'] = "error";
			}else{
				$time_breakdown = explode(":", $jwb);

				if(count($time_breakdown) == 2){
					$hour = $time_breakdown[0];
					$minute = $time_breakdown[1];

					if((int)$hour > 0 && (int)$hour < 25){
						if((int)$minute >= 0 && (int)$minute < 60){
							$data['status'] = "answer";
							$data['result'] = $jwb;
						}else{
							$data['status'] = "error";
						}
					}else{
						$data['status'] = "error";
					}
				}else{
					$data['status'] = "error";
				}
			}
		}else if($type == "datetime"){
			$check_content_1 = strpos($jwb, ":");
			$check_content_2 = strpos($jwb, "-");

			if(!$check_content_1 || !$check_content_2){
				$data['status'] = "error";
			}else if($check_content_1 && $check_content_2){
				$datetime_breakdown = explode(" ", $jwb);

				if(count($datetime_breakdown) == 2){
					$date = $datetime_breakdown[0];
					$time = $datetime_breakdown[1];

					$date_breakdown = explode("-", $date);

					if(count($date_breakdown) == 3){
						$day = $date_breakdown[0];
						$month = $date_breakdown[1];
						$year = $date_breakdown[2];

						if((int)$day > 0 && (int)$day < 32){
							if((int)$month > 0 && (int)$month < 13){
								if((int)$year > 2000 && (int)$month <= 9999){
									$data['status'] = "answer";
								}else{
									$data['status'] = "error";
								}
							}else{
								$data['status'] = "error";
							}
						}else{
							$data['status'] = "error";
						}
					}else{
						$data['status'] = "error";
					}

					$time_breakdown = explode(":", $time);

					if(count($time_breakdown) == 2){
						$hour = $time_breakdown[0];
						$minute = $time_breakdown[1];

						if((int)$hour > 0 && (int)$hour < 25){
							if((int)$minute > 0 && (int)$minute < 60){
								$data['status'] = "answer";
							}else{
								$data['status'] = "error";
							}
						}else{
							$data['status'] = "error";
						}
					}else{
						$data['status'] = "error";
					}

					if($data['status'] == "answer"){
						$data['result'] = $jwb;
					}
				}else{
					$data['status'] = "error";
				}
			}else{
				$data['status'] = "error";
			}
		}else if($type == "single_option"){
			if(filter_var($jwb, FILTER_VALIDATE_INT)){
				$opsi = $this->form->getOpsiJawaban($id_pty)->result();

				foreach($opsi as $o){
					if($o->id_opsi == (int)$jwb){
						if($o->sub_pertanyaan != ""){
							$data['result'] = $o->sub_pertanyaan;
							$data['sub_result'] = $o->opsi_jawaban;
							$data['status'] = "hold";
							$data['id_opsi'] = $o->id_opsi;
						}else{
							$result = $o->opsi_jawaban;
							$data['result'] = $o->opsi_jawaban;
							$data['status'] = "answer";
							$data['id_opsi'] = $o->id_opsi;
						}
					}
				}
			}
		}else if($type == "button"){
			$opsi = $this->form->getOpsiJawaban($id_pty)->result();

			$data['status'] = "error";

			foreach($opsi as $op){
				if($op->id_opsi == $jwb){
					$data['result'] = $op->opsi_jawaban;
					$data['status'] = "answer";
					$data['id_opsi'] = $op->id_opsi;
				}
			}

		}else if($type == "multi_option"){
			if(!ctype_space($jwb)){
				$done = true;
				$hold = false;
				$question_hold = "";
				$sub_result = 0;
				$id_opsi = 0;

				$jwb_break = explode(",",$jwb);

				$count_jwb = array_count_values($jwb_break);

				$opsi = $this->form->getOpsiJawaban($id_pty)->result();

				$seq_opsi = array();

				foreach($opsi as $o){
					array_push($seq_opsi, $o->seq_jawaban);
				}

				// var_dump($opsi);

				foreach($jwb_break as $jb){
					if(gettype(filter_var($jb, FILTER_VALIDATE_INT)) == "integer"){
						if(in_array((int)$jb, $seq_opsi)){
							if($count_jwb[$jb] > 1){
								$done = false;
							}
						}else{
							$done = false;
						}
					}else{
						$done = false;
					}

					foreach($opsi as $o){
						if($o->seq_jawaban == $jb){
							if($o->sub_pertanyaan != ""){
								$hold = true;
								$question_hold = $o->sub_pertanyaan;
								$sub_result = $o->opsi_jawaban;
								$id_opsi = $o->seq_jawaban;
							}
						}
					}
				}

				if($done){
					$data['result'] = $jwb;
					$data['status'] = "answer";
				}

				if($hold){
					$data['result'] = $question_hold;
					$data['status'] = "hold";
					$data['sub_result'] = $sub_result;
					$data['id_opsi'] = $jwb;
				}
			}else{
				$data['status'] = "error";
			}
		}else if($type == "list"){
			$break = explode("|", $jwb);

			$done = "";

			for($i = 0; $i < count($break); $i++){
				$break[$i] = trim($break[$i]);

				if($break[$i] == ""){
					array_splice($break, $i, 1);
				}	
			}

			foreach($break as $b){
				$done .= $b . "|";
			}

			$done = substr($done, 0, strlen($done) - 1);

			$data['status'] = "answer";
			$data['result'] = $done;
		}else if($type == "integer"){
			if(gettype(filter_var($jwb, FILTER_VALIDATE_INT)) == "integer"){
				$data['status'] = "answer";
				$data['result'] = $jwb;
			}else{
				$data['status'] = "error";
			}
		}else if($type == "double"){
			if(filter_var($jwb, FILTER_VALIDATE_FLOAT) || $jwb == 0 || $jwb == '0'){
				$data['status'] = "answer";
				$data['result'] = $jwb;
			}else{
				$data['status'] = "error";
			}
		}else if($type == "picture"){
			if($jwb["file_type"] == "image"){
				$compress = false;
				$done = false;

				$new_dir = $this->make_dir($sess->id_rec_h, $sess->id_pertanyaan);

				$new_dir = $new_dir . basename($jwb["file_url"]);

				$unit = $this->checkSize($jwb["file_url"]);

				if($unit == "MB" || $unit == "GB"){
					$compress = true;
				}

				if ($this->downloadFile($jwb["file_url"], $new_dir)) {
					if($compress){
						if ($this->compressImage($new_dir, 15)) {
							$done = true;
						}
					}else{
						$done = true;
					}
				}

				if($done){
					$data['status'] = "answer";
					$data['result'] = $new_dir;
				}else{
					$data['status'] = "error";
				}
			}else{
				$data['status'] = "error";
			}
		}else if($type == "document"){
			if($jwb["file_type"] == "document"){

				$done = false;

				$new_dir = $this->make_dir($sess->id_rec_h, $sess->id_pertanyaan);

				$new_dir = $new_dir . basename($jwb["file_url"]);

				// $unit = $this->checkSize($jwb["file_url"]);

				// if($unit == "MB" || $unit == "GB"){
				// 	$compress = true;
				// }

				if ($this->downloadFile($jwb["file_url"], $new_dir)) {
					// if($compress){
						// if ($this->compressImage($new_dir, 15)) {
							$done = true;
						// }
					// }else{
					// 	$done = true;
					// }
				}

				if($done){
					$data['status'] = "answer";
					$data['result'] = $new_dir;
				}else{
					$data['status'] = "error";
				}

				// $data['status'] = "answer";
				// $data['result'] = $jwb["file_url"];
			}else{
				$data['status'] = "error";
			}
		}else{
			$data['status'] = "answer";
			$data['result'] = $jwb;
		}

		return $data;
	}

	function send_wa($mode, $to, $data){
		// Get Token From DB
		$token = $this->api->getToken(1)->row()->token;
		$authorization = "Authorization: Bearer " . $token;
		
		// Set Mode
		$post = "";
		
		if($mode == "text"){
			$post = '{
				"to": "'.$to.'",
				"type": "text",
				"message": "'.$data['msg'].'"
			}';
		}else if($mode == "lists"){
			$post = '{
				"to": "'.$to.'",
				"type": "list",
				"message": "'.$data['msg'].'",
				"list_title": "'.$data['title_lists'].'",
				"lists": [';
	
				$total_list = count($data['lists']);
	
				for($i = 0; $i < $total_list; $i++){
					if(($i + 1) == $total_list){
						$post .= '{
									"id": "'. $data['lists'][$i]['id'] .'",
									"title": "'. $data['lists'][$i]['title'] .'",
									"description": "'. $data['lists'][$i]['description'] .'"
								}';
					}else{
						$post .= '{
									"id": "'. $data['lists'][$i]['id'] .'",
									"title": "'. $data['lists'][$i]['title'] .'",
									"description": "'. $data['lists'][$i]['description'] .'"
								},';
					}
				}
	
			$post .= ']}';
		}else if($mode == "button"){
			$post = '{
				"to": "'.$to.'",
				"type": "button",
				"message": "'.$data['msg'].'",
				"buttons": [';
	
			$total_button = count($data['button']);
	
			for($i = 0; $i < $total_button; $i++){
				if(($i + 1) == $total_button){
					$post .= '{
									"id": "'.$data['button'][$i]['id'].'",
									"title": "'.$data['button'][$i]['title'].'"
								}';
				}else{
					$post .= '{
									"id": "'.$data['button'][$i]['id'].'",
									"title": "'.$data['button'][$i]['title'].'"
								},';
				}
			}

			$post .= "]}";
		}else if($mode == "approval"){
			$post = '{
				"messaging_product": "whatsapp",
				"recipient_type": "individual",
				"to": "'.$to.'",
				"type": "template",
				"template": {
					"name": "notifikasi_approval_start_final",
					"language": {
						"code": "id"
					},
					"components": [
						{
							"type": "header",
							"parameters": [
								{
									"type": "document",
									"document": {
										"link":"'.$data['link'].'",
										"filename": "Work Permit.pdf"
									}
								}
							]
						},
						{
							"type": "button",
							"sub_type": "quick_reply",
							"index": "0",
							"parameters": [
								{
									"type": "payload",
									"payload": "approval_'.$data['id_rec_h'].'_'.$data['id_form'].'_'.$data['loop'].'"
								}
							]
						},
						{
							"type": "body",
							"parameters":[
								{
									"type": "text",
									"text": "'.$data['title'].'"
								},
								{
									"type": "text",
									"text": "'.$data['no_form'].'"
								}
							]
						}
					]
				}
			}';
		}else if($mode == "notif"){
			$post = '{
				"messaging_product": "whatsapp",
				"recipient_type": "individual",
				"to": "'.$to.'",
				"type": "template",
				"template": {
					"name": "form_notifikasi_upadate",
					"language": {
						"code": "id"
					},
					"components": [
							{
							"type": "body",
							"parameters": [
								{
									"type": "text",
									"text": "'.$data['title'].'"
								},
								{
									"type": "text",
									"text": "'.$data['no_form'].'"
								},
								{
									"type": "text",
									"text": "'.$data['pesan'].'"
								}
							]
						}
					]
				}
			}';
		}else if($mode == "notif_done"){
			$post = '{
				"messaging_product": "whatsapp",
				"recipient_type": "individual",
				"to": "'.$to.'",
				"type": "template",
				"template": {
					"name": "form_notifikasi_update",
					"language": {
						"code": "id"
					},
					"components": [
						{
							"type": "header",
							"parameters": [
								{
									"type": "document",
									"document": {
										"link":"'.$data['link'].'",
										"filename": "Work Permit.pdf"
									}
								}
							]
						},
						{
							"type": "body",
							"parameters": [
								{
									"type": "text",
									"text": "'.$data['title'].'"
								},
								{
									"type": "text",
									"text": "'.$data['no_form'].'"
								},
								{
									"type": "text",
									"text": "'.$data['pesan'].'"
								}
							]
						}
					]
				}
			}';
		}else if($mode == "cont"){
			$post = '{
				"messaging_product": "whatsapp",
				"recipient_type": "individual",
				"to": "'.$to.'",
				"type": "template",
				"template": {
					"name": "continue_approval_loop_update",
					"language": {
						"code": "id"
					},
					"components": [
						{
							"type": "header",
							"parameters": [
								{
									"type": "document",
									"document": {
										"link":"'.$data['link'].'",
										"filename": "Work Permit.pdf"
									}
								}
							]
						},
						{
							"type": "button",
							"sub_type": "quick_reply",
							"index": "0",
							"parameters": [
								{
									"type": "payload",
									"payload": "startform_'.$data['id_user_app'].'_'.$data['id_form'].'_'.$data['id_rec_h'].'_'.$data['id_pertanyaan'].'_'.$data['loop'].'_'.$data['seq'].'"
								}
							]
						},
						{
							"type": "body",
							"parameters": [
								{
									"type": "text",
									"text": "'.$data['title'].'"
								},
								{
									"type": "text",
									"text": "'.$data['no_form'].'"
								},
								{
									"type": "text",
									"text": "'.$data['pesan'].'"
								}
							]
						}
					]
				}
			}';
		}else if($mode == "preview"){
			$post = '{
						"to": "'.$to.'",
						"type": "button",
						"header": {
						"type": "document",
						"document": {
							"link": "'.$data['link'].'",
							"filename": "Work_Permit.pdf"
						}
						},
						"message": "'.$data['msg'].'",
						"buttons": [';
						
			$total_button = count($data['button']);

			for($i = 0; $i < $total_button; $i++){
				if(($i + 1) == $total_button){
					$post .= '{
									"id": "'.$data['button'][$i]['id'].'",
									"title": "'.$data['button'][$i]['title'].'"
								}';
				}else{
					$post .= '{
									"id": "'.$data['button'][$i]['id'].'",
									"title": "'.$data['button'][$i]['title'].'"
								},';
				}
			}
				
			$post .= ']}';
		}else if($mode == "notif_kk"){
			$post = "{
				\"messaging_product\": \"whatsapp\",
				\"recipient_type\": \"individual\",
				\"to\": \"".$to."\",
				\"type\": \"template\",
				\"template\": {
					\"name\": \"notif_kecelakaan_kerja\",
					\"language\": {
						\"code\": \"id\"
					},
					\"components\": [
							{
							\"type\": \"body\",
							\"parameters\": [
								{
									\"type\": \"text\",
									\"text\": \"".$data["dataPelapor"]."\"
								},
								{
									\"type\": \"text\",
									\"text\": \"".$data["tanggalKejadian"]."\"
								},
								{
									\"type\": \"text\",
									\"text\": \"".$data["korban"]."\"
								},
								{
									\"type\": \"text\",
									\"text\": \"".$data["dataKronologi"]."\"
								}
							]
						}
					]
				}
			}";
		}

		//Execute
		$ch = curl_init();

		curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type: application/json' , $authorization ));
		curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST");
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
		curl_setopt($ch, CURLOPT_POSTFIELDS, $post);
		curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
		curl_setopt($ch, CURLOPT_URL, "https://api.halosis.id/v1/messages");
		curl_setopt($ch, CURLOPT_ENCODING, "");
		curl_setopt($ch, CURLOPT_MAXREDIRS, 10);
		curl_setopt($ch, CURLOPT_TIMEOUT, 0);
		curl_setopt($ch, CURLOPT_HTTP_VERSION, CURL_HTTP_VERSION_1_1);
		$result = curl_exec($ch);
		curl_close($ch);
		$report = json_decode($result);

		var_dump(var_export($report, TRUE));

		$this->form->recordReportAPI(var_export($report, TRUE), $post);
	}

	function recordLog($data){
		if($data["type"] == "message.received"){
			$input = var_export($data, TRUE);
			$from = $data["data"]["from_phone_number"];
			$id_msg = $data["data"]["id"];
			$conversation_id = $data["data"]["conversation_id"];
			$msg = $data["data"]["message"];
			$action_id = $data["data"]["action_id"];
			$timestamp = $data["data"]["timestamp"];

			$file_type = "";
			$file_url = "";

			if(isset($data["data"]["file"])){
				$file_type = $data["data"]["file"]["file_type"];
				$file_url = $data["data"]["file"]["file_url"];
			}
	
			$this->form->logApi($input, $from, $id_msg, $conversation_id, $msg, $action_id, $timestamp, $file_type, $file_url);
		}
	}

	function make_dir($id_rec_h, $id_pertanyaan){
		$data_rec_d = $this->form->getIDRecDwithPertanyaan($id_rec_h, $id_pertanyaan)->row();

		$folder = "assets/form_data/ird_" . $data_rec_d->id_rec_form_d . "/";

		$done = false;
		
		if (!file_exists($folder)) {
			mkdir($folder);

			$done = true;
		}

		if($done){
			return $folder;
		}else{
			return $done;
		}
	}

	function checkSize($url){
		$img = get_headers($url, 1);
		$size = $this->formatSizeUnits($img['Content-Length']);
		$breakdown_size = explode("|", $size);
		$unit = $breakdown_size[1];
		$size = (float)$breakdown_size[0];

		return $unit;
	}

	private function downloadFile($url, $destination) {
        $fileContent = file_get_contents($url);
        if ($fileContent === FALSE) {
            return false;
        }

        if (file_put_contents($destination, $fileContent)) {
            return true;
        } else {
            return false;
        }
    }

	private function compressImage($file_path, $quality) {
        list($width, $height, $type) = getimagesize($file_path);
        $src = null;

        switch ($type) {
            case IMAGETYPE_JPEG:
                $src = imagecreatefromjpeg($file_path);
                break;
            case IMAGETYPE_PNG:
                $src = imagecreatefrompng($file_path);
                imagealphablending($src, false);
                imagesavealpha($src, true);
                break;
            case IMAGETYPE_GIF:
                $src = imagecreatefromgif($file_path);
                break;
            default:
                return false;
        }

        if ($type == IMAGETYPE_JPEG) {
            imagejpeg($src, $file_path, $quality);
        } elseif ($type == IMAGETYPE_PNG) {
            $compression = (int)((100 - $quality) / 10);
            imagepng($src, $file_path, $compression);
        } elseif ($type == IMAGETYPE_GIF) {
            // GIF does not support quality parameter, re-save the file
            imagegif($src, $file_path);
        }

        imagedestroy($src);

        return true;
    }

	private function formatSizeUnits($bytes) {
        if (is_numeric($bytes)) {
            if ($bytes >= 1073741824) {
                $bytes = number_format($bytes / 1073741824, 2) . '|GB';
            } elseif ($bytes >= 1048576) {
                $bytes = number_format($bytes / 1048576, 2) . '|MB';
            } elseif ($bytes >= 1024) {
                $bytes = number_format($bytes / 1024, 2) . '|KB';
            } elseif ($bytes > 1) {
                $bytes = $bytes . '|bytes';
            } elseif ($bytes == 1) {
                $bytes = $bytes . '|byte';
            } else {
                $bytes = '0|bytes';
            }
        }

        return $bytes;
    }

	function convertPDF($id_rec_h){
		// $no_form = $this->form->getNoForm($id_rec_h)->row()->no_form;

		// $config["msg"] = $no_form;

		// $this->send_wa("text", "6282228909916", $config);

		// $romanMonths = [
		// 	1 => 'I',
		// 	2 => 'II',
		// 	3 => 'III',
		// 	4 => 'IV',
		// 	5 => 'V',
		// 	6 => 'VI',
		// 	7 => 'VII',
		// 	8 => 'VIII',
		// 	9 => 'IX',
		// 	10 => 'X',
		// 	11 => 'XI',
		// 	12 => 'XII'
		// ];

		// $data['no_ijin'] = $id_rec_h . "/" . date("d") . "/" . $romanMonths[date("n")] . "/" . date("Y") . "/IK/SHE-C06";
		// $no_ijin = $id_rec_h . "_" . date("d") . "_" . $romanMonths[date("n")] . "_" . date("Y") . "_IK_SHE-C06";

		$answer = $this->form->getAnswerAll($id_rec_h)->result();
		$approval = $this->form->getAllApproval($id_rec_h)->result();
		$data['pemohon'] = $this->form->getDataPemohon($id_rec_h)->row();

		// var_dump($data['pemohon']);

		foreach($answer as $a){
			$value['ip_' . $a->id_pertanyaan] = $a->value;
			$value['ip_sub_' . $a->id_pertanyaan] = $a->sub_value;
		}

		foreach($approval as $a){
			$app['nama_' . $a->seq . "_" . $a->loop_app] = $a->nama;
			$app['approval_' . $a->seq . "_" . $a->loop_app] = $a->status;
			$app['catatan_' . $a->seq . "_" . $a->loop_app] = $a->catatan;
			
			if($a->status){
				if(file_exists('asset/img/sign/sign_' . $a->id_user_app . '.png')){
					$app['sign_' . $a->seq . "_" . $a->loop_app] = base_url() . 'asset/img/sign/sign_' . $a->id_user_app . '.png';
				}else{
					$app['sign_' . $a->seq . "_" . $a->loop_app] = base_url() . 'asset/img/signature.jpg';
				}
			}else{
				$app['sign_' . $a->seq . "_" . $a->loop_app] = base_url() . 'asset/img/empty.png';
			}
		}

		$data["answer"] = $value;
		$data["approval"] = $app;
		$data["no_dokumen"] = "F - 01 / SHE / C06";
		$data["berlaku"] = "September 2021";
		$data["edisi"] = "01";
		$data["revisi"] = "0.0";

		$html = $this->load->view('templates/template_work_permit_new', $data, true);

		$options = new Options();
		$options->set('defaultFont', 'Courier');
		$options->set('isRemoteEnabled', true);
 
		$dompdf = new Dompdf($options);
		$dompdf->loadHtml($html);
 
		$dompdf->setPaper('A4', 'portrait');

		$dompdf->render();

		$pdfOutput = $dompdf->output();

		$filepath = 'assets/form_data/stream_data/work_permit('. $id_rec_h .').pdf';

		file_put_contents($filepath, $pdfOutput);

		$filepath = base_url() . $filepath;

		// $config["msg"] = $filepath;

		// $this->send_wa("text", "6282228909916", $config);

		return $filepath;
	}
}
