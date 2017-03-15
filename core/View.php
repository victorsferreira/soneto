<?php

namespace Core;

class View{
  public static function load($name){
    require_once('./controllers/'.$name.'.php');

    $controller_class_name = '\Controller\\'.$name;

    return new $controller_class_name;
  }
}

?>
