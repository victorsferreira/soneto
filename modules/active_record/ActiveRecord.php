<?php

namespace Module;
use \Core\Soneto as Soneto;

class ActiveRecord{
  // driver e métodos
  static $instance = null;
  private $model;

  public function getInstance($model){
    if(self::$instance === null){
      self::$instance = new self($model);
    }

    return self::$instance;
  }

  private function __clone(){
  }

  protected function __construct($model){
    $this->model = $model;
  }

  public function create(){

  }
  public function remove(){

  }

  public function update(){

  }

  public function find(){

  }

  public function all($model){
    $result = $model->publicFunction('select',[]);
    $list = [];

    foreach($result as $item) $list[] = new Record($item,$model);

    return new Collection($list);
  }

  public function findOne(){

  }

}

?>
