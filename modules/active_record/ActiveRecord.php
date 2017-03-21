<?php

namespace Module;
use \Core\Soneto as Soneto;

class ActiveRecord{
    // driver e métodos
    static $instance = null;
    private $model;

    public function getInstance($model){
      if(self::$instance === null){
        self::$instance = new self($model);
      }

      return self::$instance;
    }

    private function __clone(){
    }

    protected function __construct($model){
      $this->model = $model;
    }
}

?>
