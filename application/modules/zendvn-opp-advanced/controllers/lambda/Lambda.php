<?php
class Lambda extends My_Controller {
    public function __construct() {
        parent::__construct();
    }
    public function index() {
        $name	= 'Peter';
    	$hello	= function(){
    					echo '<h3 style="color:red;font-weight:bold">Hello '.$name.'</h3>';
    				};    				
    	$hello();
    }
   
}