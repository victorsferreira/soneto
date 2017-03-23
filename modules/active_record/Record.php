<?php

namespace Module;
use \Core\Soneto as Soneto;

class Record{
  private $data = [];
  public $name;
  private $model;

  public function __construct($data,$model){
    $this->model = $model;
    $this->name = $this->model->name;
    $schema = $model->schema;
    foreach($data as $key => $value){
      if(isset($schema[$key]) || $key == 'id') $this->data[$key] = $value;
    }
  }

  public function update($data){
    $conditions = ['id'=>$this->data['id']];
    $params = [$data,$conditions];
    $affected = $this->model->publicFunction('update',$params);
    if($affected){
      foreach($data as $key => $value) $this->data[$key] = $value;
    }
  }

  public function delete(){
    $conditions = ['id'=>$this->data['id']];
    $params = [$conditions];
    $this->model->publicFunction('delete',$params);
  }

  public function json(){
    return json_encode($this->data);
  }

  public function array(){
    return $this->data;
  }

  public function __call($name, $arguments){
    if(isset($this->data[$name])){
      // getter/setter
      if(empty($arguments)) return $this->data[$name];
      else $this->data[$name] = $arguments[0];
    }
    else if(method_exists($this->model,$name)){
      // call model's method
      $reflection = new \ReflectionClass(get_class($this->model));
      $closure = $reflection->getMethod($name)->getClosure($this->model);
      $closure = $closure->bindTo($this);
      return call_user_func_array($closure,$arguments);
    }
  }
}

?>
