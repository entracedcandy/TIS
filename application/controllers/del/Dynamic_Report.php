<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Dynamic_Report extends CI_Controller {

	function __construct(){
        parent::__construct();
		$this->load->helper('url');
		$this->load->library('session');
		$this->load->model('M_dynamic_report', 'dynamic');
    }
	
	public function index(){
		$menu = $this->uri->segment('1') . "/" . $this->uri->segment('2') . "/" . $this->uri->segment('3');
		$idMenu = $this->dynamic->getIdMenu($menu)->result();
		$dataJudulMenu = $this->dynamic->judulMenu($idMenu[0]->id_menu);

		if($this->uri->segment('2') == "Index"){
			$isController = $this->uri->segment('3');
			$model_now = "M_" . $isController;
			$pass['model_now'] = "M_" . $isController;
			$this->session->set_userdata($pass);
		}else{
			$model_now = $this->session->userdata('model_now');
		}

		$this->load->model('Dynamic_Report_Model/'. $model_now, 'module');

		$sess['idMenu'] = $idMenu[0]->id_menu;
		
		$data['judulMenu'] = $dataJudulMenu->caption;
		$idReport = $this->uri->segment('3');
		$data['sourceMenu'] = $this->uri->segment('3');
		
		//Wajib Untuk Di CPAR
		$data['title'] = $dataJudulMenu->caption;
		$data['user'] = $this->db->get_where('master_user',['username' => $this->session->userdata('username')])->row_array();
		// $data['group_user'] = $this->db->get_where('master_alias',['ori' => $this->session->userdata('group_user')])->row_array();

		// ========================================================= Breadcrumbs ========================================================= //

		if($dataJudulMenu->submenu != 0){
			$dataSubMenu = $this->dynamic->cekSubMenu($dataJudulMenu->submenu);
			$data['breadcrumbs'] = array(ucfirst($dataJudulMenu->menu_type), $dataSubMenu->caption, $dataJudulMenu->caption);
		}else{
			$data['breadcrumbs'] = array(ucfirst($dataJudulMenu->menu_type), $dataJudulMenu->caption);
		}

		// ========================================================= Component ========================================================= //

		$report = $this->dynamic->dataReport($idReport)->result();

		$has_chart = false;

		foreach($report as $r){
			if($r->chart === "y"){
				$has_chart = true;
				$value_chart = $r->filter_chart;
				$data["totalCmp"] = 3;
			}else{
				$data["totalCmp"] = 2;
			}

			$data['modelFuncName'] = $r->value;
		}

		$allFunc = $this->dynamic->getAllFunc($idMenu[0]->id_menu)->result();
		$element = $this->dynamic->getAllElm($idReport)->result();

		$last_seq = (int)end($element)->seq;
		$last_col = (int)end($element)->col;

		if($last_col < 3){
			$col_filter_chart = $last_col + 1;
			$seq_filter_chart = 1;
		}else{
			$col_filter_chart = $last_col;
			$seq_filter_chart = $last_seq + 1;
		}

		$data_chart = (object) array(
			'id_element' => 'filter_chart',
			'element' => 'multiple',
			'caption' => 'Filter Chart',
			'table_param' => 'n',
			'chart_param' => 'n',
			'empty' => 'y',
			'hidden' => 'n',
			'bagian' => 'body',
			'value' => $value_chart,
			'col' => $col_filter_chart,
			'seq' => $seq_filter_chart,
			'enable' => 'y'
		);

		array_push($element, $data_chart);

		// var_dump($element);

		$funcAll = "";

		foreach($allFunc as $af){
			$funcAll .= $af->seq . ";";
			$funcAll .= $af->event . ";";
			$funcAll .= $af->func . ";";
			$funcAll .= $af->param . "|";
		}

		$data['allFunc'] = $funcAll;
		$data['element'] = $element;

		$dataVarElement = array();
		$requiredElement = "";
		$tableParam = "";
		$chartParam = "";
		$select2elm = "";

		foreach($element as $e){
			if($e->value !== ""){
				if($e->element == "combobox" || $e->element == "multiple" || $e->element == "combobox-search"){
					$param = "";
					$state = "";
					$nameElement = "elm_" . $e->id_element;
					
					$value = $this->module->readFunction($e->value, $param, $state);

					if(gettype($value[0]) == "object"){
						// var_dump($value);
						$field = array();
						for($i = 0; $i < count($value); $i++){
							array_push($field, key($value[$i]));
						}
					}else{
						$field = $value;
						$value_pass = array();

						// var_dump($value);

						foreach($value as $v){
							$value_pass_data = (object) array($v => $v);
							array_push($value_pass, $value_pass_data);
						}
						$value = $value_pass;
					}

					$data[$nameElement] = array();
					array_push($data[$nameElement], $value);
					array_push($data[$nameElement], $field);
				}
			}
			
			if($e->element == "multiple" || $e->element == "combobox-search"){
				if($e->id_element == "filter_chart"){
					$select2elm .= $e->id_element . ",";	
				}else{
					$select2elm .= "elm_" . $e->id_element . ",";	
				}
			}

			if($e->empty == "n"){
				$requiredElement .= "elm_" . $e->id_element . ",";
			}

			if($e->table_param == "y"){
				$tableParam .= "elm_" . $e->id_element . ",";
			}

			if($e->chart_param == "y"){
				$chartParam .= "elm_" . $e->id_element . ",";
			}
		}

		$data['select2elm'] = $select2elm;
		$data['requiredElement'] = $requiredElement;
		$data['tableParam'] = $tableParam;
		$data['chartParam'] = $chartParam;
		
		$this->load->view('templates/dash_header',$data);
		$this->load->view('templates/dash_sidebar',$data);
		$this->load->view('dynamic_report/dynamicReport', $data);
	}

	function getDataJS(){
		$model_now = $this->session->userdata('model_now');
		$this->load->model('Dynamic_Report_Model/'. $model_now, 'module');
		// $this->load->model('Dynamic_Report_Model/M_DR_2023_00001', 'module');

		$param = $this->input->post('pass_param');
		$m_func_name = $this->input->post('m_func_name');
		$state = $this->input->post('state');

		// $param = 	[
		// 				"10",
		// 				"1",
		// 				"2023-06-01",
		// 				"2023-06-06",
		// 				"A",
		// 				"1"
		// 			];
		// $m_func_name = "tableData";
		// $state = "table";

		$result = $this->module->readFunction($m_func_name, $param, $state);

		echo json_encode($result);
	}

	function exportExcel(){
		$data = $this->input->post('pass_data');

		echo json_encode($data);
	}
}
