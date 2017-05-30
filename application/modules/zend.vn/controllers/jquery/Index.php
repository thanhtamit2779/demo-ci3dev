<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Index extends AdminController {

	public function __construct() {
		parent::__construct();
		$this->template->set_template('test');
	}

	public function home() {
		$result = $this->db->select()->from('wp_terms')->get()->result_array();

		if(empty($result)) return false ;

		$data['terms'] =  $result ;
		$this->render($data, 'test/index/home') ;
	}

	public function jquery() {
		$data         = null ;
	    $this->render($data, 'index/jquery') ;
	}

	public function hosting() {
		$this->load->library(['admin_form', 'admin_ui']);
		$this->render($data = null, 'index/hosting') ;
	}

}