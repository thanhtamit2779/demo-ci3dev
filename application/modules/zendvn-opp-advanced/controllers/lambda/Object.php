<?php
class Object extends My_Controller {
    private $name;
    private $birthday;
    public function __construct() {
        $this->name     = 'Bùi Thanh Tâm';
        $this->birthday = '27/07/1994';
    }
    public function showInfo($greeting) {
        $result  = function() use ($greeting) {
            echo "{$greeting}, $this->name - $this->birthday <br/>";
        };      
        return $result();
    }
    public function showHello($greeting) {
        $result  = static function() use ($greeting) {
            echo "{$greeting}";
        }; 
        return $result();
    }
    public function __invoke() {
        echo '<br>' . __METHOD__ ;
    }
    public function run() {
        $this->showInfo('Hello');
        self::showHello('Hi');
    }
}