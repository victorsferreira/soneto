<?php

namespace Core;

class HTTP{
    public $params = [];
    public $query = [];
    public $body = [];
    public $session = [];
    public $cookie = [];
    public $headers = [];
    public $method = '';
    public $controller = '';
    public $action = '';
    public $url = '';

    static $instance = null;

    public function getInstance($data=[]){
        if(self::$instance === null){
            self::$instance = new static($data);
        }

        return self::$instance;
    }

    private function __clone(){

    }

    protected function __construct($data){
        foreach($data as $k => $v){
            if(property_exists($this,$k)) $this->$k = $v;
        }
    }

    public function status(){

    }

    public function json(){

    }

    public function plain(){

    }

    public function handle($route){
        // $action = $route['action'];
        if(is_callable($this->action)) $this->action($this);
        else{
            
        }
    }

    public function call(){

    }
}

?>
