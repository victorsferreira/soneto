<?php

function regexReplaceRecursively($pattern,$replace,$input){
    while(true){
        $new_input = preg_replace('/'.$pattern.'/',$replace,$input);
        if($input == $new_input) break;
        else $input = $new_input;
    }

    return $input;
}

function setRouteParams($route_path,$url){
    $HTTP = $GLOBALS['__soneto']['HTTP'];

    $route_parts = explode('/',$route_path);
    $url_parts = explode('/',$url);
    $params = [];

    foreach($route_parts as $i => $route_part){
        if($route_part != '' && $route_part[0] == ':'){
            $params[substr($route_part,1)] = $url_parts[$i];
        }
    }

    $HTTP->params = $params;
}

function setRouteData($route,$url){
    $HTTP = $GLOBALS['__soneto']['HTTP'];

    $action = $route['action'];

    if(is_string($action)){
        $parts = explode('#',$action);

        $HTTP->controller = $parts[0];
        $HTTP->action = $parts[1];
    }else if(is_callable($action)){
        $HTTP->action = $action;
    }

    $HTTP->url = $url;
}

?>
