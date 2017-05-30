<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class API_Controller extends MY_Controller {

	public $data = array();
	public $token = '';
	protected $authenticate = FALSE;
	protected $default_response = array('auth' => 0);
	public $autoload = array(
		'libraries' => array(),
		'helpers'   => array(),
		'models'    => array(
			'term_m', 
			'term_posts_m', 
			'termmeta_m', 
			'option_m', 
			'post_m', 
			'postmeta_m',
			'usermeta_m',
			'role_m',
			'permission_m',
			'api_user_m',
			'api_usermeta_m'
			)
		);

	function __construct()
	{	
		parent::__construct();
		
		$this->load->library('messages');
		$this->load->library('admin_ui');
		$this->load->library('admin_form');
		$this->load->library('table');
		$this->load->library('mdate');
		$this->load->model('staffs/admin_m');

		$this->token = $this->get('token');
		$this->authenticate();
	}

	protected function authenticate()
	{
		if(empty($this->token))
			return FALSE;

		$this->api_user_m->set_access_token($this->token);
		$has_permission = $this->api_user_m->authenticate();
		if(!$has_permission) return FALSE;

		$this->authenticate = TRUE;
		return $this->authenticate;
	}

	protected function render($data = array(), $code = 200)
	{
		$default = $this->default_response;
		$data = array_merge($default,$data);

		return $this->output
		->set_status_header($code)
		->set_content_type('application/json')
		->set_output(json_encode($data));
	}
	
	protected function get($index = NULL, $xss_clean = NULL)
	{
		return $this->input->get($index, $xss_clean);
	}

	protected function post($index = NULL, $xss_clean = NULL)
	{
		return $this->input->post($index, $xss_clean);
	}
}