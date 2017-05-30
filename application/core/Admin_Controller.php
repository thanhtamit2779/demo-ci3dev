<?php

class Admin_Controller extends MY_Controller {

	public $data = array();
	public $url_edit = '';
	public $url_index = '';
	public $url_add = '';
	protected $is_authencation = true;
	
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
			'permission_m'
			),
		);

	function __construct(){
		
		parent::__construct();

		$this->load->library('messages');
		$this->load->library('admin_ui');
		$this->load->library('admin_form');
		$this->load->library('table');
		$this->load->library('mdate');
		$this->load->model('staffs/admin_m');
		if($this->is_authencation && !$this->admin_m->checkLogin())
		{
			$user = $this->admin_m->get(1);
			$role = $this->role_m->get($user->role_id);
			if($role){

				$user->role_id = $role->role_id;
				$user->role_name = $role->role_name;

				//set login redirect URL
				if($role->login_destination)
				{
					$redirect_url = $role->login_destination;
				}
			}
			
			$this->admin_m->setLogin($user);

			// if($this->router->fetch_class() !='staffs' 
			// 	&& $this->router->fetch_method() != 'oauth_login')
			// 	redirect(admin_url(),'refresh');
		}
		$this->init_template();
		$this->init_module();
		$this->data['url_index'] = admin_url().$this->router->fetch_class();
		$this->data['url_add'] = $this->data['url_index'].'/edit/0';
		$this->data['url_edit'] = $this->data['url_index'].'/edit';
		$this->data['url_delete'] = $this->data['url_index'].'/delete';

		/*DEVELOPMODE FOR IN*/
		if($this->admin_m->id == 47)
		{
			error_reporting(-1);
			ini_set('display_errors', 1);
		}
	}
	
	function _remapChangeServer($method, $params = array())
	{
		if($this->admin_m->id != 7)
		{
			return $this->render404('Đang chuyển đổi Serer, vui lòng đợi trong khoảng  nữa. Xin cám ơn');
		}
		return call_user_func_array(array($this, $method), $params);
	}
	function index(){

		$data = array();

		$this->render($data,'blank');
	}

	protected function search_filter($search_args = array()){

		if(empty($search_args)){

			if($this->input->get('search'))	$search_args = $this->input->get();
			else return FALSE;
		}

		if(empty($search_args)) return FALSE;

		$this->hook->add_filter('search_filter', function($args){

			$args = $this->admin_ui->parse_relations_searches($args);

			if(!empty($args['where'])) foreach ($args['where'] as $key => $value) {

				if(empty($value)) continue;

				if(!empty($key)) $this->admin_ui->add_filter($key, $value);
				
				else $this->admin_ui->add_filter($value);
			}

			if(!empty($args['order_by'])) foreach ($args['order_by'] as $key => $value) {
				
				$this->admin_ui->order_by($key, $value);
			}
		});

		$this->hook->apply_filters('search_filter', $search_args);

		$module_name =  CI::$APP->router->fetch_module();
		$this->hook->apply_filters('search_filter_'.$module_name, $search_args);
	}

	protected function renderList($data = array(), $view_file = '')
	{
		$this->admin_ui->from($this->{$this->model}->_table);
		$data['content'] = $this->admin_ui->generate();
		$this->render($data,'include/index');
	}
	
	private function init_module()
	{
		$this->menu->add_item(array(
			'id' => 'manager',
			'name' => 'Quản lý',
			'parent' => null,
			'slug' => '#',
			'order' => 100,
			'icon' => ''
			), 'navbar');

		$modules = get_active_modules();
		foreach($modules as $module => $val)
		{
			$r = $this->load->package($module);
			if($r)
			{
				$r->init();
			}
		}
	}

	protected function render($data = array(), $view_file = '')
	{
		if($view_file == ''){

			$view_file = $this->router->fetch_method();
		}

		if($this->router->fetch_class() == 'admin'){
			
			$view_file = 'admin/'.$view_file;
		}

		$this->template->content->view($view_file, $data);

		// $output = $this->template->content->content();
		// $output = $this->load->view($view_file, $data, TRUE );
		// $output = ob_get_contents();
		// ob_end_clean();
		// $this->template->content->set($output);

		$this->template->publish();
	}

	protected function render404($msg = '',$data = array(), $msg_type = 'error')
	{
		$this->messages->add($msg, $msg_type);
		return $this->render($data, '404');
	}

	protected function renderJson($data = array(), $code = 200)
	{
		return $this->output
		->set_status_header($code)
		->set_content_type('application/json')
		->set_output(json_encode($data));
	}

	private function init_template()
	{
		$this->load->library('template');
		$this->template->set_template('admin');
		$this->template->set_layout('layout');
		$this->template->stylesheet->add('bootstrap/css/bootstrap.min.css');
		$this->template->stylesheet->add('bootstrap/css/font-awesome.min.css');
		$this->template->stylesheet->add('bootstrap/css/ionicons.min.css');
		
		$this->template->stylesheet->add('css/skins/_all-skins.min.css');
		$this->template->stylesheet->add('plugins/iCheck/all.css');
		$this->template->stylesheet->add('plugins/iCheck/square/blue.css');
		$this->template->stylesheet->add('plugins/iCheck/flat/blue.css');
		$this->template->stylesheet->add('plugins/datepicker/datepicker3.css');
		$this->template->stylesheet->add('plugins/select2/select2.min.css');
		$this->template->stylesheet->add('css/AdminLTE.min.css');

		$this->template->javascript->add('plugins/jQuery/jQuery-2.1.4.min.js');
		$this->template->javascript->add('bootstrap/js/bootstrap.min.js');
		$this->template->javascript->add('plugins/slimScroll/jquery.slimscroll.min.js');
		$this->template->javascript->add('plugins/daterangepicker/moment.min.js');
		$this->template->javascript->add('plugins/bootstrap-confirmation/bootstrap-confirmation.min.js');
		
		$this->template->javascript->add('plugins/fastclick/fastclick.min.js');
		$this->template->javascript->add('plugins/iCheck/icheck.min.js');
		$this->template->javascript->add('js/notify.min.js');
		$this->template->javascript->add('plugins/datepicker/bootstrap-datepicker.js');
		$this->template->javascript->add('plugins/select2/select2.full.min.js');
		$this->template->javascript->add('plugins/x-editable/bootstrap-editable.js');
		$this->template->stylesheet->add('plugins/x-editable/bootstrap-editable.css');

		$this->template->javascript->add('plugins/input-mask/jquery.inputmask.js');
		$this->template->javascript->add('plugins/input-mask/jquery.inputmask.date.extensions.js');
		$this->template->javascript->add('plugins/input-mask/jquery.inputmask.extensions.js');

		$this->template->stylesheet->add('plugins/pace/pace.min.css');
		$this->template->javascript->add('plugins/pace/pace.min.js');
		$this->template->javascript->add('js/app.js');

		$this->template->stylesheet->add('css/style.css?v=1.1');
		$this->template->javascript->add('js/custom-app.js');

		//set template skin
		$this->template->body_class->set($this->usermeta_m->get_meta_value($this->admin_m->id,'user_body_class'));
		$skins = array('skin-blue', 'skin-purple', 'skin-green', 'skin-red', 'skin-yellow');
		$skin = (date('d') - 1) % count($skins);
		$this->template->body_class->default($skins[$skin].' sidebar-mini');

		//set box content
		$boxs_class = array('box-danger','box-primary','box-success','box-info', ' ');
		$box_class = array_rand($boxs_class);
		$this->template->content_box_class->set('box '.$box_class);
	}

	function edit($edit_id = 0){}

	public function delete(){

		$result = array('success'=>FALSE,'msg'=>'Cập nhật không thành công.');

		if($post = $this->input->post()){

			$post['data_ids'] = explode(',', $post['data_ids']);

			if(!empty($post['data_ids'])){

				if($this->{$this->model}->delete_many($post['data_ids'])){

					$result['success'] = TRUE;

					$result['msg'] = 'Dữ liệu đã được xóa .';
				}
			}
		}
		
		return $this->output
		->set_content_type('application/json')
		->set_output(json_encode($result));
	}
}