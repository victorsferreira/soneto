<?php

namespace Model;

class Helper{
  public static function __callStatic($name, $arguments){
    if(function_exists('\core\\'.$name)) $function = '\core\\'.$name;
    else if(function_exists('\helper\\'.$name)) $function = '\helper\\'.$name;

    if(isset($function)) call_user_func($function,$arguments);
  }
}

class Module{
  public static function get($name, $arguments){
    return \Core\Module::get($name);
  }
}

class Model{
  public static function __callStatic($name, $arguments){
    if($name == 'get') $name = $arguments[0];
    return \Core\Model::get($name);
  }
}

class Soneto{
  public static function __callStatic($name, $arguments){
    $instance = \Core\Soneto::getInstance();
    if(method_exists($instance, $name) return call_user_func([$instance,$name],$arguments);
  }
}

?>
