<?php

namespace Core;

class Middleware{
    static $instance = null;
    public $stack = [];

    public function getInstance(){
        if(self::$instance === null){
            self::$instance = new static();
        }

        return self::$instance;
    }

    private function __clone(){}

    protected function __construct(){}

    public function before(){}

    public function next(){
        
    }
}

 ?>
