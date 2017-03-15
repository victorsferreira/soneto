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

  public function status($code){
    http_response_code($code);
    return $this;
  }

  public function json($object){
    echo json_encode($object);
    return $this;
  }

  public function plain(){

  }

  public function handle($route){
    require_once('./core/Middleware.php');
    $middleware = Middleware::getInstance();

    $middleware->before(function($http){
      if(is_callable($this->action)){
        $function = $this->action;
        $function($this);
      }else{
        require_once('./core/Controller.php');
        $action_name = $this->action;

        $controller = Controller::load($this->controller);
        $controller->$action_name();
      }
    });

    $middleware->runBefore($this);
  }

  public function setRouteParams($route_path,$url){
    $route_parts = explode('/',$route_path);
    $url_parts = explode('/',$url);
    $params = [];

    foreach($route_parts as $i => $route_part){
      if($route_part != '' && $route_part[0] == ':'){
        $params[substr($route_part,1)] = $url_parts[$i];
      }
    }

    $this->params = $params;
  }

  public function setRouteData($route,$url){
    $action = $route['action'];

    if(is_string($action)){
      $parts = explode('#',$action);

      $this->controller = $parts[0];
      $this->action = $parts[1];
    }else if(is_callable($action)){
      $this->action = $action;
    }

    $this->url = $url;
  }
}

?>
