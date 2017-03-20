<?php

$soneto = $GLOBALS[Soneto];
$router = $soneto->module('router');

$router->get('/list',function($http){
    global $router;
    $router->table();
});

$router->get('/foo','User#foo');

$router->resources('User');

// $routes = [
//   [
//     'path' => '/:name',
//     'method' => 'get',
//     'action' => 'User#foo'
//   ]
// ];


?>
