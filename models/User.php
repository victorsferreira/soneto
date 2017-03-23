<?php

namespace Model;

class User extends \Core\Model{

  public $schema = [
    'name'=>['type'=>'string'],
    'lastname'=>['type'=>'string'],
    'birthday'=>['type'=>'date']
  ];

  public function __construct(){

  }

  public function foo($a1){
    echo $this->name();
    echo $a1;
  }

}

?>
