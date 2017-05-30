<?php
class Closure1 extends My_Controller {
    public function __construct() {
        parent::__construct();
    }
       
    public function index() {
        // case 1
        //         $name       = 'Bùi Thanh Tâm';
        //         $birthday   = '27/07/1994';
        //         $hello      = function() use ($name, $birthday) {
        //             echo '<h1>Xin chào ' . $name . ', ngày sinh: ' . $birthday;
        //         };
        //         $hello();

        // case 2
        $name       = 'Bùi Thanh Tâm';
        $birthday   = '27/07/1994';
        $hello      = function() use (&$name, $birthday) {
            $name   = mb_strtoupper($name);
            echo '<h1>Xin chào ' . $name . ', ngày sinh: ' . $birthday;
        };
        $hello();
        echo '<br/>' . $name; // thay đổi $name
    }
}
?>