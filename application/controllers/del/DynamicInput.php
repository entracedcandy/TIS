<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class DynamicInput extends CI_Controller {

	function __construct(){
        parent::__construct();
		$this->load->helper('url');
		$this->load->model('M_dynamic_input', 'di');
		$this->load->library('session');

		if($this->session->has_userdata('username')){
		}else{
			redirect('login'); 
		}
    }
	
	public function index(){
        $user = $this->db->get_where('z_master_user',['username' => $this->session->userdata('username')])->row_array();
		$department = $user['department'];
		$group_user = $user['group_user'];
		$data['user'] = $user;
		$data['group_user'] = $this->db->get_where('master_alias',['ori' => $this->session->userdata('group_user')])->row_array();

		if($department == "Administrator"){
			$data['department'] = "";
		}else{
			$data['department'] = $department;
		}

		$id_di = $this->uri->segment(3);
        $data_mm = $this->di->getMM($id_di)->result();

		if(count($data_mm) <= 0){
			redirect('Login');
		}else{
			$this->session->set_userdata('id_di', $id_di);
			$this->load->model('Dynamic_Input_Model/M_'. $id_di, 'module');

			$data_di = $this->di->getDI($id_di)->result();
	
			$all_func = $this->di->getFuncDI($id_di)->result();

			$func_pass = "";

			foreach($all_func as $f){
				$func_pass .= $f->func . "|" . $f->target . "|" . $f->param . "~";
			}

			$max_col = $this->di->getMaxCol($id_di)->result();
			$max_col = (int)$max_col[0]->elm_col;
			$data['max_col'] = $max_col;
	
			// var_dump($all_func);
	
			$data_elm = array();
			$id_elm_required = "";
			
			$id_elm_all = "";
			$type_elm_all = "";
			$name_elm_all = "";
	
			for($i = 1; $i <= $max_col; $i++){
				$data_col = $this->di->getElmDI($id_di, $i)->result(); 
				foreach($data_col as $dc){
					if($dc->elm_value != ""){
						if(strpos($dc->elm_value, "SELECT") !== FALSE){
							$valueQuery = $this->di->readQuery($dc->elm_value)->result();
							$name_variable = "value_" . $dc->elm_id . "_query";
							$data[$name_variable] = $valueQuery;
						}else{
							$valueQuery = $this->module->readFunction($dc->elm_value, "", "");
							$valueKey = key($valueQuery[0]);
							$name_variable = "value_" . $dc->elm_id . "_model";
							$data[$name_variable] = $valueQuery;
							$name_variable = "value_" . $dc->elm_id . "_key";
							$data[$name_variable] = $valueKey;
						}
					}

					if($dc->elm_required == "y"){
						$id_elm_required .= $dc->elm_id . "|";
					}

					$id_elm_all .= $dc->elm_id . "|";
					$type_elm_all .= $dc->elm_type . "|";
					$name_elm_all .= $dc->elm_caption . "|";
				}
				array_push($data_elm, $data_col);
			}

			$id_elm_required = rtrim($id_elm_required, "|");
			$id_elm_all = rtrim($id_elm_all, "|");
			$type_elm_all = rtrim($type_elm_all, "|");
			$name_elm_all = rtrim($name_elm_all, "|");
			$func_pass = rtrim($func_pass, "~");
	
			$data['data_elm_di'] = $data_elm;
			$data['id_elm_required'] = $id_elm_required;
			$data['id_elm_all'] = $id_elm_all;
			$data['type_elm_all'] = $type_elm_all;
			$data['name_elm_all'] = $name_elm_all;
			$data['func_pass'] = $func_pass;
	
			$data["title"] = $data_di[0]->caption;
	
			$this->load->view('templates/dash_header',$data);
			$this->load->view('templates/dash_sidebar',$data);
			$this->load->view('di/dynamicInput', $data);
		}
	}

	function saveData(){
		$param = $this->input->post('param');
		$id_di = $this->session->userdata('id_di');
		$this->load->model('Dynamic_Input_Model/M_'. $id_di, 'module');

		$funcInput = $this->di->getFuncInput($id_di)->result();
		$funcInput = $funcInput[0]->insert_query;

		$user = $this->db->get_where('z_master_user',['username' => $this->session->userdata('username')])->row_array();
		$department = $user['department'];
		$data_dept = $this->db->get_where('master_department',['username' => $this->session->userdata('username')])->row_array();
		// $inputData = $this->module->readFunction($funcInput, $param, "");

		echo json_encode($department);
	}
}
