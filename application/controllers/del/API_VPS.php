<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class API_VPS extends CI_Controller {

	function __construct(){
        parent::__construct();
		$this->load->model('M_Report_Form', 'RForm');
		$this->load->model('M_Attendance', 'Matt');
		$this->load->model('M_API', 'api');
    }
	
	public function index(){
		
	}

	function send_wa(){
		
		// Template JSON
		// {
		// 	"to" : "082228909916",
		// 	"pesan" : "Halo Testing API"
		// }

		$token = $this->input->get_request_header('Authorization');

		if($token){
			$token_break = explode(" ", $token);
	
			if($token_break[1] == "uODr2wmGozooWo5ziF"){
				header('Content-Type: application/json; charset=utf-8');
		
				$json = file_get_contents('php://input');
				$data = json_decode($json, true);
		
				$to = $data['to'];
				$pesan = $data['pesan'];
		
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
						'target' => $to,
						'message' => $pesan,
						'countryCode' => '62', //optional
					),
					CURLOPT_HTTPHEADER => array(
						'Authorization: Li46RvGdGy+3e+7bUTJ9' //change TOKEN to your actual token
					),
				));
				
				$response = curl_exec($curl);
				
				curl_close($curl);
			}else{
				$response = '{"status" : "error", "message" : "wrong token"}';
			}
		}else{
			$response = '{"status" : "error", "message" : "wrong token"}';
		}

        $this->output
             ->set_content_type('application/json')
             ->set_output(json_encode($response));
	}

	function workPermit(){
		// $token = $this->input->get_request_header('Authorization');
		// $data = "SUCCESS";

		header('Content-Type: application/json; charset=utf-8');

		$json = file_get_contents('php://input');
		$data = json_decode($json, true);

		$date_start = $data['date_start'];
		$date_end = $data['date_end'];

		$result = $this->RForm->getDataDash($date_start, $date_end)->result();

        $this->output
             ->set_content_type('application/json')
             ->set_output(json_encode($result));
	}

	function dinasLuar(){
		header('Content-Type: application/json; charset=utf-8');

		$json = file_get_contents('php://input');
		$data = json_decode($json, true);

		// var_dump($data['data']['nopeg'][0]);

		// var_dump(var_export($data, TRUE));
		
		$save = var_export($data, TRUE);

		$this->Matt->saveLog($save);
		
		if($data['status'] == "insert"){
			$spl = $data['data']['id_spl'];
			$seq = $data['data']['seq'];
			$nopeg = $data['data']['nopeg'];
			$start = $data['data']['start'];
			$end = $data['data']['end'];
			$plant = $data['data']['plant'];
			
			$result = $this->Matt->collectDataDL($spl, $seq, $nopeg, $start, $end, $plant);
		}else if($data['status'] == "update"){
			$spl = $data['data']['id_spl'];
			$seq = $data['data']['seq'];
			$nopeg = $data['data']['nopeg'];
			$start = $data['data']['start'];
			$end = $data['data']['end'];
			$plant = $data['data']['plant'];
			
			$result = $this->Matt->updateDataDL($spl, $seq, $nopeg, $start, $end, $plant);
		}else if($data['status'] == "delete"){
			$spl = $data['data']['id_spl'];
			$seq = $data['data']['seq'];
			$plant = $data['data']['plant'];
			
			$result = $this->Matt->deleteDataDLSeq($spl, $seq, $plant);
		}else if($data['status'] == "delete_all"){
			$spl = $data['data']['id_spl'];
			$plant = $data['data']['plant'];
			
			$result = $this->Matt->deleteDataDL($spl, $seq, $plant);
		}

        $this->output
             ->set_content_type('application/json')
             ->set_output(json_encode($result));
	}

	function collectDinasLuar(){
		header('Content-Type: application/json; charset=utf-8');

		$data = $this->Matt->getAllData()->result();

		$this->output
             ->set_content_type('application/json')
             ->set_output(json_encode($data));
	}

	function collectOvetimeVerification2(){
		header('Content-Type: application/json; charset=utf-8');

		$data = $this->Matt->getAllVerification()->result();

		$this->output
             ->set_content_type('application/json')
             ->set_output(json_encode($data));
	}

	function offDinasLuar(){
		header('Content-Type: application/json; charset=utf-8');

		$json = file_get_contents('php://input');
		$data = json_decode($json, true);

		$data = $this->Matt->absensiCollected($data['id_absensi'])->result();

		$this->output
             ->set_content_type('application/json')
             ->set_output(json_encode($data));
	}

	function getTokenHalosis(){
		$token = $this->input->get_request_header('Authorization');

		if($token == "Bearer M4tTXYCoDcDTlndS844A9u4CRvB4AShE"){
			$token = $this->api->getToken(1)->row()->token;

			echo json_encode($token);
		}else{
			echo "Wrong Token!";
		}
	}

	function sendVerifMsg(){
		header('Content-Type: application/json; charset=utf-8');

		$token = $this->input->get_request_header('Authorization');

		if($token == "Bearer 123456789"){
			$json = file_get_contents('php://input');
			$data = json_decode($json, true);

			$to = $data['to'];
			$pesan = $data['pesan'];

			$token = $this->api->getToken(1)->row()->token;
			$authorization = "Authorization: Bearer " . $token;

			$post = '{
				"messaging_product": "whatsapp",
				"recipient_type": "individual",
				"to": "'.$to.'",
				"type": "template",
				"template": {
					"name": "approval_overtime",
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
									"payload": "approve_all"
								}
							]
						},
						{
							"type": "button",
							"sub_type": "quick_reply",
							"index": "1",
							"parameters": [
								{
									"type": "payload",
									"payload": "approve_selection"
								}
							]
						},
						{
							"type": "button",
							"sub_type": "quick_reply",
							"index": "2",
							"parameters": [
								{
									"type": "payload",
									"payload": "reject_all"
								}
							]
						},
						{
							"type": "body",
							"parameters":[
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

			echo json_encode($report);
		}else{
			echo "Wrong Token!";
		}
	}

	function saveOvertime(){
		header('Content-Type: application/json; charset=utf-8');

		$json = file_get_contents('php://input');
		$data = json_decode($json, true);

		$plant = $data['plant'];
		$dataVerif = $data['data'];

		$batch = $this->Matt->getMaxBatch($plant)->result();

		if(!$batch[0]->batch){
			$nextBatch = 1;
		}else{
			$nextBatch = (int)$batch[0]->batch + 1;
		}

		foreach($dataVerif as $dv){
			$spl = $dv['id_spl'];
			$seq = $dv['seq'];
			$id_user = $dv['id_user'];
			$nama = $dv['nama'];
			$tanggal = $dv['tanggal'];
			$start = $dv['start'];
			$end = $dv['end'];
			$task = $dv['task'];
			$ot = $dv['ot'];
			$batch = $nextBatch;
			$total_ot = $dv['total_ot'];
			$dept = $dv['dept'];
			$replacer = $dv['replacer'];
			$category = $dv['category'];
			$costcenter = $dv['costcenter'];

			$result = $this->Matt->saveOvertime($spl, $seq, $id_user, $ot, $total_ot, $plant, $batch, $dept, $nama, $tanggal, $start, $end, $task, $replacer, $category, $costcenter);
		}

		$deptNotif = $this->Matt->getAllBatchDept($plant, $nextBatch)->result();

		var_dump($deptNotif);

		foreach($deptNotif as $dn){
			$data = $this->Matt->getInfoOT($plant, $nextBatch, $dn->department)->result();

			$id_button = "verifOT_" . $nextBatch . "_" . $plant . "_" . $dn->department;
			
			$result = $this->sendOPeningVerif("6282228909916", $data[0]->tanggal_write, $data[0]->jumlah_orang, $id_button);
		}
		
        $this->output
             ->set_content_type('application/json')
             ->set_output(json_encode($result));
	}

	function overtimeCollected(){
		header('Content-Type: application/json; charset=utf-8');

		$json = file_get_contents('php://input');
		$data = json_decode($json, true);

		$id_data_verif = $data['id_data_verif'];

		$result = $this->Matt->overtimeCollected($id_data_verif);
		
        $this->output
             ->set_content_type('application/json')
             ->set_output(json_encode($result));
	}

	function getBatchOT(){
		header('Content-Type: application/json; charset=utf-8');

		$json = file_get_contents('php://input');
		$data = json_decode($json, true);

		$plant = $data['plant'];

		$result = $this->Matt->getMaxBatch($plant)->result();
		
        $this->output
             ->set_content_type('application/json')
             ->set_output(json_encode($result));
	}

	function sendOPeningVerif($to, $tanggal, $totalKaryawan, $id_button){
		$post = '{
					"messaging_product": "whatsapp",
					"recipient_type": "individual",
					"to": "'.$to.'",
					"type": "template",
					"template": {
						"name": "notif_ot_verif_update_2",
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
										"payload": "'.$id_button.'"
									}
								]
							},
							{
								"type": "body",
								"parameters": [
									{
										"type": "text",
										"text": "*'.$tanggal.'*"
									},
									{
										"type": "text",
										"text": "*('.$totalKaryawan.' Orang)*"
									}
								]
							}
						]
					}
				}';

		$token = $this->api->getToken(1)->row()->token;
		$authorization = "Authorization: Bearer " . $token;

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

		echo json_encode($report);
	}

	function sendWAVerif($to, $pesan){
		$post = '{
			"messaging_product": "whatsapp",
			"recipient_type": "individual",
			"to": "'.$to.'",
			"type": "template",
			"template": {
				"name": "approval_overtime",
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
								"payload": "approve_all"
							}
						]
					},
					{
						"type": "button",
						"sub_type": "quick_reply",
						"index": "1",
						"parameters": [
							{
								"type": "payload",
								"payload": "approve_selection"
							}
						]
					},
					{
						"type": "button",
						"sub_type": "quick_reply",
						"index": "2",
						"parameters": [
							{
								"type": "payload",
								"payload": "reject_all"
							}
						]
					},
					{
						"type": "body",
						"parameters":[
							{
								"type": "text",
								"text": "'.$pesan.'"
							}
						]
					}
				]
			}
		}';

		$token = $this->api->getToken(1)->row()->token;
		$authorization = "Authorization: Bearer " . $token;

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

		echo json_encode($report);
	}
}