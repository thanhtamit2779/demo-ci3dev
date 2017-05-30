<?php
class Variable extends My_Controller{
    public function index1() {
        $x  = 'abc';
        $$x = 'def'; // $abc = 'def'
        echo '<br/>' . $x;
        echo '<br/>' . $$x;
    }
    
    public function index2() {
        function showHello() {
            echo '<h1>showHello</h1>';    
        };
        $hello = 'showHello';
        $hello();
    }
    
    public function index3() {       
        $index3 = 'index2';
        $this->$index3();
    }
}