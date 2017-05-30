<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Facebook extends My_Controller  {
   // construct
   public function __construct() {
        parent::__construct();
        //$this->load->model('Facebook');
   }
   
   // index
   public function index() {
       // Include the facebook api php libraries
       require_once LIBS_PATH . 'facebook/facebook.php';
   }   
}