<?php

namespace Core;

class Daemon{
  static $instance = null;
  public $jobs = [];

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

  public function setJob($callback){
    if(is_callable($callback)){
      $this->jobs[] = $callback;
      return true;
    }

    return false;
  }

  public function getJobs(){
    return $this->jobs;
  }

  public function runJobs($soneto = null){
    $jobs = $this->getJobs();

    if(!$soneto){
      $soneto_class_path = dirname(__FILE__).'/Soneto.php';
      require_once($soneto_class_path);
      $soneto = Soneto::getInstance();
    }

    foreach($jobs as $job){
      $job($soneto);
    }
  }
}

?>
