<?php

namespace Module;
use \Core\Soneto as Soneto;

class MySQLConnection{
  static $instance = null;
  private $database;
  public $link;
  public $result;

  public function getInstance($database){
    if(self::$instance === null){
      self::$instance = new self($database);
    }

    return self::$instance;
  }

  private function __clone(){

  }

  protected function __construct($database){
    $this->database = $database;
    $this->link = mysqli_connect($database['host'],$database['username'],$database['password'],$database['name']);
  }

  public function query($sql){
    $this->result = mysqli_query($this->link, $sql);
    if(!$this->result) echo mysqli_error($this->link);
    return $this->result;
  }

  public function toArray(){
    return mysqli_fetch_all($this->result);
  }

  public function affected(){
    return mysqli_affected_rows($this->link);
  }

  public function count(){
    return mysqli_num_rows($this->result);
  }

}

?>
