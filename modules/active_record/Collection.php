<?php

namespace Module;
use \Core\Soneto as Soneto;

class Collection{
  private $list = [];

  public function __construct($list){
    $this->list = $list;
  }

  public function last(){
    if(!empty($this->list)) return $this->list[count($this->list) - 1];
  }

  public function first(){
    if(!empty($this->list)) return $this->list[0];
  }

  public function get($index){
    if(isset($this->list[$index])) return $this->list[$index];
  }

  public function length(){
    return count($this->list);
  }

  public function at($index){
    return $this->get($index);
  }

  public function second($index){
    if(isset($this->list[1])) return $this->list[1];
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
    foreach($this->list as $i => $item) $callback($item,$i);
  }

  public function updateAll($data){
    foreach($this->list as $item) $item->update($data);
  }

  public function deleteAll(){
    foreach($this->list as $item) $item->remove();
  }
}

?>
