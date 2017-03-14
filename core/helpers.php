<?php

function regexReplaceRecursively($pattern,$replace,$input){
  while(true){
    $new_input = preg_replace('/'.$pattern.'/',$replace,$input);
    if($input == $new_input) break;
    else $input = $new_input;
  }

  return $input;
}

function setRouteParams($route,$url){
  $route_parts = explode('/',$route);
  $url_parts = explode('/',$url);
  $params = [];

  foreach($route_parts as $i => $route_part){
    if($route_part != '' && $route_part[0] == ':'){
      $params[substr($route_part,1)] = $url_parts[$i];
    }
  }

  $_DATA['params'] = $params;
}

 ?>
