<?php

namespace Controller;

class Model{
  public static $loaded = [];

  public static function __callStatic($model_class_name, $arguments){
    if(isset(self::$loaded[$model_class_name])) return self::$loaded[$model_class_name];

    $original_model_class_name = $model_class_name;
    require_once('./models/'.$model_class_name.'.php');

    $model_class_name = '\Model\\'.$model_class_name;

    $model = new $model_class_name;
    $model->name = \Core\camelCaseToSnakeCase($original_model_class_name);
    self::$loaded[$model_class_name] = $model;

    return $model;
  }
}

class Helper{

}

?>
