<?php

$task = $argv[1];

function stopServer(){
  $runtime = json_decode(file_get_contents('runtime.json'), true);
  $pid = $runtime['pid'];

  exec("kill -9 $pid > /dev/null 2>/dev/null &");
}

if($task == 'daemon:start'){
  stopServer();
  file_put_contents('runtime.json','{}'); //deletes and recreates runtime.json

  $dirname = dirname(__FILE__);

  exec("nohup php core/cli/server.php $dirname > /dev/null 2>/dev/null &");

  echo "\r\nServer started\r\n";
}else if($task == 'daemon:stop'){
  stopServer();

  echo "\r\nServer stoped\r\n";
}

?>
