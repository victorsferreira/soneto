<?php

namespace Module;
use \Core\Soneto as Soneto;

Soneto::installModule('router',function($soneto){
  require(dirname(__FILE__).'/Router.php');
  
  return Router::getInstance();
});

?>
