<?php

namespace Core;

class Model{

  public static $loaded = [];
  public $name = '';
  private static $driver;

  static $instance = null;

  public function getInstance($data=[]){
      if(self::$instance === null){
          self::$instance = new static($data);
      }

      return self::$instance;
  }

  private function __clone(){
  }

  protected function __construct(){
  }

  public static function load($name){
    require_once('./models/'.$name.'.php');

    $model_class_name = '\Model\\'.$name;

    return new $model_class_name;
  }

  public static function __callStatic($model_class_name, $arguments){
    if(isset(self::$loaded[$model_class_name])) return self::$loaded[$model_class_name];

    require_once('./models/'.$model_class_name.'.php');

    $model_class_name = '\Model\\'.$model_class_name;
    $model = new $model_class_name;
    $model->name = camelCaseToSnakeCase($model_class_name);
    self::$loaded[$model_class_name] = $model;

    return $model;
  }

  public function __call($name, $arguments){
    return call_user_func_array(array(self::$driver,$name),$arguments);
  }

  public function setDriver($driver){
    self::$driver = $driver;
  }

  // insert(['attr'=>['$date'=>'asdsadsa']])
  public function insert($data){
    $name = $this->name;

    $attributes = [];
    $values = [];

    foreach($data as $attribute => $value){
      $attributes[] = "'$attribute'";
      $values[] = $this->getFormattedValue($value);
    }

    $attributes = implode(', ',$attributes);
    $values = implode(', ',$values);

    $sql = "INSERT INTO $name ($attributes) VALUES ($values)";

    echo $sql;

    return $sql;
  }

  private function getFormattedValue($value){
    if(isAssociativeArray($value)){
      foreach($value as $k => $v){
        if($k == '$date') $v = "'$v'";
        else if($k == '$string') $v = "'$v'";
        else if($k == '$number') $v = $v;
        else if($k == '$bool' || $k == '$boolean') $v = $v;
        else if($k == '$raw') $v = $v;
        else if($k == '$func' || $k == '$function') $v = $v;
        break;
      }

      $value = $v;
    }else $value = "'$value'";

    return $value;
  }

  public function delete(){}

  public function update(){}



}

?>
