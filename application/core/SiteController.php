<?php
class SiteController extends MY_Controller {
	protected $_module  = '' ;
	public function __construct() {	
		parent::__construct();
        $this->load->database();
        $this->load->model('my_model') ;        
        $this->load->library('template');
        $this->template->set_layout('main');
        $this->css() ;
        $this->js();
	}
	

	// File view
	protected function render($data = null, $view_file = '') {
		if($view_file == '') {
			$view_file = $this->router->fetch_method();
		}
		$this->template->content->view($view_file, $data);
		$this->template->publish();
	}

	
	// Kết quả trả về json
	protected function renderJson($data = array(), $code = 200) {
		return $this->output
		->set_status_header($code)
		->set_content_type('application/json')
		->set_output(json_encode($data));
	}
	
	// Kết quả trả về Serialize
	protected function renderSerialize($data = array(), $code = 200) {
	    return $this->output
	    ->set_status_header($code)
	    ->set_content_type('application/json')
	    ->set_output(serialize($data));
	}
	
	// Css
	public function css() {
	      $stylesheet    = [  'common/bootstrap/css/bootstrap.min.css' ,
	    					  'common/font-awesome/css/font-awesome.min.css',
	    					  'assets/css/style.css' ,
                	          //'assets/css/skins/_all-skins.css' 
	    ];
	    $this->template->stylesheet->add($stylesheet);
	}
  
    // Javascript
    public function js() {
	      $javascript  = [ 
	      				  'common/bootstrap/js/bootstrap.min.js' ,
	      ];
	      $this->template->javascript->add($javascript);
  	}

  	// Set module
  	public function setModule($module = '') {
  		$this->_module = $module . '/';
  		if($module == '') $this->_module  = $this->uri->segment(1) ; 
  	}

  	// Get module
  	public function getModule() {
  		return $this->_module ;
  	}
}