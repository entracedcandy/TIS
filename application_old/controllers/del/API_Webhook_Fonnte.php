<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class API_Webhook extends CI_Controller {

	function __construct(){
        parent::__construct();
		$this->load->model('M_Form', 'form');
    }
	
	public function index(){
		header('Content-Type: application/json; charset=utf-8');

		$json = file_get_contents('php://input');
		$data = json_decode($json, true);

		$device = $data['device'];
		$sender = $data['sender'];
		$message = $data['message'];
		$name = $data['name'];
		$location = $data['location'];
		$url =  $data['url'];
		$filename =  $data['filename'];
		$extension =  $data['extension'];

		// $sender = '6282228909916';
		
		// $message = "1";

		$sender = '0' . ltrim($sender,'62');
		
		if($message){
			$sess = $this->form->cekSession($sender)->result();

			if($message == "#form#"){
				if($sess){
					$update_sess = $this->form->updateSession($sess[0]->id_session, 0, 0, 0, 0, 99);
				}

				$form = $this->form->getForm($sender)->result();

				$msg = "";
				
				if($form){
					$user = $this->form->getUser($sender)->result();

					$id_user = $user[0]->id_user;

					$record_sess = $this->form->recordSession($id_user);
	
					$msg = "Silahkan Memilih Form:\n\n";

					foreach($form as $f){
						$msg .= $f->nomor . ". " . $f->title . "\n";
					}
	
				}

				$reply = ["message" => $msg];

			}else if($message == "#approval#"){
				if($sess){
					$update_sess = $this->form->updateSession($sess[0]->id_session, 0, 0, 0, 0, 99);
				}

				$approvalForm = $this->form->checkAppUser($sender)->result();

				$msg = "";
				
				if($approvalForm){
					$msg .= "List Form Approval : \n\n";

					$ctr = 1;

					foreach($approvalForm as $af){
						$msg .= $ctr . ". " . $af->no_form . " | " . $af->title . " | " . $af->caption_form . "\n";
						
						$ctr++;
					}
					
					$user = $this->form->getUser($sender)->result();

					$id_user = $user[0]->id_user;

					$record_sess = $this->form->recordSessionApproval($id_user);
				}

				$reply = ["message" => $msg];
			}else{
				if($sess[0]->status == 0){
					$user = $this->form->getUser($sender)->result();

					$id_user = $user[0]->id_user;

					$form = $this->form->getForm($sender)->result();

					if((int)$message > count($form) || !filter_var($message, FILTER_VALIDATE_INT)){
						$msg = "Jawaban Tidak Ada Dalam Pilihan!\n\nSilahkan Memilih Form:\n\n";

						foreach($form as $f){
							$msg .= $f->nomor . ". " . $f->title . "\n";
						}
		
						$reply = ["message" => $msg];
					}else{
						$id_form = 0;

						foreach($form as $f){
							if($f->nomor == (int)$message){
								if($f->has_parent > 0){
									$refForm = $this->form->getRefForm($f->id_form)->result();

									if($refForm){
										foreach($refForm as $rf){
											$msg = "Silahkan Memilih Refrensi Form:\n\n";

											$msg .= $rf->nomor . ". " . $rf->id_rec_h . "\n";
										}

										$update_sess = $this->form->updateSession($sess[0]->id_session, $f->id_form, 0, 0, 0, 0.1);

										$reply = ["message" => $msg];
									}else{
										$msg = "Tidak Ada Form yang Bisa Di Refrensikan\n\nSilahkan Memilih Form:\n\n";

										foreach($form as $f){
											$msg .= $f->nomor . ". " . $f->title . "\n";
										}

										$reply = ["message" => $msg];
									}
								}else{
									$id_form = $f->id_form;
	
									$user = $this->form->getUser($sender)->result();
									$id_user = $user[0]->id_user;
									$plant = $user[0]->plant;
	
									$id_rec_h = $this->form->recordFormH($id_form, $id_user, $plant);
									
									$pertanyaan = $this->form->getFirstQuestion($id_form)->result();
									
									$id_pertanyaan_pertama = $pertanyaan[0]->id_pertanyaan;
	
									$rec_d = $this->form->recordFormD($id_rec_h, $id_pertanyaan_pertama);
								}
							}
						}

						if($id_form > 0){
							$update_sess = $this->form->updateSession($sess[0]->id_session, $id_form, $id_rec_h, $id_pertanyaan_pertama, 0, 1);

							$jawaban_chat = $pertanyaan[0]->pertanyaan;

							if($pertanyaan[0]->tipe_jawaban == 'date'){
								$jawaban_chat .= "\n_Format Jawaban : DD-MM-YYYY (Contoh: 23-10-2024)_";
							}else if($pertanyaan[0]->tipe_jawaban == 'datetime'){
								$jawaban_chat .= "\n_Format Jawaban : DD-MM-YYYY JJ:MM (Contoh: 23-10-2024 15:30)_";
							}else if($pertanyaan[0]->tipe_jawaban == 'time'){
								$jawaban_chat .= "\n_Format Jawaban : JJ:MM (Contoh: 15:30)_";
							}else if($pertanyaan[0]->tipe_jawaban == 'multi_option'){
								$jawaban_chat .= "\n_Jawaban Bisa Dipilih Lebih dari 1 (Contoh Format Jawaban : 1,2,3,4,5)_";
							}

							if($pertanyaan[0]->tipe_jawaban == 'single_option' || $pertanyaan[0]->tipe_jawaban == 'multi_option'){
								$opsi = $this->form->getOpsiJawaban($id_pertanyaan_pertama)->result();

								$jawaban_chat .= "\n\n";

								foreach($opsi as $o){
									$jawaban_chat .= $o->seq_jawaban . ". " . $o->opsi_jawaban . "\n";
								}
							}
						}

						$reply = ["message" => $jawaban_chat];
					}
				}else if($sess[0]->status == 1){
					$pertanyaan = $this->form->getPertanyaan($sess[0]->id_pertanyaan)->result();

					$result = $this->check_input($pertanyaan[0]->tipe_jawaban, $message, $sess[0]->id_pertanyaan);

					if(!$result['status'] || $result['status'] == 'error'){
						$msg = "Jawaban Tidak Benar\n\n" . $pertanyaan[0]->pertanyaan;

						if($pertanyaan[0]->tipe_jawaban == 'date'){
							$msg .= "\n_Format Jawaban : DD-MM-YYYY (Contoh: 23-10-2024)_";
						}else if($pertanyaan[0]->tipe_jawaban == 'datetime'){
							$msg .= "\n_Format Jawaban : DD-MM-YYYY JJ:MM (Contoh: 23-10-2024 15:30)_";
						}else if($pertanyaan[0]->tipe_jawaban == 'time'){
							$msg .= "\n_Format Jawaban : JJ:MM (Contoh: 15:30)_";
						}else if($pertanyaan[0]->tipe_jawaban == 'multi_option'){
							$jawaban_chat .= "\n_Jawaban Bisa Dipilih Lebih dari 1 (Contoh Format Jawaban : 1,2,3,4,5)_";
						}

						$opsi = $this->form->getOpsiJawaban($sess[0]->id_pertanyaan)->result();

						if($opsi){
							$msg .= "\n\n";
						}

						foreach($opsi as $o){
							$msg .= $o->seq_jawaban . ". " . $o->opsi_jawaban . "\n";
						}
					}else if($result['status'] == "answer"){
						$id_rec_d = $this->form->getIDRecD($sess[0]->id_rec_h)->result();

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

						// var_dump($sess[0]->id_form);

						if($checkQuestion[0]->status == "NO"){
							$seq_next = (int)$checkQuestion[0]->jawaban + 1;

							$nextQuestion = $this->form->getPertanyaanBySeq($sess[0]->id_form, $seq_next)->result();

							$jawaban_chat = $nextQuestion[0]->pertanyaan;

							if($nextQuestion[0]->tipe_jawaban == 'date'){
								$jawaban_chat .= "\n_Format Jawaban : DD-MM-YYYY (Contoh: 23-10-2024)_";
							}else if($nextQuestion[0]->tipe_jawaban == 'datetime'){
								$jawaban_chat .= "\n_Format Jawaban : DD-MM-YYYY JJ:MM (Contoh: 23-10-2024 15:30)_";
							}else if($nextQuestion[0]->tipe_jawaban == 'time'){
								$jawaban_chat .= "\n_Format Jawaban : JJ:MM (Contoh: 15:30)_";
							}else if($nextQuestion[0]->tipe_jawaban == 'multi_option'){
								$jawaban_chat .= "\n_Jawaban Bisa Dipilih Lebih dari 1 (Contoh Format Jawaban : 1,2,3,4,5)_";
							}

							if($nextQuestion[0]->tipe_jawaban == 'single_option' || $nextQuestion[0]->tipe_jawaban == 'multi_option'){
								$opsi = $this->form->getOpsiJawaban($nextQuestion[0]->id_pertanyaan)->result();

								$jawaban_chat .= "\n\n";

								foreach($opsi as $o){
									$jawaban_chat .= $o->seq_jawaban . ". " . $o->opsi_jawaban . "\n";
								}
							}

							$rec_d = $this->form->recordFormD($sess[0]->id_rec_h, $nextQuestion[0]->id_pertanyaan);

							$update_sess = $this->form->updateSession($sess[0]->id_session, $sess[0]->id_form, $sess[0]->id_rec_h, $nextQuestion[0]->id_pertanyaan, 0, 1);

							$msg = $jawaban_chat;
						}else{
							$update_sess = $this->form->updateSession($sess[0]->id_session, $sess[0]->id_form, $sess[0]->id_rec_h, 0, 0, 2);

							$no_form = $this->form->getNoForm($sess[0]->id_rec_h)->result();

							$msg = "Silahkan Melihat Preview Dari Form yang Sudah Diisi Pada Form Dibawah Ini:\n\nhttps://karir-cpijatim.com/cpar/FormViewer/index/" . $no_form[0]->no_form . "\n\nKonfirmasi Form Tersebut ?\n1. Konfirmasi\n2. Reject / Edit";
						}
					}else if($result['status'] == "hold"){
						$msg = $result['result'];

						$id_rec_d = $this->form->getIDRecD($sess[0]->id_rec_h)->result();

						$opsi_jawaban = $this->form->getOpsiJawabanValue($result['id_opsi'])->result();

						$resultUpdate = $this->form->updateValueRecordFormD($id_rec_d[0]->id_rec_form_d, $opsi_jawaban[0]->opsi_jawaban);

						$update_sess = $this->form->updateSession($sess[0]->id_session, $sess[0]->id_form, $sess[0]->id_rec_h, $sess[0]->id_pertanyaan, $result['id_opsi'], 1.1);
					}
					
					$reply = ["message" => $msg];
				}else if($sess[0]->status == 1.1){
					$id_rec_d = $this->form->getIDRecDHasValue($sess[0]->id_rec_h)->result();

					$resultUpdate = $this->form->updateSubValueRecordFormD($id_rec_d[0]->id_rec_form_d, $message);

					$checkQuestion = $this->form->checkNextQuestion($sess[0]->id_form, $sess[0]->id_rec_h)->result();

					if($checkQuestion[0]->status == "NO"){
						$seq_next = (int)$checkQuestion[0]->jawaban + 1;

						$nextQuestion = $this->form->getPertanyaanBySeq($sess[0]->id_form, $seq_next)->result();

						$jawaban_chat = $nextQuestion[0]->pertanyaan;

						if($nextQuestion[0]->tipe_jawaban == 'date'){
							$jawaban_chat .= "\n_Format Jawaban : DD-MM-YYYY (Contoh: 23-10-2024)_";
						}else if($nextQuestion[0]->tipe_jawaban == 'datetime'){
							$jawaban_chat .= "\n_Format Jawaban : DD-MM-YYYY JJ:MM (Contoh: 23-10-2024 15:30)_";
						}else if($nextQuestion[0]->tipe_jawaban == 'time'){
							$jawaban_chat .= "\n_Format Jawaban : JJ:MM (Contoh: 15:30)_";
						}else if($nextQuestion[0]->tipe_jawaban == 'multi_option'){
							$jawaban_chat .= "\n_Jawaban Bisa Dipilih Lebih dari 1 (Contoh Format Jawaban : 1,2,3,4,5)_";
						}

						if($nextQuestion[0]->tipe_jawaban == 'single_option' || $nextQuestion[0]->tipe_jawaban == 'multi_option'){
							$opsi = $this->form->getOpsiJawaban($nextQuestion[0]->id_pertanyaan)->result();

							$jawaban_chat .= "\n\n";

							foreach($opsi as $o){
								$jawaban_chat .= $o->seq_jawaban . ". " . $o->opsi_jawaban . "\n";
							}
						}

						$rec_d = $this->form->recordFormD($sess[0]->id_rec_h, $nextQuestion[0]->id_pertanyaan);

						$update_sess = $this->form->updateSession($sess[0]->id_session, $sess[0]->id_form, $sess[0]->id_rec_h, $nextQuestion[0]->id_pertanyaan, 0, 1);

						$msg = $jawaban_chat;
					}else{
						$update_sess = $this->form->updateSession($sess[0]->id_session, $sess[0]->id_form, $sess[0]->id_rec_h, 0, 0, 2);

						$no_form = $this->form->getNoForm($sess[0]->id_rec_h)->result();

						$msg = "Silahkan Melihat Preview Dari Form yang Sudah Diisi Pada Form Dibawah Ini:\n\nhttps://karir-cpijatim.com/cpar/FormViewer/index/" . $no_form[0]->no_form . "\n\nKonfirmasi Form Tersebut ?\n1. Konfirmasi\n2. Reject / Edit";
					}

					$reply = ["message" => $msg];
				}else if($sess[0]->status == 2){
					if($message == 1){
						$createdStatus = $this->form->recordFormHCreated($sess[0]->id_rec_h);

						$update_sess = $this->form->updateSession($sess[0]->id_session, $sess[0]->id_form, $sess[0]->id_rec_h, 0, 0, 7);

						$app_user = $this->form->getAppUser($sess[0]->id_user, $sess[0]->id_form)->result();

						var_dump($app_user);

						$msg = "";

						$no_form = $this->form->getNoForm($sess[0]->id_rec_h)->result();

						if($app_user){
							$msg = "Form _*" . $no_form[0]->no_form . "*_ Telah Terkirim Ke: _*" . $app_user[0]->caption . "*_ untuk Dilkukan Approval";

							$formTitle = $this->form->getTitleForm($no_form[0]->no_form)->result();

							$msg_to_approval = "_*Notifikasi*_\nForm: _*" . $formTitle[0]->title . "*_, Dengan No Form: _*" . $no_form[0]->no_form . "*_ Menunggu Untuk Dilakukan Approval\n\n*_Pesan Ini Dapat Diabaikan_*";

							$reply_app = ["message" => $msg_to_approval];

							$this->send_wa($app_user[0]->no_hp, $reply_app);
						}

						$update_sess = $this->form->updateSession($sess[0]->id_session, 0, 0, 0, 0, 99);
					}else if($message == 2){
						$msg = "Apakah Anda Mau Melakukan Editing Jawaban Form Atau Membatalkan Pengisian Form ?\n\n1. Edit Form\n2. Hapus Form";
						
						$update_sess = $this->form->updateSession($sess[0]->id_session, $sess[0]->id_form, $sess[0]->id_rec_h, 0, 0, 3);
					}else{
						$no_form = $this->form->getNoForm($sess[0]->id_rec_h)->result();

						$msg = "_*Pilihan Yang Anda Pilih Tidak Ada Dalam Pilihan!*_\n\nSilahkan Melihat Preview Dari Form yang Sudah Diisi Pada Form Dibawah Ini:\n\nhttps://karir-cpijatim.com/cpar/FormViewer/index/" . $no_form[0]->no_form . "\n\nKonfirmasi Form Tersebut ?\n1. Konfirmasi\n2. Reject / Edit";
					}

					$reply = ["message" => $msg];
				}else if($sess[0]->status == 3){
					if($message == 2){
						$update_sess = $this->form->updateSession($sess[0]->id_session, $sess[0]->id_form, $sess[0]->id_rec_h, 0, 0, 4);

						$no_form = $this->form->getNoForm($sess[0]->id_rec_h)->result();

						$msg = "Form : _*" . $no_form[0]->no_form . "*_ Berhasil Dibatalkan.";

						$update_sess = $this->form->updateSession($sess[0]->id_session, 0, 0, 0, 0, 99);
					}else if($message == 1){
						$update_sess = $this->form->updateSession($sess[0]->id_session, $sess[0]->id_form, $sess[0]->id_rec_h, 0, 0, 5);

						$allPertanyaan = $this->form->getAllPertanyaan($sess[0]->id_form)->result();

						$msg = "Silahkan Memilih Nomor Pertanyaan yang Ingin Dirubah:\n\n";

						foreach($allPertanyaan as $ap){
							$msg .= $ap->seq . ". " . $ap->pertanyaan . "\n\n";
						}

						$msg .= "\n99. Kembali Untuk Preview Form";
					}else{
						$msg = "Jawaban Anda Tidak Ada Dalam Pilihan!\n\nApakah Anda Mau Melakukan Editing Jawaban Form Atau Membatalkan Pengisian Form ?\n\n1. Edit Form\n2. Hapus Form";
					}

					$reply = ["message" => $msg];
				}else if($sess[0]->status == 5){
					if($message == 99){
						$update_sess = $this->form->updateSession($sess[0]->id_session, $sess[0]->id_form, $sess[0]->id_rec_h, $pertanyaanNow[0]->id_pertanyaan, 0, 2);

						$no_form = $this->form->getNoForm($sess[0]->id_rec_h)->result();

						$msg = "Silahkan Melihat Preview Dari Form yang Sudah Diisi Pada Form Dibawah Ini:\n\nhttps://karir-cpijatim.com/cpar/FormViewer/index/" . $no_form[0]->no_form . "\n\nKonfirmasi Form Tersebut ?\n1. Konfirmasi\n2. Reject / Edit";
					}else{
						$pertanyaanNow = $this->form->getPertanyaanBySeq($sess[0]->id_form, $message)->result();
	
						$jawabanSebelumnya = $this->form->getJawaban($sess[0]->id_rec_h, $pertanyaanNow[0]->id_pertanyaan)->result();
	
						if($pertanyaanNow){
							$msg = "Jawaban Sebelumnya : _*" . $jawabanSebelumnya[0]->value;
	
							if($jawabanSebelumnya[0]->sub_value != ""){
								$msg .= " - " . $jawabanSebelumnya[0]->sub_value;
							}
	
							$msg .= "*_\n\nSilahkan Memilih / Memasukkan Jawaban Yang Baru\n\n";
	
							$pertanyaanEdit = $this->form->getPertanyaan($pertanyaanNow[0]->id_pertanyaan)->result();
	
							$msg .= $pertanyaanEdit[0]->pertanyaan;
	
							if($pertanyaanEdit[0]->tipe_jawaban == 'date'){
								$msg .= "\n_Format Jawaban : DD-MM-YYYY (Contoh: 23-10-2024)_";
							}else if($pertanyaanEdit[0]->tipe_jawaban == 'datetime'){
								$msg .= "\n_Format Jawaban : DD-MM-YYYY JJ:MM (Contoh: 23-10-2024 15:30)_";
							}else if($pertanyaanEdit[0]->tipe_jawaban == 'time'){
								$msg .= "\n_Format Jawaban : JJ:MM (Contoh: 15:30)_";
							}else if($pertanyaanEdit[0]->tipe_jawaban == 'multi_option'){
								$jawaban_chat .= "\n_Jawaban Bisa Dipilih Lebih dari 1 (Contoh Format Jawaban : 1,2,3,4,5)_";
							}
	
							if($pertanyaanEdit[0]->tipe_jawaban == 'single_option' || $pertanyaanEdit[0]->tipe_jawaban == 'multi_option'){
								$opsi = $this->form->getOpsiJawaban($pertanyaanEdit[0]->id_pertanyaan)->result();
	
								$msg .= "\n\n";
	
								foreach($opsi as $o){
									$msg .= $o->seq_jawaban . ". " . $o->opsi_jawaban . "\n";
								}
	
								$update_sess = $this->form->updateSession($sess[0]->id_session, $sess[0]->id_form, $sess[0]->id_rec_h, $pertanyaanNow[0]->id_pertanyaan, 0, 6);
							}
						}else{
							$allPertanyaan = $this->form->getAllPertanyaan($sess[0]->id_form)->result();
	
							$msg = "Jawaban Anda Tidak Ada Dalam Pilihan!\n\nSilahkan Memilih Nomor Pertanyaan yang Ingin Dirubah:\n\n";

							foreach($allPertanyaan as $ap){
								$msg .= $ap->seq . ". " . $ap->pertanyaan . "\n";
							}
	
							$msg .= "\n99. Kembali Untuk Preview Form";
						}
	
					}
					$reply = ["message" => $msg];
				}else if($sess[0]->status == 6){
					$pertanyaan = $this->form->getPertanyaan($sess[0]->id_pertanyaan)->result();

					$result = $this->check_input($pertanyaan[0]->tipe_jawaban, $message, $sess[0]->id_pertanyaan);

					if(!$result['status'] || $result['status'] == 'error'){
						$msg = "Jawaban Tidak Benar\n\n" . $pertanyaan[0]->pertanyaan;

						if($pertanyaan[0]->tipe_jawaban == 'date'){
							$msg .= "\n_Format Jawaban : DD-MM-YYYY (Contoh: 23-10-2024)_";
						}else if($pertanyaan[0]->tipe_jawaban == 'datetime'){
							$msg .= "\n_Format Jawaban : DD-MM-YYYY JJ:MM (Contoh: 23-10-2024 15:30)_";
						}else if($pertanyaan[0]->tipe_jawaban == 'time'){
							$msg .= "\n_Format Jawaban : JJ:MM (Contoh: 15:30)_";
						}else if($pertanyaan[0]->tipe_jawaban == 'multi_option'){
							$jawaban_chat .= "\n_Jawaban Bisa Dipilih Lebih dari 1 (Contoh Format Jawaban : 1,2,3,4,5)_";
						}

						$opsi = $this->form->getOpsiJawaban($sess[0]->id_pertanyaan)->result();

						if($opsi){
							$msg .= "\n\n";
						}

						foreach($opsi as $o){
							$msg .= $o->seq_jawaban . ". " . $o->opsi_jawaban . "\n";
						}
					}else if($result['status'] == "answer"){
						$id_rec_d = $this->form->getJawaban($sess[0]->id_rec_h, $sess[0]->id_pertanyaan)->result();

						$send_data = $result['result'];

						$resultUpdate = $this->form->updateValueRecordFormD($id_rec_d[0]->id_rec_form_d, $send_data);
						$resultUpdate = $this->form->updateSubValueRecordFormD($id_rec_d[0]->id_rec_form_d, "");

						$update_sess = $this->form->updateSession($sess[0]->id_session, $sess[0]->id_form, $sess[0]->id_rec_h, $pertanyaanNow[0]->id_pertanyaan, 0, 5);

						$allPertanyaan = $this->form->getAllPertanyaan($sess[0]->id_form)->result();

						$msg = "_*Jawaban Berhasil Dirubah*_\n\nSilahkan Memilih Nomor Pertanyaan yang Ingin Dirubah:\n\n";

						foreach($allPertanyaan as $ap){
							$msg .= $ap->seq . ". " . $ap->pertanyaan . "\n";
						}

						$msg .= "\n99. Kembali Untuk Preview Form";
					}else if($result['status'] == "hold"){
						$msg = $result['result'];

						$id_rec_d = $this->form->getJawaban($sess[0]->id_rec_h, $sess[0]->id_pertanyaan)->result();

						$opsi_jawaban = $this->form->getOpsiJawabanValue($result['id_opsi'])->result();

						$resultUpdate = $this->form->updateValueRecordFormD($id_rec_d[0]->id_rec_form_d, $opsi_jawaban[0]->opsi_jawaban);

						$update_sess = $this->form->updateSession($sess[0]->id_session, $sess[0]->id_form, $sess[0]->id_rec_h, $sess[0]->id_pertanyaan, $result['id_opsi'], 6.1);
					}
					
					$reply = ["message" => $msg];
				}else if($sess[0]->status == 6.1){
					$id_rec_d = $this->form->getJawaban($sess[0]->id_rec_h, $sess[0]->id_pertanyaan)->result();

					$resultUpdate = $this->form->updateSubValueRecordFormD($id_rec_d[0]->id_rec_form_d, $message);

					$update_sess = $this->form->updateSession($sess[0]->id_session, $sess[0]->id_form, $sess[0]->id_rec_h, $pertanyaanNow[0]->id_pertanyaan, 0, 5);

					$allPertanyaan = $this->form->getAllPertanyaan($sess[0]->id_form)->result();

					$msg = "_*Jawaban Berhasil Dirubah*_\n\nSilahkan Memilih Nomor Pertanyaan yang Ingin Dirubah:\n\n";

					foreach($allPertanyaan as $ap){
						$msg .= $ap->seq . ". " . $ap->pertanyaan . "\n";
					}

					$msg .= "\n99. Kembali Untuk Preview Form";

					$reply = ["message" => $msg];
				}else if($sess[0]->status == 10){
					$approvalForm = $this->form->checkAppUser($sender)->result();

					$check_answer = true;

					$msg = "";

					$postion = 0;

					if(filter_var($message, FILTER_VALIDATE_INT) > 0){
						if((int)$message > count($approvalForm)){
							$check_answer = false;
						}else{
							$postion = (int)$message - 1;
						}
					}else{
						$check_answer = false;
					}

					if($check_answer){
						$msg .= "Preview Form : " . $approvalForm[$postion]->title . ", Dengan Nomor Form : " . $approvalForm[$postion]->no_form . "\n\nhttps://karir-cpijatim.com/cpar/FormViewer/index/" . $approvalForm[$postion]->no_form . "\n\nApakah Anda Akan Melakukan Approval Pada Form Ini ?\n1. Approve\n2. Reject";
					}

					$reply = ["message" => $msg];
				}
			}

			$this->send_wa($sender, $reply);
		}
	}

	function check_input($type, $jwb, $id_pty){
		$data = [
			"status" => "",
			"result" => "",
			"id_opsi" => 0
		];

		var_dump($type);

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
					if($o->seq_jawaban == (int)$jwb){
						if($o->sub_pertanyaan != ""){
							$data['result'] = $o->sub_pertanyaan;
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
					if(filter_var($jb, FILTER_VALIDATE_INT)){
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
			}
		}else if($type == "integer"){
			var_dump($jwb);
			if(filter_var($jwb, FILTER_VALIDATE_INT) >= 0){
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
		}else if($type == "button"){
			if(filter_var($jwb, FILTER_VALIDATE_INT)){
				$opsi = $this->form->getOpsiJawaban($id_pty)->result();

				if($opsi){
					$data['result'] = $opsi->opsi_jawaban;
					$data['status'] = "answer";
					$data['id_opsi'] = $opsi->id_opsi;
				}else{
					$data['status'] = "error";
				}

			}
		}else{
			$data['status'] = "answer";
			$data['result'] = $jwb;
		}

		return $data;
	}

	function send_wa($target, $data){
		$curl = curl_init();

		curl_setopt_array($curl, array(
		CURLOPT_URL => "https://api.fonnte.com/send",
		CURLOPT_RETURNTRANSFER => true,
		CURLOPT_ENCODING => "",
		CURLOPT_MAXREDIRS => 10,
		CURLOPT_TIMEOUT => 0,
		CURLOPT_FOLLOWLOCATION => true,
		CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
		CURLOPT_CUSTOMREQUEST => "POST",
		CURLOPT_POSTFIELDS => array(
				'target' => $target,
				'message' => $data['message'],
				// 'url' => $data['url'],
				// 'filename' => $data['filename'],
			),
		CURLOPT_HTTPHEADER => array(
			"Authorization: Li46RvGdGy+3e+7bUTJ9"
		),
		));

		$response = curl_exec($curl);

		curl_close($curl);

		return $response;
	}
}
