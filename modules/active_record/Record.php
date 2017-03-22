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
      if(isset($schema[$key])) $this->data[$key] = $value;
    }
  }

  // public function update(){}
  // public function delete(){}

  public function json(){
    return json_encode($this->data);
  }

  public function array(){
    return $this->data;
  }

  public function __call($name, $arguments){
    if(isset($this->data[$name])){
      if(empty($arguments)) return $this->data[$name];
      else $this->data[$name] = $arguments[0];
    }
    else if(method_exists($this->model,$name)){
      return call_user_func_array(array($this->model,$name),$arguments);
    }
  }
}

?>
