<?php

namespace Controller;

class Model{
  public static $loaded = [];

  public static function __callStatic($name, $arguments){
    if($name == 'get') $name = $arguments[0];
    return \Core\Model::get($name);
  }
}

class Helper{

}

?>
