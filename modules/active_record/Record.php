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

  private function resolveRelationship($field,$value){
    $model_name = $field['model'];
    $model = \Core\Model::get($model_name);

    if(isset($field['many']) && $field['many']){
      if(!isset($field['through'])) $field['through'] = [];
      $through = $field['through'];

      if(!isset($through['field'])) $through['field'] = $this->model->name.'_id';
      if(!isset($through['table'])) $through['table'] = $model->name;

      if(isset($through['target'])){
        $target = $through['target'];
        $result = $model->select([$through['field']=>$this->data['id']],$through['table']);

        $list = [];
        foreach($result as $item) $list[] = $model->findOne($item[$target]);
      
        return new Collection($list);
      }else return $model->find([$through['field']=>$this->data['id']]);
    }else{
      return $model->findOne($value);
    }
  }

  public function json(){
    return json_encode($this->data);
  }

  public function array(){
    return $this->data;
  }

  public function __call($name, $arguments){
    $schema = $this->model->schema;
    if(isset($schema[$name]) || $name == 'id'){
      // getter/setter
      $type = $schema[$name]['type'];

      if(empty($arguments)){
        $has_field = isset($this->data[$name]);
        if($has_field) $value = $this->data[$name];
        else $value = '';

        if($type == 'rel' || $type == 'relationship'){
          return $this->resolveRelationship($schema[$name], $value);
        }else return $value;
      }else if($has_field) $this->data[$name] = $arguments[0];
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
