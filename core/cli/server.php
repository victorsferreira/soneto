<?php

namespace Core;

$pid = getmypid();
$root = $argv[1];

function getApplicationJson(){
  global $root;
  return json_decode(file_get_contents($root.'/application.json'), true);
}

$runtime_json_path = $root.'/runtime.json';

$runtime = json_decode(file_get_contents($runtime_json_path), true);
$runtime['pid'] = $pid;
file_put_contents($runtime_json_path,json_encode($runtime));

// include Daemon
$soneto_class_path = $root.'/core/Soneto.php';
$daemon_class_path = $root.'/core/Daemon.php';
$daemon_path = $root.'/config/daemon.php';

require_once($soneto_class_path);
require_once($daemon_class_path);
require_once($daemon_path);

$daemon = Daemon::getInstance();

$application = getApplicationJson();

if(!isset($application['daemon'])) $application['daemon']=['interval'=>5];
else if(!isset($application['daemon']['interval'])) $application['daemon']['interval']=5;

$daemon_config = $application['daemon'];

$soneto = Soneto::getInstance();


while(true){
  $daemon->runJobs();
  // before starting over
  sleep($daemon_config['interval']);
}

?>
