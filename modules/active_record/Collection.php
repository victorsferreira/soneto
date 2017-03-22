<?php

namespace Module;
use \Core\Soneto as Soneto;

class Collection{
  public $list = [];

  public function __construct($list){
    $this->list = $list;
  }

  public function add($item){
    $this->list[] = $item;
    return $this;
  }

  public function last(){
    return $this->list[count($this->list) - 1];
  }

  public function first(){
    return $this->list[0];
  }

  public function get($index){
    return $this->list[$index];
  }

  public function at($index){
    return $this->get($index);
  }

  public function second($index){
    return $this->list[1];
  }

  public function isEmpty(){
    return empty($this->list);
  }

  public function isBlank(){
    return $this->isEmpty();
  }

  public function hasAny(){
    return !$this->isEmpty();
  }

  public function each($callback){
    foreach($this->list as $i => $item){
      $callback($item,$i);
    }
  }

  public function updateAll($data){

  }
}

?>
