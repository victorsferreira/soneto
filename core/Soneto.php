<?php

namespace Core;

class Soneto{
  private $data = [
    'misc' => [],
    'routes' => [],
    'setup' => [],
    'modules' => []
  ];

  private $installed_modules = [];

  static $instance = null;

  public function getInstance(){
    if(self::$instance === null){
      self::$instance = new static();
    }

    return self::$instance;
  }

  private function __clone(){

  }

  public function __construct(){

  }

  public function routes($routes){
    if($routes) $this->data['routes'] = $routes;
  }

  public function setup($setup){
    if($setup) $this->data['setup'] = $setup;
  }

  public function modules($modules){
    if($modules) $this->data['modules'] = $modules;
  }

  public function database($database){
    if($database){
      if(!isMultidimensionalArray($database)) $database = [$database];
      $this->data['database'] = $database;
    }
  }

  public function getDatabase($key=null){
    if($key){
      foreach($this->data['database'] as $database) if($database['id'] == $key) return $database;
    }else return $this->data['database'];
  }

  public function getRoutes($key=null){
    if($key){
      if(isset($this->data['routes'][$key])) return $this->data['routes'][$key];
    }else return $this->data['routes'];
  }

  public function getSetup($key=null){
    if($key){
      if(isset($this->data['setup'][$key])) return $this->data['setup'][$key];
    }else return $this->data['setup'];
  }

  public function getModules($key=null){
    if($key){
      if(isset($this->data['modules'][$key])) return $this->data['modules'][$key];
    }else return $this->data['modules'];
  }

  public function set($key,$value){
    $this->data['misc'][$key] = $value;
  }

  public function get($key){
    if(isset($this->data['misc'][$key])) return $this->data['misc'][$key];
  }

  public function loadModules(){
    foreach($this->data['modules'] as $module){
      require_once('./modules/'.$module.'/index.php');
    }
  }

  public function installModule($name,$install_function){
    $module = $install_function($this);
    if(!$module) $module = true;

    $this->installed_modules[$name] = $module;
  }

  public function module($name){
    if(isset($this->installed_modules[$name])) return $this->installed_modules[$name];
    else exit('Module "'.$name.'" has not been loaded');
  }

  public function setupCheck($setup){
    // required
    if(!isset($setup['installation_path'])) $setup['installation_path'] = '';

    // fixes
    if(isset($setup['installation_path'][0]) && $setup['installation_path'][0] !== '/') $setup['installation_path'] = '/'.$setup['installation_path'];

    return $setup;
  }


}

?>
