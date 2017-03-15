<?php

$soneto = $GLOBALS[Soneto];
$router = $soneto->module('router');

$router->get('/:name',function($http){
  $http->status(201)->json([
    'name'=>'victor'
  ]);
});

// $routes = [
//   [
//     'path' => '/:name',
//     'method' => 'get',
//     'action' => 'user#foo'
//   ]
// ];

 ?>
