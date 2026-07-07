<?php
defined('BASEPATH') OR exit('No direct script access allowed');

require 'asset/php/dompdf/vendor/autoload.php';

use Dompdf\Dompdf;
use Dompdf\Options;

class API_WA extends CI_Controller {

	function __construct(){
        parent::__construct();
		$this->load->model('M_Form', 'form');
		$this->load->model('M_API', 'api');
    }
	
	public function index(){

		// $this->load->view('templates/template_work_permit_update', $data);
		
		// Ambil konten HTML dari view
		$html = $this->load->view('templates/form_template', [], true);

		// Mengatur opsi dompdf
		$options = new Options();
		$options->set('defaultFont', 'Courier');
		$options->set('isRemoteEnabled', true);
		$options->set('isPhpEnabled', TRUE);

		// Membuat instance dompdf baru
		$dompdf = new Dompdf($options);
		//  $dompdf = new Dompdf();

		// Memuat konten HTML ke dompdf
		$dompdf->loadHtml($html);

		// Mengatur ukuran dan orientasi kertas
		$dompdf->setPaper('A4', 'portrait'); // 'landscape' untuk orientasi lanskap

		// Merender HTML menjadi PDF
		$dompdf->render();

		// Menghasilkan file PDF dan mengirimnya ke browser untuk diunduh
		$pdfOutput = $dompdf->stream("output.pdf", array("Attachment" => 1));
	}

	function view_wp(){
		$this->load->view('templates/form_template');
	}

	function webhook(){
		header('Content-Type: application/json; charset=utf-8');

		$json = file_get_contents('php://input');
		$data = json_decode($json, true);

		//Nomor WA User
		$to = $data["data"]["from_phone_number"];

		// Data Pesan User
		$message = $data["data"]["message"]; 
		
		// Id Button yang Dipilih User
		$action_id = $data["data"]["action_id"];

		$config['msg'] = $message;

		$this->send_wa("text", $to, $config);
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

			echo $post;
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

			echo $post;
		}

		var_dump($post);

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

	function checkInput(){
		$answer = "Aditya bagus  ";

		$break = explode("|", $answer);

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

		var_dump($answer);
		echo "<br>";
		var_dump($done);
	}

	function convertPDF(){
		$id_rec_h = 282;

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
				var_dump(file_exists('asset/img/sign/sign_' . $a->id_user_app . '.png'));

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
		$options->set('isPhpEnabled', TRUE);
 
		$dompdf = new Dompdf($options);
		$dompdf->loadHtml($html);
 
		$dompdf->setPaper('A4', 'portrait');

		$dompdf->render();

		$pdfOutput = $dompdf->output();

		// $pdfOutput = $dompdf->stream("output.pdf", array("Attachment" => 1));

		$filepath = 'assets/form_data/stream_data/work_permit('. $id_rec_h .').pdf';

		file_put_contents($filepath, $pdfOutput);

		// $filepath = base_url() . $filepath;

		// $config["msg"] = $filepath;

		// // $this->send_wa("text", "6282228909916", $config);

		// return $filepath;
	}
}
