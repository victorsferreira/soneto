<?php

namespace Module;

\Core\Soneto::installModule('router',function($soneto){
  require(dirname(__FILE__).'/Router.php');
  
  return Router::getInstance();
});

?>
