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

  public function create($model, $data){
    $params = [$data];
    return $model->publicFunction('insert',$params);
  }

  public function remove($model, $conditions){
    if(is_numeric($conditions)) $conditions = ['id'=>$conditions];
    $params = [$conditions];
    return $model->publicFunction('delete',$params);
  }

  public function find($model, $conditions){
    $params = [$conditions];
    $result = $model->publicFunction('select',$params);
    $list = [];

    foreach($result as $item) $list[] = new Record($item,$model);

    return new Collection($list);
  }

  public function all($model){
    $result = $model->publicFunction('select',[]);
    $list = [];

    foreach($result as $item) $list[] = new Record($item,$model);

    return new Collection($list);
  }

  public function findOne($model, $conditions){
    if(is_numeric($conditions)) $conditions = ['id'=>$conditions];
    $params = [$conditions];
    $result = $model->publicFunction('select',$params);

    if(empty($result)) return null;

    return new Record($result[0],$model);
  }

}

?>
