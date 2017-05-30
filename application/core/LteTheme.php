<?php 
class LteTheme extends AdminController {
	public function __construct() { 
		parent::__construct();
		$this->template->set_admin_select_theme('admin/lte/');
		$this->template->set_template('index') ;
		$this->template->set_layout('index') ;
		$this->css() ;
		$this->js() ;
	}

	// Stylesheet
  public function css() {
      $stylesheet    = [ 'dist/css/AdminLTE.min.css' , 
                         'dist/css/skins/_all-skins.min.css' ,
                         'plugins/iCheck/flat/blue.css' ,
                         'plugins/morris/morris.css' ,
                         'plugins/jvectormap/jquery-jvectormap-1.2.2.css' ,
                         'plugins/datepicker/datepicker3.css' ,
                         'plugins/daterangepicker/daterangepicker.css' ,
                         'plugins/bootstrap-wysihtml5/bootstrap3-wysihtml5.min.css' ,
                         'plugins/iCheck/all.css',
                         'plugins/colorpicker/bootstrap-colorpicker.min.css',
                         'plugins/timepicker/bootstrap-timepicker.min.css'
                       ];
      $this->template->stylesheet->add($stylesheet);
  }
  
  // Javascript
  public function js() {
      $javascript  = [
                      'plugins/input-mask/jquery.inputmask.js',
                      'plugins/input-mask/jquery.inputmask.date.extensions.js',
                      'plugins/input-mask/jquery.inputmask.extensions.js',
                      'plugins/daterangepicker/daterangepicker.js',
                      'plugins/datepicker/bootstrap-datepicker.js',
                      'plugins/colorpicker/bootstrap-colorpicker.min.js',
                      'plugins/timepicker/bootstrap-timepicker.min.js',
                      'plugins/slimScroll/jquery.slimscroll.min.js',
                      'plugins/iCheck/icheck.min.js',
                      'plugins/fastclick/fastclick.js',
                      'dist/js/app.min.js',
                      'dist/js/demo.js',
      ];
      $this->template->javascript->add($javascript);
  }

}