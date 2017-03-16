<?php

namespace Core;

class Middleware{
    static $instance = null;
    public $stack_before = [];

    public function getInstance(){
        if(self::$instance === null){
            self::$instance = new static();
        }

        return self::$instance;
    }

    private function __clone(){

    }

    protected function __construct(){

    }

    public function __call($name, $arguments){
        if($name == 'use'){
            call_user_func_array(array($this,'before'),$arguments);
        }
    }

    public function before($new_middleware){
        $this->stack_before[] = $new_middleware;
    }

    public function runBefore($http){
        if(count($this->stack_before) && $http instanceof \Core\HTTP){
            $next_middleware = array_shift($this->stack_before);
            $next_middleware($http,array($this,'runBefore'));
        }
    }
}

?>
