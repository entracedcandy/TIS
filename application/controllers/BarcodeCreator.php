<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class BarcodeCreator extends CI_Controller {

	function __construct(){
        parent::__construct();
		$this->load->library('phpqrcode');
    }
	
	public function index(){
		// header('Content-Type: image/png');
        // $text = "halo"; 
        // $this->phpqrcode->generate($text);
	}
}
