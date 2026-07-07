<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class FormViewer extends CI_Controller {

	function __construct(){
        parent::__construct();
		$this->load->model('M_Form', 'form');
    }
	
	public function index(){
		// $dataRecH = $this->form->getIdRecH($no_form)->row();

		$id_rec_h = urldecode($this->uri->segment(2, 0));

		$data['no_form'] = $this->form->getNoForm($id_rec_h)->row()->no_form;

		// $no_form = "CPI-2024-00206";

		$data['form'] = $this->form->formViewer($id_rec_h)->result();
		$data['opsi'] = $this->form->getOpsiForm($id_rec_h)->result();

		// var_dump($data['form']);

		// echo '<pre>' . var_export($data['opsi'], true) . '</pre>';

		// $data['approval'] = $this->form->getAppForm($no_form)->result();

		$data['approval'] = $this->form->getAllApprovalSet($id_rec_h)->result();

		$data['section'] = $this->form->getSectionForm($id_rec_h)->result();

		$title = $this->form->getTitleForm($id_rec_h)->result();

		$data['title'] = $title[0]->title;

		$this->load->view("page_view/formViewer", $data);
	}
}
