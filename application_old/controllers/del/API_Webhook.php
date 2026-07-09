<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class API_Webhook extends CI_Controller {

	function __construct(){
        parent::__construct();
		$this->load->model('M_Form', 'form');
		$this->load->model('M_API', 'api');
    }
	
	public function index(){
		header('Content-Type: application/json; charset=utf-8');

		$json = file_get_contents('php://input');
		$data = json_decode($json, true);

		// Record Semua Chat Yang Masuk
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

		// Setup Data Config Yang Diperlukan
		$to = $data["data"]["from_phone_number"];
		$message = $data["data"]["message"];
		$action_id = $data["data"]["action_id"];
		if(isset($data["data"]["file"])){
			$file = $data["data"]["file"];
		}

		// Dummy Data
		// // $to = '6282228909916';
		// // $message = "1";
		// // $action_id = "1";

		// Setup Data Config Pt. 2
		$sender = '0' . ltrim($to,'62');
		$type = "";
		$mode = "form";

		// Check Button Approval
		if($action_id){
			$check_approval = strpos($action_id, "approval_");

			if(gettype($check_approval) != "boolean"){
				$mode = "start_approval";
			}
		}
		
		//Setup For Approval
		if($mode == "start_approval"){
			$id_bongkar = explode("_",$action_id);
			$id_rec_h_approval = $id_bongkar[1];
			$id_form_approval = $id_bongkar[2];

			$user = $this->form->getUser($sender)->result();
			$id_user = $user[0]->id_user;

			$start_sess_approval = $this->form->recordSessionApproval($id_user, $id_form_approval, $id_rec_h_approval);

			$no_form = $this->form->getNoForm($id_rec_h_approval)->result();

			$msg = "Apakah Anda Akan Melakukan Konfirmasi Untuk Form: *" . $no_form[0]->title . "*, Dengan Nomor Form: *" . $no_form[0]->no_form . "*\\n\\nPratinjau Form:\\nhttps://cpipga.com/FormViewer/index/" . $no_form[0]->no_form;

			$button = array(
				[
					"id" => "1",
					"title" => "Menyetujui"
				],
				[
					"id" => "2",
					"title" => "Menolak"
				]
			);

			$this->send_wa_button($to, $msg, $button);
		}else if($message != "" || isset($file)){

			//Setup Alur Pengisian Form dan Alur Approval
			$sess = $this->form->cekSession($sender)->result();

			if($message == "#form#"){
				if($sess){
					$update_sess = $this->form->updateSession($sess[0]->id_session, 0, 0, 0, 0, 0, 99);
				}

				$form = $this->form->getForm($sender)->result();

				$msg = "";

				
				if($form){
					
					$user = $this->form->getUser($sender)->result();

					$id_user = $user[0]->id_user;

					$record_sess = $this->form->recordSession($id_user);
	
					$msg = "Silahkan Memilih Jenis Form";

					$lists = array();

					$type = "lists";

					foreach($form as $f){
						$config = [
							"id" => $f->id_form,
							"title" => $f->title,
							"description" => "   "
						];

						array_push($lists, $config);
					}
				}

				$this->send_wa_lists($to, $msg, "List Menu", $lists);
			}else{
				if($sess[0]->status == 0){
					$id_form = $action_id;

					$approvalDept = $this->form->getApprovalDept($id_form)->result();
					$deptQuestion = $this->form->getDeptQuestion($id_form)->row();

					$msg = $deptQuestion->pertanyaan_dept . "\\n\\n";
					
					$no_dept = 1;
					foreach($approvalDept as $ad){
						$msg .= $no_dept . ". " . $ad->department . "\\n";
						$no_dept++;
					}

					$update_sess = $this->form->updateSession($sess[0]->id_session, $id_form, 0, 0, 0, 0, 0.1);

					$this->send_wa_text($to, $msg);
				}else if($sess[0]->status == 0.1){
					$postDept = (int)$message - 1;

					$approvalDeptPos = $this->form->getApprovalDept($sess[0]->id_form)->result();

					$jumlah_app = count($approvalDeptPos);

					$flag_answer = true;

					if(($postDept + 1) > $jumlah_app){
						$flag_answer = false;
					}

					if($flag_answer){
						$flag_approval = false;

						$user = $this->form->getUser($sender)->row();
	
						$departmentApproval = $approvalDeptPos[$postDept]->department;
						$plantApproval = $user->plant;
						$id_user_save = $user->id_user;
						$idFormApproval = $sess[0]->id_form;

						$id_rec_h = $this->form->recordFormH($idFormApproval, $id_user_save, $plantApproval, $departmentApproval);
	
						$dataApprovalChoose = $this->form->getApprovalCtr($idFormApproval, $departmentApproval, $plantApproval)->result();
						$dataApprovalNoChoose = $this->form->getApprovalNoChoose($idFormApproval, $departmentApproval, $plantApproval)->result();
	
						if(count($dataApprovalNoChoose) > 0){
							foreach($dataApprovalNoChoose as $danc){
								$record_data_approval = $this->form->recordApproval($id_rec_h, $danc->id_alur, '', 0, $danc->seq_app);
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
							$update_sess = $this->form->updateSession($sess[0]->id_session, $sess[0]->id_form, $id_rec_h, 0, 0, $dataApprovalChoose[0]->seq_app, 0.11);
							
							$this->send_wa_text($to, $msg_list_approval);
						}else{
							$lists = array();

							$pertanyaan = $this->form->getFirstQuestion($action_id)->result();
		
							$id_pertanyaan_pertama = $pertanyaan[0]->id_pertanyaan;
		
							$msg = $pertanyaan[0]->pertanyaan;
		
							if($pertanyaan[0]->tipe_jawaban == 'date'){
								$msg .= "\\n_Format Jawaban : DD-MM-YYYY (Contoh: 23-10-2024)_";
		
								$this->send_wa_text($to, $msg);
							}else if($pertanyaan[0]->tipe_jawaban == 'datetime'){
								$msg .= "\\n_Format Jawaban : DD-MM-YYYY JJ:MM (Contoh: 23-10-2024 15:30)_";
		
								$this->send_wa_text($to, $msg);
							}else if($pertanyaan[0]->tipe_jawaban == 'time'){
								$msg .= "\\n_Format Jawaban : JJ:MM (Contoh: 15:30)_";
		
								$this->send_wa_text($to, $msg);
							}else if($pertanyaan[0]->tipe_jawaban == 'multi_option'){
								$msg .= "\\n_Jawaban Bisa Dipilih Lebih dari 1 (Contoh Format Jawaban : 1,2,3,4,5)_";
		
								$this->send_wa_text($to, $msg);
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
		
								$this->send_wa_lists($to, $msg, "List Jawaban", $lists);
							}else{
								$this->send_wa_text($to, $msg);
							}
	
							$id_rec_h = $this->form->recordFormH($id_form, $id_user, $plant, $departmentApproval);
	
							$rec_d = $this->form->recordFormD($id_rec_h, $id_pertanyaan_pertama);
		
							$update_sess = $this->form->updateSession($sess[0]->id_session, $sess[0]->id_form, $id_rec_h, $id_pertanyaan_pertama, 0, 0, 1);
						}
					}else{
						$approvalDept = $this->form->getApprovalDept($sess[0]->id_form)->result();
						$deptQuestion = $this->form->getDeptQuestion($sess[0]->id_form)->row();

						$msg = "*Jawaban Tidak Ada Dalam Pilihan*\\n\\n" . $deptQuestion->pertanyaan_dept . "\\n\\n";
						
						$no_dept = 1;
						foreach($approvalDept as $ad){
							$msg .= $no_dept . ". " . $ad->department . "\\n";
							$no_dept++;
						}

						$this->send_wa_text($to, $msg);
					}

				}else if($sess[0]->status == 0.11){
					$user = $this->form->getUser($sender)->row();

					$departmentApproval = $this->form->getDeptFromRecH($sess[0]->id_rec_h)->row();

					$departmentApproval = $departmentApproval->department;
					$plantApproval = $user->plant;
					$id_user_save = $user->id_user;
					$idFormApproval = $sess[0]->id_form;
					$seq_now = $sess[0]->seq_app;

					$userApprovalChoose = $this->form->getApprovalBySeq($idFormApproval, $departmentApproval, $plantApproval, $sess[0]->seq_app)->result();

					$userChoose = (int)$message - 1;

					$record_data_approval = $this->form->recordApproval($sess[0]->id_rec_h, $userApprovalChoose[$userChoose]->id_alur, '', 0, $sess[0]->seq_app);

					$dataApprovalChoose = $this->form->getApprovalCtr($idFormApproval, $departmentApproval, $plantApproval)->result();

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

						$update_sess = $this->form->updateSession($sess[0]->id_session, $sess[0]->id_form, $sess[0]->id_rec_h, 0, 0, $seq_hit, 0.11);

						var_dump($msg_list_approval);

						$this->send_wa_text($to, $msg_list_approval);
					}else{
						$lists = array();

						$pertanyaan = $this->form->getFirstQuestion($sess[0]->id_form)->result();
		
						$id_pertanyaan_pertama = $pertanyaan[0]->id_pertanyaan;
	
						$msg = $pertanyaan[0]->pertanyaan;
	
						if($pertanyaan[0]->tipe_jawaban == 'date'){
							$msg .= "\\n_Format Jawaban : DD-MM-YYYY (Contoh: 23-10-2024)_";
	
							$this->send_wa_text($to, $msg);
						}else if($pertanyaan[0]->tipe_jawaban == 'datetime'){
							$msg .= "\\n_Format Jawaban : DD-MM-YYYY JJ:MM (Contoh: 23-10-2024 15:30)_";
	
							$this->send_wa_text($to, $msg);
						}else if($pertanyaan[0]->tipe_jawaban == 'time'){
							$msg .= "\\n_Format Jawaban : JJ:MM (Contoh: 15:30)_";
	
							$this->send_wa_text($to, $msg);
						}else if($pertanyaan[0]->tipe_jawaban == 'multi_option'){
							$msg .= "\\n_Jawaban Bisa Dipilih Lebih dari 1 (Contoh Format Jawaban : 1,2,3,4,5)_";
	
							$this->send_wa_text($to, $msg);
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
	
							$this->send_wa_lists($to, $msg, "List Jawaban", $lists);
						}else{
							$this->send_wa_text($to, $msg);
						}

						$rec_d = $this->form->recordFormD($sess[0]->id_rec_h, $id_pertanyaan_pertama);
	
						$update_sess = $this->form->updateSession($sess[0]->id_session, $sess[0]->id_form, $sess[0]->id_rec_h, $id_pertanyaan_pertama, 0, 0, 1);
					}
				}else if($sess[0]->status == 1){
					$pertanyaan = $this->form->getPertanyaan($sess[0]->id_pertanyaan)->result();

					$file_now = 0;

					if($pertanyaan[0]->tipe_jawaban == "single_option"){
						$message = $action_id;
					}else if($pertanyaan[0]->tipe_jawaban == "picture" || $pertanyaan[0]->tipe_jawaban == "document"){
						$message = $file;
					}

					$result = $this->check_input($pertanyaan[0]->tipe_jawaban, $message, $sess[0]->id_pertanyaan);

					if(!$result['status'] || $result['status'] == 'error'){
						$msg = "*Jawaban Tidak Benar*\\n" . $pertanyaan[0]->pertanyaan;

						if($pertanyaan[0]->tipe_jawaban == 'date'){
							$msg .= "\\n_Format Jawaban : DD-MM-YYYY (Contoh: 23-10-2024)_";
	
							$this->send_wa_text($to, $msg);
						}else if($pertanyaan[0]->tipe_jawaban == 'datetime'){
							$msg .= "\\n_Format Jawaban : DD-MM-YYYY JJ:MM (Contoh: 23-10-2024 15:30)_";
	
							$this->send_wa_text($to, $msg);
						}else if($pertanyaan[0]->tipe_jawaban == 'time'){
							$msg .= "\\n_Format Jawaban : JJ:MM (Contoh: 15:30)_";
	
							$this->send_wa_text($to, $msg);
						}else if($pertanyaan[0]->tipe_jawaban == 'multi_option'){
							$msg .= "\\n_Jawaban Bisa Dipilih Lebih dari 1 (Contoh Format Jawaban : 1,2,3,4,5)_";

							$opsi = $this->form->getOpsiJawaban($pertanyaan[0]->id_pertanyaan)->result();

							$msg .= "\\n";
							
							foreach($opsi as $o){
								$msg .= "\\n" . $o->seq_jawaban . ". " . $o->opsi_jawaban;
							}
	
							$this->send_wa_text($to, $msg);
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
	
							$this->send_wa_lists($to, $msg, "List Jawaban", $lists);
						}else{
							$this->send_wa_text($to, $msg);
						}
					}else if($result['status'] == "answer"){
						$id_rec_d = $this->form->getIDRecD($sess[0]->id_rec_h)->result();

						// var_dump($sess);

						if($pertanyaan[0]->as_caption){
							$caption = $this->form->getCaptionForm($sess[0]->id_rec_h)->result();

							$caption_form = $caption[0]->caption_form;

							if($caption_form){
								$caption_form .= " | " . $result['result'];
							}else{
								$caption_form .= $result['result'];
							}

							$updateCaption = $this->form->updateCaptionRecH($sess[0]->id_rec_h, $caption_form);
						}

						$send_data = $result['result'];

						$resultUpdate = $this->form->updateValueRecordFormD($id_rec_d[0]->id_rec_form_d, $send_data);

						$checkQuestion = $this->form->checkNextQuestion($sess[0]->id_form, $sess[0]->id_rec_h)->result();

						if($checkQuestion[0]->status == "NO"){
							$seq_next = (int)$checkQuestion[0]->jawaban + 1;

							$nextQuestion = $this->form->getPertanyaanBySeq($sess[0]->id_form, $seq_next)->result();

							$msg = $nextQuestion[0]->pertanyaan;

							if($nextQuestion[0]->tipe_jawaban == 'date'){
								$msg .= "\\n_Format Jawaban : DD-MM-YYYY (Contoh: 23-10-2024)_";
		
								$this->send_wa_text($to, $msg);
							}else if($nextQuestion[0]->tipe_jawaban == 'datetime'){
								$msg .= "\\n_Format Jawaban : DD-MM-YYYY JJ:MM (Contoh: 23-10-2024 15:30)_";
		
								$this->send_wa_text($to, $msg);
							}else if($nextQuestion[0]->tipe_jawaban == 'time'){
								$msg .= "\\n_Format Jawaban : JJ:MM (Contoh: 15:30)_";
		
								$this->send_wa_text($to, $msg);
							}else if($nextQuestion[0]->tipe_jawaban == 'multi_option'){
								$msg .= "\\n_Jawaban Bisa Dipilih Lebih dari 1 (Contoh Format Jawaban : 1,2,3,4,5)_";

								$opsi = $this->form->getOpsiJawaban($nextQuestion[0]->id_pertanyaan)->result();

								$msg .= "\\n";
								
								foreach($opsi as $o){
									$msg .= "\\n" . $o->seq_jawaban . ". " . $o->opsi_jawaban;
								}
		
								$this->send_wa_text($to, $msg);
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
		
								$this->send_wa_lists($to, $msg, "List Jawaban", $lists);
							}else{
								$this->send_wa_text($to, $msg);
							}

							$rec_d = $this->form->recordFormD($sess[0]->id_rec_h, $nextQuestion[0]->id_pertanyaan);

							$update_sess = $this->form->updateSession($sess[0]->id_session, $sess[0]->id_form, $sess[0]->id_rec_h, $nextQuestion[0]->id_pertanyaan, 0, 0, 1);
						}else{
							$update_sess = $this->form->updateSession($sess[0]->id_session, $sess[0]->id_form, $sess[0]->id_rec_h, 0, 0, 0, 2);

							$no_form = $this->form->getNoForm($sess[0]->id_rec_h)->result();

							$msg = "Silahkan Melihat Pratinjau Dari Form yang Sudah Diisi Pada Form Dibawah Ini:\\n\\nhttps://cpipga.com/FormViewer/index/" . $no_form[0]->no_form . "\\n\\nApakah Anda Ingin Mengonfirmasi Form Tersebut?";

							$button = array(
								[
									"id" => "1",
									"title" => "Konfirmasi"
								],
								[
									"id" => "2",
									"title" => "Anulir / Batalkan"
								]
							);

							$this->send_wa_button($to, $msg, $button);
						}
					}else if($result['status'] == "hold"){
						$msg = $result['result'];

						if($pertanyaan[0]->as_caption){
							$caption = $this->form->getCaptionForm($sess[0]->id_rec_h)->result();

							$caption_form = $caption[0]->caption_form;

							if($caption_form){
								$caption_form .= " | " . $result['sub_result'];
							}else{
								$caption_form .= $result['sub_result'];
							}

							$updateCaption = $this->form->updateCaptionRecH($sess[0]->id_rec_h, $caption_form);
						}

						$id_rec_d = $this->form->getIDRecD($sess[0]->id_rec_h)->result();

						$opsi_jawaban = $this->form->getOpsiJawabanValue($result['id_opsi'])->result();

						$resultUpdate = $this->form->updateValueRecordFormD($id_rec_d[0]->id_rec_form_d, $opsi_jawaban[0]->opsi_jawaban);

						$update_sess = $this->form->updateSession($sess[0]->id_session, $sess[0]->id_form, $sess[0]->id_rec_h, $sess[0]->id_pertanyaan, $result['id_opsi'], 0, 1.1);

						$this->send_wa_text($to, $msg);
					}
				}else if($sess[0]->status == 1.1){
					$id_rec_d = $this->form->getIDRecDHasValue($sess[0]->id_rec_h)->result();

					$resultUpdate = $this->form->updateSubValueRecordFormD($id_rec_d[0]->id_rec_form_d, $message);

					$checkQuestion = $this->form->checkNextQuestion($sess[0]->id_form, $sess[0]->id_rec_h)->result();

					$caption = $this->form->getCaptionForm($sess[0]->id_rec_h)->result();

					$caption_form = $caption[0]->caption_form;

					$caption_form .= " - " . $message;

					$updateCaption = $this->form->updateCaptionRecH($sess[0]->id_rec_h, $caption_form);

					if($checkQuestion[0]->status == "NO"){
						$seq_next = (int)$checkQuestion[0]->jawaban + 1;

						$nextQuestion = $this->form->getPertanyaanBySeq($sess[0]->id_form, $seq_next)->result();

						$msg = $nextQuestion[0]->pertanyaan;

						if($nextQuestion[0]->tipe_jawaban == 'date'){
							$msg .= "\\n_Format Jawaban : DD-MM-YYYY (Contoh: 23-10-2024)_";
	
							$this->send_wa_text($to, $msg);
						}else if($nextQuestion[0]->tipe_jawaban == 'datetime'){
							$msg .= "\\n_Format Jawaban : DD-MM-YYYY JJ:MM (Contoh: 23-10-2024 15:30)_";
	
							$this->send_wa_text($to, $msg);
						}else if($nextQuestion[0]->tipe_jawaban == 'time'){
							$msg .= "\\n_Format Jawaban : JJ:MM (Contoh: 15:30)_";
	
							$this->send_wa_text($to, $msg);
						}else if($nextQuestion[0]->tipe_jawaban == 'multi_option'){
							$msg .= "\\n_Jawaban Bisa Dipilih Lebih dari 1 (Contoh Format Jawaban : 1,2,3,4,5)_";

							$opsi = $this->form->getOpsiJawaban($nextQuestion[0]->id_pertanyaan)->result();

							$msg .= "\\n";
							
							foreach($opsi as $o){
								$msg .= "\\n" . $o->seq_jawaban . ". " . $o->opsi_jawaban;
							}
	
							$this->send_wa_text($to, $msg);
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
	
							$this->send_wa_lists($to, $msg, "List Jawaban", $lists);
						}else{
							$this->send_wa_text($to, $msg);
						}

						$rec_d = $this->form->recordFormD($sess[0]->id_rec_h, $nextQuestion[0]->id_pertanyaan);

						$update_sess = $this->form->updateSession($sess[0]->id_session, $sess[0]->id_form, $sess[0]->id_rec_h, $nextQuestion[0]->id_pertanyaan, 0, 0, 1);
					}else{
						$update_sess = $this->form->updateSession($sess[0]->id_session, $sess[0]->id_form, $sess[0]->id_rec_h, 0, 0, 0, 2);

						$no_form = $this->form->getNoForm($sess[0]->id_rec_h)->result();

						$msg = "Silahkan Melihat Pratinjau Dari Form yang Sudah Diisi Pada Form Dibawah Ini:\\n\\nhttps://cpipga.com/FormViewer/index/" . $no_form[0]->no_form . "\\n\\nApakah Anda Ingin Mengonfirmasi Form Tersebut?";

						$button = array(
							[
								"id" => "1",
								"title" => "Konfirmasi"
							],
							[
								"id" => "2",
								"title" => "Anulir / Batalkan"
							]
						);

						$this->send_wa_button($to, $msg, $button);
					}
				}else if($sess[0]->status == 2){
					if($action_id == 1){
						$createdStatus = $this->form->recordFormHCreated($sess[0]->id_rec_h);

						$update_sess = $this->form->updateSession($sess[0]->id_session, $sess[0]->id_form, $sess[0]->id_rec_h, 0, 0, 0, 7);

						$app_user = $this->form->getAppUser($sess[0]->id_rec_h)->result();

						$msg = "";

						$no_form = $this->form->getNoForm($sess[0]->id_rec_h)->result();

						if($app_user){
							$msg = "Form _*" . $no_form[0]->no_form . "*_ Telah Terkirim Ke: _*" . $app_user[0]->caption . "*_ untuk Dilkukan Persetujuan";

							$formTitle = $this->form->getTitleForm($no_form[0]->no_form)->result();
							
							$contact_user = '62' . ltrim($app_user[0]->no_hp,'0');

							$this->send_wa_text($to, $msg);
							
							//Using Template
							$this->send_wa_approval($contact_user, $sess[0]->id_rec_h, $sess[0]->id_form, $formTitle[0]->title, $no_form[0]->no_form);

							//Using Without Template
							/*$msg_approval = "_*Notifikasi*_\\nForm : _*" . $formTitle[0]->title . "*_ Dengan Nomor : _*" . $no_form[0]->no_form . "*_ Menunggu Untuk Persetujuan";
							$button = array(
								[
									"id" => "approval_" . $sess[0]->id_rec_h . "_" . $sess[0]->id_form,
									"title" => "Memulai Persetujuan"
								]
							);
							$this->send_wa_button($contact_user, $msg_approval, $button);*/
						}

						$update_sess = $this->form->updateSession($sess[0]->id_session, 0, 0, 0, 0, 0, 99);

					}else if($action_id == 2){
						$update_sess = $this->form->updateSession($sess[0]->id_session, $sess[0]->id_form, $sess[0]->id_rec_h, 0, 0, 0, 3);

						$msg = "Apakah Anda Mau Melakukan Anulir Jawaban Form Atau Membatalkan Pengisian Form ?";

						$button = array(
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

						$this->send_wa_button($to, $msg, $button);
					}
				}else if($sess[0]->status == 3){
					if($action_id == 1){
						$update_sess = $this->form->updateSession($sess[0]->id_session, $sess[0]->id_form, $sess[0]->id_rec_h, 0, 0, 0, 5);

						$allPertanyaan = $this->form->getAllPertanyaan($sess[0]->id_form)->result();

						$msg = "Silahkan Memilih Nomor Pertanyaan yang Ingin Dirubah:\\n";

						foreach($allPertanyaan as $ap){
							$msg .= "\\n" . $ap->seq . ". " . $ap->pertanyaan;
						}

						$msg .= "\\n\\n" . "99. Kembali Pratinjau Form";

						$this->send_wa_text($to, $msg);
					}else if($action_id == 2){
						$update_sess = $this->form->updateSession($sess[0]->id_session, $sess[0]->id_form, $sess[0]->id_rec_h, 0, 0, 0, 4);

						$no_form = $this->form->getNoForm($sess[0]->id_rec_h)->result();

						$msg = "Form : _*" . $no_form[0]->no_form . "*_ Berhasil Dibatalkan.";

						$update_sess = $this->form->updateSession($sess[0]->id_session, 0, 0, 0, 0, 0, 99);

						$this->send_wa_text($to, $msg);
					}else if($action_id == 3){
						$update_sess = $this->form->updateSession($sess[0]->id_session, $sess[0]->id_form, $sess[0]->id_rec_h, 0, 0, 0, 2);

						$no_form = $this->form->getNoForm($sess[0]->id_rec_h)->result();

						$msg = "Silahkan Melihat Pratinjau Dari Form yang Sudah Diisi Pada Form Dibawah Ini:\\n\\nhttps://cpipga.com/FormViewer/index/" . $no_form[0]->no_form . "\\n\\nApakah Anda Ingin Mengonfirmasi Form Tersebut?";

						$button = array(
							[
								"id" => "1",
								"title" => "Konfirmasi"
							],
							[
								"id" => "2",
								"title" => "Anulir / Batalkan"
							]
						);

						$this->send_wa_button($to, $msg, $button);
					}
				}else if($sess[0]->status == 5){
					if($message == 99){
						$update_sess = $this->form->updateSession($sess[0]->id_session, $sess[0]->id_form, $sess[0]->id_rec_h, 0, 0, 0, 2);

						$no_form = $this->form->getNoForm($sess[0]->id_rec_h)->result();

						$msg = "Silahkan Melihat Pratinjau Dari Form yang Sudah Diisi Pada Form Dibawah Ini:\\n\\nhttps://cpipga.com/FormViewer/index/" . $no_form[0]->no_form . "\\n\\nApakah Anda Ingin Mengonfirmasi Form Tersebut?";

						$button = array(
							[
								"id" => "1",
								"title" => "Konfirmasi"
							],
							[
								"id" => "2",
								"title" => "Anulir / Batalkan"
							]
						);

						$this->send_wa_button($to, $msg, $button);
					}else{
						$pertanyaanNow = $this->form->getPertanyaanBySeq($sess[0]->id_form, $message)->result();
	
						$jawabanSebelumnya = $this->form->getJawaban($sess[0]->id_rec_h, $pertanyaanNow[0]->id_pertanyaan)->result();
	
						if($pertanyaanNow){
							$msg = "Jawaban Sebelumnya : _*" . $jawabanSebelumnya[0]->value;

							if($jawabanSebelumnya[0]->sub_value != ""){
								$msg .= " - " . $jawabanSebelumnya[0]->sub_value;
							}
	
							$msg .= "*_\\n\\nSilahkan Memilih / Memasukkan Jawaban Yang Baru\\n\\n";

							$pertanyaanEdit = $this->form->getPertanyaan($pertanyaanNow[0]->id_pertanyaan)->result();
	
							$msg .= $pertanyaanEdit[0]->pertanyaan;

							if($pertanyaanEdit[0]->tipe_jawaban == 'date'){
								$msg .= "\\n_Format Jawaban : DD-MM-YYYY (Contoh: 23-10-2024)_";
		
								$this->send_wa_text($to, $msg);
							}else if($pertanyaanEdit[0]->tipe_jawaban == 'datetime'){
								$msg .= "\\n_Format Jawaban : DD-MM-YYYY JJ:MM (Contoh: 23-10-2024 15:30)_";
		
								$this->send_wa_text($to, $msg);
							}else if($pertanyaanEdit[0]->tipe_jawaban == 'time'){
								$msg .= "\\n_Format Jawaban : JJ:MM (Contoh: 15:30)_";
		
								$this->send_wa_text($to, $msg);
							}else if($pertanyaanEdit[0]->tipe_jawaban == 'multi_option'){
								$msg .= "\\n_Jawaban Bisa Dipilih Lebih dari 1 (Contoh Format Jawaban : 1,2,3,4,5)_";
	
								$opsi = $this->form->getOpsiJawaban($pertanyaanEdit[0]->id_pertanyaan)->result();
	
								$msg .= "\\n";
								
								foreach($opsi as $o){
									$msg .= "\\n" . $o->seq_jawaban . ". " . $o->opsi_jawaban;
								}
		
								$this->send_wa_text($to, $msg);
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
		
								$this->send_wa_lists($to, $msg, "List Jawaban", $lists);
							}else{
								$this->send_wa_text($to, $msg);
							}

							$update_sess = $this->form->updateSession($sess[0]->id_session, $sess[0]->id_form, $sess[0]->id_rec_h, $pertanyaanEdit[0]->id_pertanyaan, 0, 0, 6);
						}else{
							$allPertanyaan = $this->form->getAllPertanyaan($sess[0]->id_form)->result();

							$msg = "Silahkan Memilih Nomor Pertanyaan yang Ingin Dirubah:\\n";

							foreach($allPertanyaan as $ap){
								$msg .= "\\n" . $ap->seq . ". " . $ap->pertanyaan;
							}

							$msg .= "\\n\\n" . "99. Kembali Pratinjau Form";

							$this->send_wa_text($to, $msg);
						}
					}
				}else if($sess[0]->status == 6){
					$pertanyaan = $this->form->getPertanyaan($sess[0]->id_pertanyaan)->result();

					if($pertanyaan[0]->tipe_jawaban == "single_option"){
						$message = $action_id;
					}

					$result = $this->check_input($pertanyaan[0]->tipe_jawaban, $message, $sess[0]->id_pertanyaan);
					
					if(!$result['status'] || $result['status'] == 'error'){
						$msg = "Jawaban Tidak Benar\\n" . $pertanyaan[0]->pertanyaan;

						if($pertanyaan[0]->tipe_jawaban == 'date'){
							$msg .= "\\n_Format Jawaban : DD-MM-YYYY (Contoh: 23-10-2024)_";
	
							$this->send_wa_text($to, $msg);
						}else if($pertanyaan[0]->tipe_jawaban == 'datetime'){
							$msg .= "\\n_Format Jawaban : DD-MM-YYYY JJ:MM (Contoh: 23-10-2024 15:30)_";
	
							$this->send_wa_text($to, $msg);
						}else if($pertanyaan[0]->tipe_jawaban == 'time'){
							$msg .= "\\n_Format Jawaban : JJ:MM (Contoh: 15:30)_";
	
							$this->send_wa_text($to, $msg);
						}else if($pertanyaan[0]->tipe_jawaban == 'multi_option'){
							$msg .= "\\n_Jawaban Bisa Dipilih Lebih dari 1 (Contoh Format Jawaban : 1,2,3,4,5)_";

							$opsi = $this->form->getOpsiJawaban($pertanyaan[0]->id_pertanyaan)->result();

								$msg .= "\\n";
								
								foreach($opsi as $o){
									$msg .= "\\n" . $o->seq_jawaban . ". " . $o->opsi_jawaban;
								}
		
								$this->send_wa_text($to, $msg);
	
							$this->send_wa_text($to, $msg);
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
	
							$this->send_wa_lists($to, $msg, "List Jawaban", $lists);
						}else{
							$this->send_wa_text($to, $msg);
						}
					}else if($result['status'] == "answer"){
						$id_rec_d = $this->form->getJawaban($sess[0]->id_rec_h, $sess[0]->id_pertanyaan)->result();

						$send_data = $result['result'];

						$allCaption = $this->form->getSeqCaption($sess[0]->id_form, $sess[0]->id_pertanyaan)->result();

						$caption = $this->form->getCaptionForm($sess[0]->id_rec_h)->result();

						$caption_form = $caption[0]->caption_form;

						$caption_break = explode(" | ", $caption_form);

						$caption_break[$allCaption[0]->nomor] = $send_data;

						$caption_anulir = implode(" | ", $caption_break);

						$updateCaption = $this->form->updateCaptionRecH($sess[0]->id_rec_h, $caption_anulir);

						$resultUpdate = $this->form->updateValueRecordFormD($id_rec_d[0]->id_rec_form_d, $send_data);
						$resultUpdate = $this->form->updateSubValueRecordFormD($id_rec_d[0]->id_rec_form_d, "");

						$update_sess = $this->form->updateSession($sess[0]->id_session, $sess[0]->id_form, $sess[0]->id_rec_h, $pertanyaanNow[0]->id_pertanyaan, 0, 0, 5);

						$allPertanyaan = $this->form->getAllPertanyaan($sess[0]->id_form)->result();

						$msg = "_*Jawaban Berhasil Dirubah*_\\n\\nSilahkan Memilih Nomor Pertanyaan yang Ingin Dirubah:\\n\\n";
						foreach($allPertanyaan as $ap){
							$msg .= $ap->seq . ". " . $ap->pertanyaan . "\\n";
						}

						$msg .= "\\n\\n99. Kembali Pratinjau Form";
					}else if($result['status'] == "hold"){
						$msg = $result['result'];

						$id_rec_d = $this->form->getJawaban($sess[0]->id_rec_h, $sess[0]->id_pertanyaan)->result();

						$opsi_jawaban = $this->form->getOpsiJawabanValue($result['id_opsi'])->result();

						$allCaption = $this->form->getSeqCaption($sess[0]->id_form, $sess[0]->id_pertanyaan)->result();

						$caption = $this->form->getCaptionForm($sess[0]->id_rec_h)->result();

						$caption_form = $caption[0]->caption_form;

						$caption_break = explode(" | ", $caption_form);

						$caption_break[$allCaption[0]->nomor] = $result['sub_result'];

						$caption_anulir = implode(" | ", $caption_break);

						$updateCaption = $this->form->updateCaptionRecH($sess[0]->id_rec_h, $caption_anulir);

						$resultUpdate = $this->form->updateValueRecordFormD($id_rec_d[0]->id_rec_form_d, $opsi_jawaban[0]->opsi_jawaban);

						$update_sess = $this->form->updateSession($sess[0]->id_session, $sess[0]->id_form, $sess[0]->id_rec_h, $sess[0]->id_pertanyaan, $result['id_opsi'], 0, 6.1);

						$this->send_wa_text($to, $msg);
					}
				}else if($sess[0]->status == 6.1){
					$id_rec_d = $this->form->getJawaban($sess[0]->id_rec_h, $sess[0]->id_pertanyaan)->result();

					$resultUpdate = $this->form->updateSubValueRecordFormD($id_rec_d[0]->id_rec_form_d, $message);

					$update_sess = $this->form->updateSession($sess[0]->id_session, $sess[0]->id_form, $sess[0]->id_rec_h, $pertanyaanNow[0]->id_pertanyaan, 0, 0, 5);

					$allCaption = $this->form->getSeqCaption($sess[0]->id_form, $sess[0]->id_pertanyaan)->result();

					$caption = $this->form->getCaptionForm($sess[0]->id_rec_h)->result();

					$caption_form = $caption[0]->caption_form;

					$caption_break = explode(" | ", $caption_form);

					$caption_break[$allCaption[0]->nomor] = $caption_break[$allCaption[0]->nomor] . " - " . $message;

					$caption_anulir = implode(" | ", $caption_break);

					$updateCaption = $this->form->updateCaptionRecH($sess[0]->id_rec_h, $caption_anulir);

					$allPertanyaan = $this->form->getAllPertanyaan($sess[0]->id_form)->result();

					$msg = "_*Jawaban Berhasil Dirubah*_\\n\\nSilahkan Memilih Nomor Pertanyaan yang Ingin Dirubah:\\n";
					foreach($allPertanyaan as $ap){
						$msg .= "\\n" . $ap->seq . ". " . $ap->pertanyaan;
					}

					$msg .= "\\n\\n99. Kembali Pratinjau Form";

					$this->send_wa_text($to, $msg);
				}else if($sess[0]->status == 10){
					$userPemohon = $this->form->getUserPemohon($sess[0]->id_rec_h)->result();

					$to_pemohon = $userPemohon[0]->no_hp;

					$to_pemohon = '62' . ltrim($to_pemohon,'0');

					if($action_id == 1){
						$id_form_app = $sess[0]->id_form;
						$status_app = $action_id;

						$checkAppUser = $this->form->nextAppUser($sess[0]->id_rec_h)->result();

						$seq_app_now = $checkAppUser[0]->seq;

						$configAppNow = $this->form->getConfigApp($id_form_app, $seq_app_now, $status_app)->result();

						if($configAppNow){
							$seq_config_now = 0;

							if(count($configAppNow) > 1){
								$seq_config_now = (int)$configAppNow[0]->seq_config;
							}

							$pertanyaan_approval = $configAppNow[0]->pertanyaan_config;
	
							$data = [
								"msg" => $pertanyaan_approval
							];
	
							$this->send_wa("text", $to, $data);

							$update_sess = $this->form->updateSession($sess[0]->id_session, $sess[0]->id_form, $sess[0]->id_rec_h, 0, 0, $seq_config_now, 10.1);
						}else{
							$UserAppNow = $this->form->nextAppUser($sess[0]->id_rec_h)->result();

							$updateApproval = $this->form->updateApproval($sess[0]->id_rec_h, $UserAppNow[0]->id_alur, "", 1);

							$nextUserApp = $this->form->nextAppUser($sess[0]->id_rec_h)->result();

							$no_form = $this->form->getNoForm($sess[0]->id_rec_h)->result();

							if(nextUserApp){
								$contact_user = '62' . ltrim($nextUserApp[0]->no_hp,'0');
		
								// =============== Send Message With Template =============== \\
								
								$user_approval_now = $this->form->getUser($to)->result();
		
								$msg_to_pemohon = "Telah _*Disetujui*_ Oleh: *" . $UserAppNow[0]->jabatan . " - " . $UserAppNow[0]->caption . "* Form Ini Sedang Dikirim Menuju: *" . $nextUserApp[0]->jabatan . " - " . $nextUserApp[0]->caption . "* Untuk Dilakukan Persetujuan Form";
								
								$this->send_wa_approval($contact_user, $sess[0]->id_rec_h, $sess[0]->id_form, $no_form[0]->title, $no_form[0]->no_form);
								
								$this->send_wa_notif($to_pemohon, $no_form[0]->title, $no_form[0]->no_form, $msg_to_pemohon);
		
								$msg = "Form: " . $no_form[0]->title . ", Dengan No Form: " . $no_form[0]->no_form . " Telah Berhasil *_Disetujui_*";
		
								$this->send_wa_text($to, $msg);
							}else{
								$approvedStatus = $this->form->recordFormHApproved($sess[0]->id_rec_h);
		
								// =============== Send Message With Template =============== \\
		
								$msg_to_pemohon = "Telah Berhasil Disetujui";
								$this->send_wa_notif($to_pemohon, $no_form[0]->title, $no_form[0]->no_form, $msg_to_pemohon);
		
								$msg = "Form: " . $no_form[0]->title . ", Dengan No Form: " . $no_form[0]->no_form . " Telah Berhasil *_Disetujui_*";
		
								$this->send_wa_text($to, $msg);
							}
		
							$update_sess = $this->form->updateSession($sess[0]->id_session, $sess[0]->id_form, $sess[0]->id_rec_h, 0, 0, 0, 12);
		
							$update_sess = $this->form->updateSession($sess[0]->id_session, 0, 0, 0, 0, 0, 99);
						}
					}else if($action_id == 2){
						$id_form_app = $sess[0]->id_form;
						$status_app = $action_id;

						$checkAppUser = $this->form->nextAppUser($sess[0]->id_rec_h)->result();

						$seq_app_now = $checkAppUser[0]->seq;

						$configAppNow = $this->form->getConfigApp($id_form_app, $seq_app_now, $status_app)->result();

						if($configAppNow){
							$seq_config_now = 0;

							if(count($configAppNow) > 1){
								$seq_config_now = $configAppNow[0]->seq_config;
							}

							$pertanyaan_approval = $configAppNow[0]->pertanyaan_config;
	
							$data = [
								"msg" => $pertanyaan_approval
							];
	
							$this->send_wa("text", $to, $data);

							$update_sess = $this->form->updateSession($sess[0]->id_session, $sess[0]->id_form, $sess[0]->id_rec_h, 0, 0, $seq_config_now, 10.2);
						}else{
							$userPemohon = $this->form->getUserPemohon($sess[0]->id_rec_h)->result();

							$to_pemohon = $userPemohon[0]->no_hp;

							$to_pemohon = '62' . ltrim($to_pemohon,'0');

							$no_form = $this->form->getNoForm($sess[0]->id_rec_h)->result();

							$UserAppNow = $this->form->nextAppUser($sess[0]->id_rec_h)->result();

							$updateApproval = $this->form->updateApproval($sess[0]->id_rec_h, $UserAppNow[0]->id_alur, $message, 2);

							$msg = "Form: " . $no_form[0]->title . ", Dengan No Form: " . $no_form[0]->no_form . " Telah *_Ditolak_*";
							
							$this->send_wa_text($to, $msg);

							$msg_to_pemohon = "Telah _*Ditolak*_ Oleh: *" . $UserAppNow[0]->jabatan . " - " . $UserAppNow[0]->caption . "*";

							$this->send_wa_notif($to_pemohon, $no_form[0]->title, $no_form[0]->no_form, $msg_to_pemohon);

							$update_sess = $this->form->updateSession($sess[0]->id_session, $sess[0]->id_form, $sess[0]->id_rec_h, 0, 0, 0, 14);

							$update_sess = $this->form->updateSession($sess[0]->id_session, 0, 0, 0, 0, 0, 99);
						}
					}
				}else if($sess[0]->status == 10.1){
					$userPemohon = $this->form->getUserPemohon($sess[0]->id_rec_h)->result();

					$to_pemohon = $userPemohon[0]->no_hp;

					$to_pemohon = '62' . ltrim($to_pemohon,'0');

					$id_form_app = $sess[0]->id_form;
					$status_app = $action_id;

					$checkAppUser = $this->form->nextAppUser($sess[0]->id_rec_h)->result();

					$seq_app_now = $checkAppUser[0]->seq;

					$configAppNow = $this->form->getConfigApp($id_form_app, $seq_app_now, 1)->result();

					$seq_quest_now = (int)$sess[0]->seq_app;

					$id_rec_app = $this->form->getIdRecDByAlur($sess[0]->id_rec_h, $checkAppUser[0]->id_alur)->row()->id_rec_form_app;

					$recordApprovalDetail = $this->form->recordApprovalDetail($id_rec_app, $configAppNow[0]->id_config_app, $message);

					if($seq_quest_now > 0){

					}else{

						$updateApproval = $this->form->updateApproval($sess[0]->id_rec_h, $checkAppUser[0]->id_alur, "", 1);
					}

					$nextUserApp = $this->form->nextAppUser($sess[0]->id_rec_h)->result();

					$no_form = $this->form->getNoForm($sess[0]->id_rec_h)->result();

					if($nextUserApp){
						$contact_user = '62' . ltrim($nextUserApp[0]->no_hp,'0');

						// =============== Send Message With Template =============== \\
						
						$user_approval_now = $this->form->getUser($to)->result();

						$msg_to_pemohon = "Telah _*Disetujui*_ Oleh: *" . $UserAppNow[0]->jabatan . " - " . $UserAppNow[0]->caption . "* Form Ini Sedang Dikirim Menuju: *" . $nextUserApp[0]->jabatan . " - " . $nextUserApp[0]->caption . "* Untuk Dilakukan Persetujuan Form";
						
						$this->send_wa_approval($contact_user, $sess[0]->id_rec_h, $sess[0]->id_form, $no_form[0]->title, $no_form[0]->no_form);
						
						$this->send_wa_notif($to_pemohon, $no_form[0]->title, $no_form[0]->no_form, $msg_to_pemohon);

						$msg = "Form: " . $no_form[0]->title . ", Dengan No Form: " . $no_form[0]->no_form . " Telah Berhasil *_Disetujui_*";

						$this->send_wa_text($to, $msg);
					}else{
						$approvedStatus = $this->form->recordFormHApproved($sess[0]->id_rec_h);

						// =============== Send Message With Template =============== \\

						$msg_to_pemohon = "Telah Berhasil Disetujui";
						$this->send_wa_notif($to_pemohon, $no_form[0]->title, $no_form[0]->no_form, $msg_to_pemohon);

						$msg = "Form: " . $no_form[0]->title . ", Dengan No Form: " . $no_form[0]->no_form . " Telah Berhasil *_Disetujui_*";

						$this->send_wa_text($to, $msg);
					}

					$update_sess = $this->form->updateSession($sess[0]->id_session, $sess[0]->id_form, $sess[0]->id_rec_h, 0, 0, 0, 12);

					$update_sess = $this->form->updateSession($sess[0]->id_session, 0, 0, 0, 0, 0, 99);
				}else if($sess[0]->status == 11){
					// $checkAppUser = $this->form->checkAppUser($sess[0]->id_rec_h, $sess[0]->id_user)->result();

					$userPemohon = $this->form->getUserPemohon($sess[0]->id_rec_h)->result();

					$to_pemohon = $userPemohon[0]->no_hp;

					$to_pemohon = '62' . ltrim($to_pemohon,'0');

					$no_form = $this->form->getNoForm($sess[0]->id_rec_h)->result();

					$alurApp = $this->form->getAlurApp($sess[0]->id_user, $sess[0]->id_form, $userPemohon[0]->plant)->result();

					// $recordApprovalForm = $this->form->recordApproval($sess[0]->id_rec_h, $alurApp[0]->id_alur, $message, 1);

					$UserAppNow = $this->form->nextAppUser($sess[0]->id_rec_h)->result();

					$updateApproval = $this->form->updateApproval($sess[0]->id_rec_h, $UserAppNow[0]->id_alur, $message, 1);

					$nextUserApp = $this->form->nextAppUser($sess[0]->id_rec_h)->result();

					if(nextUserApp){
						$contact_user = '62' . ltrim($nextUserApp[0]->no_hp,'0');

						// =============== Send Message With Template =============== \\
						
						$user_approval_now = $this->form->getUser($to)->result();

						$msg_to_pemohon = "Telah _*Disetujui*_ Oleh: *" . $UserAppNow[0]->jabatan . " - " . $UserAppNow[0]->caption . "* Dengan Catatan : *" . $message . "*. Form Ini Sedang Dikirim Menuju: *" . $nextUserApp[0]->jabatan . " - " . $nextUserApp[0]->caption . "* Untuk Dilakukan Persetujuan Form";
						
						$this->send_wa_approval($contact_user, $sess[0]->id_rec_h, $sess[0]->id_form, $no_form[0]->title, $no_form[0]->no_form);
						
						$this->send_wa_notif($to_pemohon, $no_form[0]->title, $no_form[0]->no_form, $msg_to_pemohon);

						$msg = "Form: " . $no_form[0]->title . ", Dengan No Form: " . $no_form[0]->no_form . " Telah Berhasil *_Disetujui_* Dengan Catatan : " . $message;

						$this->send_wa_text($to, $msg);

						// =============== Send Message Without Template =============== \\

						/*$msg_to_pemohon = "Form _*" . $no_form[0]->no_form . "*_ Telah Terkirim Ke: _*" . $nextUserApp[0]->caption . "*_ untuk Dilkukan Approval";
						$this->send_wa_text($to_pemohon, $msg_to_pemohon);

						$msg = "_*Notifikasi*_\\nForm : _*" . $no_form[0]->title . "*_ Dengan Nomor : _*" . $no_form[0]->no_form . "*_ Menunggu Untuk Approval";
						$button = array(
							[
								"id" => "approval_" . $sess[0]->id_rec_h . "_" . $sess[0]->id_form,
								"title" => "Memulai Approval"
							]
						);
						$this->send_wa_button($contact_user, $msg, $button);*/
					}else{
						$approvedStatus = $this->form->recordFormHApproved($sess[0]->id_rec_h);

						$no_form = $this->form->getNoForm($sess[0]->id_rec_h)->result();

						// =============== Send Message With Template =============== \\

						$msg_to_pemohon = "Telah Berhasil Disetujui";
						$this->send_wa_notif($to_pemohon, $no_form[0]->title, $no_form[0]->no_form, $msg_to_pemohon);

						$msg = "Form: " . $no_form[0]->title . ", Dengan No Form: " . $no_form[0]->no_form . " Telah Berhasil *_Disetujui_* Dengan Catatan : " . $message;

						$this->send_wa_text($to, $msg);

						// =============== Send Message Without Template =============== \\

						/*$msg_to_pemohon = "Form _*" . $no_form[0]->no_form . "*_ Telah Berhasil *Disetujui*";
						$this->send_wa_text($to_pemohon, $msg_to_pemohon);*/
					}

					$update_sess = $this->form->updateSession($sess[0]->id_session, $sess[0]->id_form, $sess[0]->id_rec_h, 0, 0, 0, 12);

					$update_sess = $this->form->updateSession($sess[0]->id_session, 0, 0, 0, 0, 0, 99);
				}else if($sess[0]->status == 13){
					$userPemohon = $this->form->getUserPemohon($sess[0]->id_rec_h)->result();

					$to_pemohon = $userPemohon[0]->no_hp;

					$to_pemohon = '62' . ltrim($to_pemohon,'0');

					$no_form = $this->form->getNoForm($sess[0]->id_rec_h)->result();

					// $user_app = $this->form->getUser($sender)->result();

					// $alurApp = $this->form->getAlurApp($user_app[0]->id_user, $sess[0]->id_form, $userPemohon[0]->plant)->result();

					// $recordRejectForm = $this->form->recordApproval($sess[0]->id_rec_h, $alurApp[0]->id_alur, $message, 0);

					$UserAppNow = $this->form->nextAppUser($sess[0]->id_rec_h)->result();

					$updateApproval = $this->form->updateApproval($sess[0]->id_rec_h, $UserAppNow[0]->id_alur, $message, 2);

					$msg = "Form: " . $no_form[0]->title . ", Dengan No Form: " . $no_form[0]->no_form . " Telah *_Ditolak_* Dengan Catatan : " . $message;
					
					$this->send_wa_text($to, $msg);

					// $user_approval_now = $this->form->getUser($to)->result();

					// $msg_to_pemohon = "Telah _*Ditolak*_ Oleh: " . $user_approval_now[0]->nama . " Dengan Catatan : " . $message;
					// $this->send_wa_notif($to_pemohon, $no_form[0]->title, $no_form[0]->no_form, $msg_to_pemohon);

					$msg_to_pemohon = "Telah _*Ditolak*_ Oleh: *" . $UserAppNow[0]->jabatan . " - " . $UserAppNow[0]->caption . "* Dengan Catatan : *" . $message . "*";

					$this->send_wa_notif($to_pemohon, $no_form[0]->title, $no_form[0]->no_form, $msg_to_pemohon);

					$update_sess = $this->form->updateSession($sess[0]->id_session, $sess[0]->id_form, $sess[0]->id_rec_h, 0, 0, 0, 14);

					$update_sess = $this->form->updateSession($sess[0]->id_session, 0, 0, 0, 0, 0, 99);

				}
			}
		}
	}

	function check_input($type, $jwb, $id_pty){
		$data = [
			"status" => "",
			"result" => "",
			"sub_result" => "",
			"id_opsi" => 0
		];

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
		}else if($type == "multi_option"){
			if(!ctype_space($jwb)){
				$done = true;

				$jwb_break = explode(",",$jwb);

				$count_jwb = array_count_values($jwb_break);

				$opsi = $this->form->getOpsiJawaban($id_pty)->result();

				$seq_opsi = array();

				foreach($opsi as $o){
					array_push($seq_opsi, $o->seq_jawaban);
				}

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
				}

				if($done){
					$data['result'] = $jwb;
					$data['status'] = "answer";
				}
			}else{
				$data['status'] = "error";
			}
		}else if($type == "integer"){
			if(gettype(filter_var($jwb, FILTER_VALIDATE_INT)) == "integer"){
				$data['status'] = "answer";
				$data['result'] = $jwb;
			}else{
				$data['status'] = "error";
			}
		}else if($type == "double"){
			if(filter_var($jwb, FILTER_VALIDATE_FLOAT)){
				$data['status'] = "answer";
				$data['result'] = $jwb;
			}else{
				$data['status'] = "error";
			}
		}else if($type == "picture"){
			if($jwb["file_type"] == "image"){
				$data['status'] = "answer";
				$data['result'] = $jwb["file_url"];
			}else{
				$data['status'] = "error";
			}
		}else if($type == "document"){
			if($jwb["file_type"] == "document"){
				$data['status'] = "answer";
				$data['result'] = $jwb["file_url"];
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

			$post .= ']}';
		}else if($mode == "approval"){
			$post = '{
				"messaging_product": "whatsapp",
				"recipient_type": "individual",
				"to": "'.$to.'",
				"type": "template",
				"template": {
					"name": "approval_session_form_user",
					"language": {
						"code": "id"
					},
					"components": [
						{
							"type": "button",
							"sub_type": "quick_reply",
							"index": "0",
							"parameters": [
								{
									"type": "payload",
									"payload": "approval_'.$data['id_rec_h'].'_'.$data['id_form'].'"
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

		var_dump($report);
	}

	// ====================== Expired Function ====================== \\

	function send_wa_text($to, $msg){
		$token = $this->api->getToken(1)->row()->token;

		$authorization = "Authorization: Bearer " . $token;

		// $to = '6282228909916';

		var_dump($msg);
	
		$posts = '{
			"to": "'.$to.'",
			"type": "text",
			"message": "'.$msg.'"
		}';

		$ch = curl_init();

		curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type: application/json' , $authorization ));
		curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST");
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
		curl_setopt($ch, CURLOPT_POSTFIELDS,$posts);
		curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
		curl_setopt($ch, CURLOPT_URL, "https://api.halosis.id/v1/messages");
		curl_setopt($ch, CURLOPT_ENCODING, "");
		curl_setopt($ch, CURLOPT_MAXREDIRS, 10);
		curl_setopt($ch, CURLOPT_TIMEOUT, 0);
		curl_setopt($ch, CURLOPT_HTTP_VERSION, CURL_HTTP_VERSION_1_1);
		$result = curl_exec($ch);
		curl_close($ch);
		$report = json_decode($result);

		var_dump($report);
	}

	function send_wa_lists($to, $msg, $title_lists, $lists){
		$token = $this->api->getToken(1)->row()->token;

		$authorization = "Authorization: Bearer " . $token;

		// $to = '6282228909916';

		$posts = '{
			"to": "'.$to.'",
			"type": "list",
			"message": "'.$msg.'",
			"list_title": "'.$title_lists.'",
			"lists": [';

			$total_list = count($lists);

			for($i = 0; $i < $total_list; $i++){
				if(($i + 1) == $total_list){
					$posts .= '{
								"id": "'. $lists[$i]['id'] .'",
								"title": "'. $lists[$i]['title'] .'",
								"description": "'. $lists[$i]['description'] .'"
							}';
				}else{
					$posts .= '{
								"id": "'. $lists[$i]['id'] .'",
								"title": "'. $lists[$i]['title'] .'",
								"description": "'. $lists[$i]['description'] .'"
							},';
				}
			}

		$posts .= ']}';

		$ch = curl_init();

		curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type: application/json' , $authorization ));
		curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST");
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
		curl_setopt($ch, CURLOPT_POSTFIELDS,$posts);
		curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
		curl_setopt($ch, CURLOPT_URL, "https://api.halosis.id/v1/messages");
		curl_setopt($ch, CURLOPT_ENCODING, "");
		curl_setopt($ch, CURLOPT_MAXREDIRS, 10);
		curl_setopt($ch, CURLOPT_TIMEOUT, 0);
		curl_setopt($ch, CURLOPT_HTTP_VERSION, CURL_HTTP_VERSION_1_1);
		$result = curl_exec($ch);
		curl_close($ch);
		$report = json_decode($result);

		var_dump($report);
	}

	function send_wa_button($to, $msg, $button){
		$token = $this->api->getToken(1)->row()->token;

		$authorization = "Authorization: Bearer " . $token;

		// $to = '6282228909916';

		$posts = '{
			"to": "'.$to.'",
			"type": "button",
			"message": "'.$msg.'",
			"buttons": [';

		$total_button = count($button);

		for($i = 0; $i < $total_button; $i++){
			if(($i + 1) == $total_button){
				$posts .= '{
								"id": "'.$button[$i]['id'].'",
								"title": "'.$button[$i]['title'].'"
							}';
			}else{
				$posts .= '{
								"id": "'.$button[$i]['id'].'",
								"title": "'.$button[$i]['title'].'"
							},';
			}
		}

		$posts .= ']}';

		$ch = curl_init();

		curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type: application/json' , $authorization ));
		curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST");
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
		curl_setopt($ch, CURLOPT_POSTFIELDS,$posts);
		curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
		curl_setopt($ch, CURLOPT_URL, "https://api.halosis.id/v1/messages");
		curl_setopt($ch, CURLOPT_ENCODING, "");
		curl_setopt($ch, CURLOPT_MAXREDIRS, 10);
		curl_setopt($ch, CURLOPT_TIMEOUT, 0);
		curl_setopt($ch, CURLOPT_HTTP_VERSION, CURL_HTTP_VERSION_1_1);
		$result = curl_exec($ch);
		curl_close($ch);
		$report = json_decode($result);

		var_dump($report);
	}

	function send_wa_result_pemohon($to, $title, $no_form, $result, $user_app){
		$token = $this->api->getToken(1)->row()->token;

		$authorization = "Authorization: Bearer " . $token;

		// $to = '6282228909916';

		$posts = '{
			"messaging_product": "whatsapp",
			"recipient_type": "individual",
			"to": "'.$to.'",
			"type": "template",
			"template": {
				"name": "approval_session_notifikasi_pemohon_result",
				"language": {
					"code": "id"
				},
				"components": [
					{
						"type": "body",
						"parameters": [
							{
								"type": "text",
								"text": "'.$title.'"
							},
							{
								"type": "text",
								"text": "'.$no_form.'"
							},
							{
								"type": "text",
								"text": "'.$result.'"
							},
							{
								"type": "text",
								"text": "'.$user_app.'"
							}
						]
					}
				]
			}
		}';

		$ch = curl_init();

		curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type: application/json' , $authorization ));
		curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST");
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
		curl_setopt($ch, CURLOPT_POSTFIELDS,$posts);
		curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
		curl_setopt($ch, CURLOPT_URL, "https://api.halosis.id/v1/messages");
		curl_setopt($ch, CURLOPT_ENCODING, "");
		curl_setopt($ch, CURLOPT_MAXREDIRS, 10);
		curl_setopt($ch, CURLOPT_TIMEOUT, 0);
		curl_setopt($ch, CURLOPT_HTTP_VERSION, CURL_HTTP_VERSION_1_1);
		$result = curl_exec($ch);
		curl_close($ch);
		$report = json_decode($result);

		var_dump($report);
	}

	function send_wa_notif_pemohon($to, $no_form, $user_app){
		$token = $this->api->getToken(1)->row()->token;

		$authorization = "Authorization: Bearer " . $token;

		// $to = '6282228909916';

		$posts = '{
			"messaging_product": "whatsapp",
			"recipient_type": "individual",
			"to": "'.$to.'",
			"type": "template",
			"template": {
				"name": "approval_session_notifikasi_form_pemohon",
				"language": {
					"code": "id"
				},
				"components": [
					{
						"type": "body",
						"parameters": [
							{
								"type": "text",
								"text": "'.$no_form.'"
							},
							{
								"type": "text",
								"text": "'.$user_app.'"
							}
						]
					}
				]
			}
		}';

		$ch = curl_init();

		curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type: application/json' , $authorization ));
		curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST");
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
		curl_setopt($ch, CURLOPT_POSTFIELDS,$posts);
		curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
		curl_setopt($ch, CURLOPT_URL, "https://api.halosis.id/v1/messages");
		curl_setopt($ch, CURLOPT_ENCODING, "");
		curl_setopt($ch, CURLOPT_MAXREDIRS, 10);
		curl_setopt($ch, CURLOPT_TIMEOUT, 0);
		curl_setopt($ch, CURLOPT_HTTP_VERSION, CURL_HTTP_VERSION_1_1);
		$result = curl_exec($ch);
		curl_close($ch);
		$report = json_decode($result);

		var_dump($report);
	}

	function send_wa_notif($to, $title, $no_form, $pesan){
		$token = $this->api->getToken(1)->row()->token;

		$authorization = "Authorization: Bearer " . $token;

		// $to = '6282228909916';

		$posts = '{
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
								"text": "'.$title.'"
							},
							{
								"type": "text",
								"text": "'.$no_form.'"
							},
							{
								"type": "text",
								"text": "'.$pesan.'"
							}
						]
					}
				]
			}
		}';

		$ch = curl_init();

		curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type: application/json' , $authorization ));
		curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST");
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
		curl_setopt($ch, CURLOPT_POSTFIELDS,$posts);
		curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
		curl_setopt($ch, CURLOPT_URL, "https://api.halosis.id/v1/messages");
		curl_setopt($ch, CURLOPT_ENCODING, "");
		curl_setopt($ch, CURLOPT_MAXREDIRS, 10);
		curl_setopt($ch, CURLOPT_TIMEOUT, 0);
		curl_setopt($ch, CURLOPT_HTTP_VERSION, CURL_HTTP_VERSION_1_1);
		$result = curl_exec($ch);
		curl_close($ch);
		$report = json_decode($result);

		var_dump($report);
	}

	function send_wa_approval($to, $id_rec_h, $id_form, $title, $no_form){
		$token = $this->api->getToken(1)->row()->token;

		$authorization = "Authorization: Bearer " . $token;

		// $to = '6282228909916';

		$posts = '{
			"messaging_product": "whatsapp",
			"recipient_type": "individual",
			"to": "'.$to.'",
			"type": "template",
			"template": {
				"name": "approval_session_form_user",
				"language": {
					"code": "id"
				},
				"components": [
					{
						"type": "button",
						"sub_type": "quick_reply",
						"index": "0",
						"parameters": [
							{
								"type": "payload",
								"payload": "approval_'.$id_rec_h.'_'.$id_form.'"
							}
						]
					},
					{
						"type": "body",
						"parameters":[
							{
								"type": "text",
								"text": "'.$title.'"
							},
							{
								"type": "text",
								"text": "'.$no_form.'"
							}
						]
					}
				]
			}
		}';

		$ch = curl_init();

		curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type: application/json' , $authorization ));
		curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST");
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
		curl_setopt($ch, CURLOPT_POSTFIELDS,$posts);
		curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
		curl_setopt($ch, CURLOPT_URL, "https://api.halosis.id/v1/messages");
		curl_setopt($ch, CURLOPT_ENCODING, "");
		curl_setopt($ch, CURLOPT_MAXREDIRS, 10);
		curl_setopt($ch, CURLOPT_TIMEOUT, 0);
		curl_setopt($ch, CURLOPT_HTTP_VERSION, CURL_HTTP_VERSION_1_1);
		$result = curl_exec($ch);
		curl_close($ch);
		$report = json_decode($result);

		var_dump($report);
	}
}
