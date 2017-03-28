<?php

namespace Core;

class Module{
  public static function get($name){
    $soneto = Soneto::getInstance();
    return $soneto->module($name);
  }
}

?>
