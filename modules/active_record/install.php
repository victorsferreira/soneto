<?php

namespace Module;
use \Core\Soneto as Soneto;

Soneto::installModule('active_record',function($soneto){
  require(dirname(__FILE__).'/ActiveRecord.php');

  $model = $soneto->get('Model');

  $model->setDriver(ActiveRecord::getInstance($model));
});

?>
