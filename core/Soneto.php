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

  public function start(){
    define('Soneto','__soneto');

    session_start();

    // load default helpers
    require_once('core/helpers.php');

    // load configuration
    $this->loadApplicationJson();

    // load libraries and misc
    require_once('core/Model.php');
    require_once('core/Module.php');
    require_once('core/ModuleBridge.php');
    require_once('core/ModelBridge.php');

    $directory = dirname(__FILE__);
    $directory = explode('/',$directory);
    array_pop($directory);

    $this->set('directory',implode('/',$directory));
    $this->set('Model',Model::getInstance());

    // load modules
    $this->loadModules();

    // load user's helpers
    includeAll('helpers');
  }

  public function loadApplicationJson(){
    $data = json_decode(file_get_contents('./application.json'),true);
    if(!$data) $data = [];
    if(!isset($data['environment'])) $data['environment'] = 'development';
    $environment_data = json_decode(file_get_contents('./'.$data['environment'].'.json'),true);
    foreach($environment_data as $key => $value) $data[$key]=$value;

    $data = $this->checkApplicationData($data);

    $this->modules($data['modules']);
    $this->databases($data['databases']);

    unset($data['modules']);
    unset($data['databases']);

    $this->setup($data);
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

  public function databases($databases){
    if($databases){
      if(isMultidimensionalArray($databases)) $databases = [$databases];
      $this->data['databases'] = $databases;
    }
  }

  public function getDatabases(){
    return $this->data['databases'];
  }

  public function getDatabase($key=null){
    if(!$key) $key = $this->data['setup']['database_id'];
    foreach($this->data['databases'] as $database){
      if($database['id'] == $key) return $database;
    }
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
      require_once('./modules/'.$module.'/install.php');
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

  public function checkApplicationData($data){
    if(!isset($data['database'])) $data['database'] = [];
    if(!isset($data['modules'])) $data['modules'] = [];
    if(!isset($data['installation_path'])) $data['installation_path'] = '';

    if(isset($data['installation_path'][0]) && $data['installation_path'][0] !== '/') $data['installation_path'] = '/'.$data['installation_path'];
    if(!isset($data['database_id'])) $data['database_id'] = $data['databases'][0]['id'];

    return $data;
  }


}

?>
