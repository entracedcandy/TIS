<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class KarirDashboard extends CI_Controller {

    function __construct(){
        parent::__construct();
		$this->load->helper('url');
        $this->karir = $this->load->database('karir', TRUE);
        $this->load->database();
		$this->load->model('M_KarirDashboard', 'kak');
		// $this->load->model('M_Dash', 'dash');
		// $this->load->model('M_Home', 'mhm');
		// $this->load->library('session');
		// $this->load->library('image_lib');

		// if(!$this->session->has_userdata('token')){
		// 	redirect('Home'); 
		// }else{
		// 	$token = $this->session->userdata('token');
        // 	$info = $this->dash->userValid($token)->row_array();

		// 	if($info['validasi'] == 'OUT'){
		// 		$user = $this->mhm->getIdUser($token)->result();
		// 		$logout = $this->mhm->logWrite($user[0]->id_user, $token, 'LOGOUT');

		// 		$this->session->unset_userdata('token');
		// 		$this->session->sess_destroy();

		// 		redirect('Home');
		// 	}
		// }

    }

    function testFLK(){
		$dataflk = $this->kak->getFLK()->result();
        var_dump($dataflk);
	}

}

?>