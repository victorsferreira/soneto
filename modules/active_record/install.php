<?php

namespace Module;
use \Core\Soneto as Soneto;

Soneto::installModule('active_record',function($soneto){
  require(dirname(__FILE__).'/ActiveRecord.php');
  require(dirname(__FILE__).'/Collection.php');
  require(dirname(__FILE__).'/Record.php');

  $model = $soneto->get('Model');

  $model->setDriver(ActiveRecord::getInstance($model));
});

?>
