<?php

class Public_Controller extends MY_Controller {
	public $autoload = array(
        'libraries' => array(),
        'helpers'   => array(),
        'models'    => array('term_m', 'term_posts_m', 'termmeta_m', 'option_m', 'post_m', 'postmeta_m','usermeta_m','staffs/admin_m'));

	function __construct()
	{
		parent::__construct();
		$this->load->library('mdate');
		$this->load->library('table');
	}
}