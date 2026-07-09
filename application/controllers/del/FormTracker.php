<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class FormTracker extends CI_Controller {

	function __construct(){
        parent::__construct();
		$this->load->model('M_TForm', 'tform');
    }
	
	public function index(){
		$char_1 = urldecode($this->uri->segment(2, 0));
		$char_2 = urldecode($this->uri->segment(3, 0));
		$char_3 = urldecode($this->uri->segment(4, 0));

		$no_form = $char_1 . "/" . $char_2 . "/" . $char_3;

		$data['dataTrack'] = $this->tform->trackData($no_form)->result();

		$this->load->view('page_view/form_tracker', $data);
	}

	function createBarcode(){
		$string = 'code39';
		$this->set_barcode($string);
	}

	private function set_barcode($code)
	{
		// Load library
		$this->load->library('zend');
		// Load in folder Zend
		$this->zend->load('Zend/Barcode');
		// Generate barcode
		Zend_Barcode::render('code128', 'image', array('text'=>$code), array());
	}
}
