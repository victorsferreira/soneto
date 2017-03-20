<?php

namespace Module;
use \Core\Soneto as Soneto;

Soneto::installModule('mysql_connection',function($soneto){
  require(dirname(__FILE__).'/MySQLConnection.php');

  $model = $soneto->get('Model');

  $model->setConnection(MySQLConnection::getInstance($soneto->getDatabase()));
});

?>
