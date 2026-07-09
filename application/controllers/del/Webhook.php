<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Webhook extends CI_Controller {

	function __construct(){
        parent::__construct();
		$this->load->model('M_Form', 'form');
		$this->load->model('M_API', 'api');
    }
	
	public function index(){
		header('Content-Type: application/json; charset=utf-8');

		$json = file_get_contents('php://input');
		$data = json_decode($json, true);
        
        // Kirim respons ke server yang melakukan request webhook
        // $this->output
        //     ->set_content_type('application/json')
        //     ->set_output(json_encode(['status' => $data]));

		$to = $data["data"]["from_phone_number"];
		$message = $data["data"]["message"];
		$action_id = $data["data"]["action_id"];
		// $file = $data["data"]["file"];

		$this->send_wa_text($to, $message);
	}

	function send_wa_text($to, $msg){
		$token = $this->api->getToken(1)->row()->token;

		$authorization = "Authorization: Bearer " . $token;
	
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
}
