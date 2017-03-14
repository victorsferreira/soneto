<?php

namespace Core;

class Middleware{
    static $instance = null;
    public $stack = [];

    public function getInstance(){
        if(self::$instance === null){
            echo 'criou';
            self::$instance = new static();
        }

        return self::$instance;
    }

    private function __clone(){}

    protected function __construct(){}

    public function useBefore(){}
}

 ?>
