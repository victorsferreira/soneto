<?php

namespace Model;

class User extends \Core\Model{

  public $schema = [
    'name'=>['type'=>'string'],
    'lastname'=>['type'=>'string'],
    'birthday'=>['type'=>'date'],
    'city'=>['type'=>'relationship', 'model'=>'City'],
    // 'contacts'=>['type'=>'relationship', 'model'=>'Contact', 'many' => true, 'through'=>[
    //   'table'=>'contact'
    //   ]],
    'contacts'=>['type'=>'relationship', 'model'=>'Contact', 'many' => true, 'through'=>[
      'table'=>'user_contact',
      'field'=>'user_id',
      'target'=>'contact_id'
      ]],
    ];

    public function __construct(){

    }
  }

  ?>
