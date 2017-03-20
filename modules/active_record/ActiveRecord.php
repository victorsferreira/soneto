<?php

namespace Module;
use \Core\Soneto as Soneto;

class ActiveRecord{
    // driver e métodos
    static $instance = null;

    public function getInstance(){
      if(self::$instance === null){
        self::$instance = new self();
      }

      return self::$instance;
    }

    private function __clone(){
    }

    protected function __construct(){
    }
}

?>
