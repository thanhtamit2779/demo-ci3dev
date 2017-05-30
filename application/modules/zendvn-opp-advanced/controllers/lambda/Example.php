<?php
class Example extends My_Controller {
    public function __construct() {
        parent::__construct();
    }
    public function index() {
        $input      = array(1,2,3,4,5,6,7,8,9);
        
        echo '<pre>';
        print_r($input);
        echo '</pre>';
        
        $compare    = function($max) {
            return function($value) use ($max) {
                return $value > $max;
            };
        };
        $output = array_filter($input, $compare(5));
        echo '<pre>';
        print_r($output);
        echo '</pre>';
    }
}