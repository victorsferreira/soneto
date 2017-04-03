<?php

$task = $argv[1];

if($task == 'start'){
  file_put_contents('connections','');
  file_put_contents('runtime.json','{}');

    $dirname = dirname(__FILE__);

    exec("nohup php core/cli/server.php $dirname > /dev/null 2>/dev/null &");

    echo "\r\nServer started\r\n";
  }else if($task == 'stop'){
    $runtime = json_decode(file_get_contents('runtime.json'), true);
    $pid = $runtime['pid'];

    exec("kill -9 $pid");

    echo "\r\nServer stoped\r\n";
  }

  ?>
