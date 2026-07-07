<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class TrackingDocFarmSurvey extends CI_Controller {

	function __construct(){
        parent::__construct();
		$this->load->helper('url');
		$this->load->model('M_Dash', 'dash');
		$this->load->model('M_Home', 'mhm');
		$this->load->model('M_DocFarmSurvey', 'doc');
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

		$department = $user['department'];
		$group_user = $user['group_user'];
		$id_menu = "14";
		$awal = 0;

        $data['user'] = $user;
		$data['group_user'] = $this->db->get_where('master_alias',['ori' => $this->session->userdata('group_user')])->row_array();
		$data['title'] = "CP - APPS | DOC Survey Farm";
		$data["js"] = "trackingDocFarmSurvey";

		$data['allPT'] = $this->doc->getAllPT()->result();

		$this->load->view('templates/dash_h',$data);
		$this->load->view('page_view/trackingDocFarmSurvey',$data);
		$this->load->view('templates/dash_f',$data);
	}

	function getFarm(){
		$idPT = $this->input->post('param');

		$farm = $this->doc->getAllFarm($idPT)->result();

		echo json_encode($farm);
	}

	function getTypeFarm(){
		$idfarm = $this->input->post('param');

		$type = $this->doc->getTypeFarm($idfarm)->result();

		echo json_encode($type);
	}

	function insertSurvey(){
		$data = html_escape($this->input->post());

		echo json_encode($data);
	}

	function uploadFile(){
		$id_survey = html_escape($this->input->post("id_survey"));

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

		for($i = 0; $i < 4; $i++){
			${"jawaban".$i} = html_escape($this->input->post("jawaban".$i));
			$insertJawaban = $this->doc->insertDetailSurvey($id_survey,"j",${"jawaban".$i});
		}

		if($insertJawaban){
			$result = true;
		}

		echo json_encode($result);
	}
}
