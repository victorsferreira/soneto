<?php

namespace Module;

class Router{

  static $instance = null;

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

  public function get($path,$action){
    $this->add('get',$path,$action);
  }

  public function post($path,$action){
    $this->add('post',$path,$action);
  }

  public function put($path,$action){
    $this->add('put',$path,$action);
  }

  public function patch($path,$action){
    $this->add('patch',$path,$action);
  }

  public function delete($path,$action){
    $this->add('delete',$path,$action);
  }

  public function resource($path,$action){
    // $this->add('get',$path,$action);
  }

  private static function add($method,$path,$action){
    $route = [
      'path' => $path,
      'method' => $method,
      'action' => $action
    ];

    $soneto = \Core\Soneto::getInstance();
    $routes = $soneto->getRoutes();
    $routes[] = $route;
    $soneto->routes($routes);
  }
}

?>
