<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Plugin extends SiteController {

	public function __construct() {
		parent::__construct();
		$this->template->set_template('zend.vn');
		//$this->initSibar() ;
	}

	public function zoom() {
	    $javascript  = [
	        'common/bootstrap/js/bootstrap.js'
	    ];
	    $this->template->javascript->add($javascript);
	    
		$data    = null ;		
		$this->render($data, 'jquery/plugin/zoom') ;
	}
	
	public function js() {
	    
	}

	// public function initSibar() {
	// 	$this->config->load('zend.vn/menu') ;
	// 	$left  = $this->config->item('left') ;
	// 	echo '<pre>' ; print_r($left) ; echo '</pre>' ;
	// }

}