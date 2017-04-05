<?php

use \Core\Daemon as Daemon;
$daemon = Daemon::getInstance();

$daemon->setJob(function($soneto){
  file_put_contents('current_time','It\'s '.date('h:i:s d/m/Y'));
});

?>
