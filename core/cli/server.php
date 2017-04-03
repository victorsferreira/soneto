<?php

$pid = getmypid();
$root = $argv[1];
$runtime_json_path = $root.'/runtime.json';
$connections_path = $root.'/connections';

$runtime = json_decode(file_get_contents($runtime_json_path), true);
$runtime['pid'] = $pid;

file_put_contents($runtime_json_path,json_encode($runtime));

while(true){
  sleep(1);
  
}

 ?>
