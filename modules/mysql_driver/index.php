<?php

namespace Module;
use \Core\Soneto as Soneto;

Soneto::installModule('mysql_driver',function($soneto){
  require(dirname(__FILE__).'/MySQLDriver.php');

  $model = $soneto->get('Model');

  $model->setDriver(new MySQLDriver);
});

?>
