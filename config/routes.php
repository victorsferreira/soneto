<?php

$soneto = $GLOBALS[Soneto];
$router = $soneto->module('router');

$router->get('/:teste?',function($http){
  $http->status(201)->render('home');
});

// $routes = [
//   [
//     'path' => '/:name',
//     'method' => 'get',
//     'action' => 'user#foo'
//   ]
// ];

?>
