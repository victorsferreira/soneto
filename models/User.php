<?php

namespace Model;

class User extends \Core\Model{

  public function __construct(){
    
  }

  public $schema = [
    'name'=>['type'=>'string'],
    'lastname'=>['type'=>'string'],
    'birthday'=>['type'=>'date']
  ];

  public function foo(){
    echo 'Foo xxx';
  }

}

 ?>
