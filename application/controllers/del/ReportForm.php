<?php

use PHPMailer\PHPMailer\PHPMailer;

defined('BASEPATH') OR exit('No direct script access allowed');

class ReportWP extends CI_Controller {

	public function __construct(){
        parent::__construct();

		$this->load->library('session');

        if ($this->session->has_userdata('username')) {
		} else {
			redirect('login');
		}
	}

	public function index(){
        $user = $this->db->get_where('master_user', ['username' => $this->session->userdata('username')])->row_array();
		$department = $user['department'];
		$group_user = $user['group_user'];
		$id_menu = "209";

		$data['user'] = $user;
		$data['group_user'] = $this->db->get_where('master_alias', ['ori' => $this->session->userdata('group_user')])->row_array();
		$data['title'] = "Report Work Permit";

		$this->load->view('templates/dash_header', $data);
		$this->load->view('templates/dash_sidebar', $data);
        $this->load->view("page_view/reportWP", $data);
	}

    function getData(){
        $data = $this->input->post('param');

        $date_start = html_escape($data['date_start']);
        $date_end = html_escape($data['date_end']);

        $flag_error = false;
        $message = "";
        $result = "";

        if($date_start == '' || $date_end == ''){
            $flag_error = true;
            $message = "Tanggal Mulai / Tanggal Berakhir Tidak Boleh Kosong";
        }else{
            if($date_start > $date_end){
                $flag_error = true;
                $message = "Tanggal Mulai Tidak Boleh Lebih Besar Dari Tanggal Berakhir";
            }else{
                $send_data = 
                '{
                    "date_start" : "'.$date_start.'", 
                    "date_end" : "'.$date_end.'"
                }';
        
                $result_data = $this->send_api($send_data);

                if(!$result_data){
                    $flag_error = true;
                    $message = "Data Tidak Ada";
                }else{
                    $ctr = 1;
                    foreach($result_data as $rd){
                        $result .= '<tr>';
                        $result .= '<td class="text-center align-middle">';
                        $result .= $ctr;
                        $result .= '</td>';
                        $result .= '<td class="text-center align-middle">';
                        $result .= $rd->no_form;
                        $result .= '</td>';
                        $result .= '<td class="text-center align-middle">';
                        $result .= $rd->form_created;
                        $result .= '</td>';
                        $result .= '<td class="text-center align-middle">';
                        $result .= $rd->pemohon;
                        $result .= '</td>';
                        $result .= '<td class="text-center align-middle">';
                        $result .= $rd->vendor;
                        $result .= '</td>';
                        $result .= '<td class="text-center align-middle">';
                        $result .= $rd->status_exp;
                        $result .= '</td>';
                        $result .= '<td class="text-center align-middle">';
                        $result .= '<button type="button" value="https://cpipga.com/TrackingForm/'.$rd->no_form.'" class="btn btn-success m-1" name="viewDetail" id="viewDetail_'.$rd->id_rec_form_h.'"><i class="fas fa-eye"></i></button>';
                        $result .= '<button type="button" value="'.$rd->link.'" class="btn btn-primary" name="downloadForm" id="dwnld_'.$rd->id_rec_form_h.'"><i class="fas fa-file-download"></i></button>';
                        $result .= '</td>';
                        $result .= '</tr>';

                        $ctr++;
                    }
                }
            }
        }

        $result_final = [
            "status" => $flag_error,
            "message" => $message,
            "result" => $result,
        ];

        echo json_encode($result_final);
    }

    function send_api($post){
        $authorization = "Authorization: Bearer 123456789";

		$ch = curl_init();

		curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type: application/json' , $authorization ));
		curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST");
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
		curl_setopt($ch, CURLOPT_POSTFIELDS, $post);
		curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
		curl_setopt($ch, CURLOPT_URL, "https://cpipga.com/API_VPS/");
		curl_setopt($ch, CURLOPT_ENCODING, "");
		curl_setopt($ch, CURLOPT_MAXREDIRS, 10);
		curl_setopt($ch, CURLOPT_TIMEOUT, 0);
		curl_setopt($ch, CURLOPT_HTTP_VERSION, CURL_HTTP_VERSION_1_1);
		$result = curl_exec($ch);
		curl_close($ch);
		$report = json_decode($result);

		return $report;
    }
}
