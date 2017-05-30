<?php
require APPPATH."third_party/MX/Controller.php";
/**
 * The base controller which is used by the Front and the Admin controllers
 */
class MY_Controller extends MX_Controller {

	 /**
     * @var array Stores a number of items to 'autoload' when the class
     * constructor runs. This allows any controller to easily set items which
     * should always be loaded, but not to force the entire application to
     * autoload it through the config/autoload file.
     */
	public function __construct() {
		parent::__construct() ;
	}
}
