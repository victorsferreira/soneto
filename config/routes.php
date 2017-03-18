<?php

// $soneto = $GLOBALS[Soneto];
// $router = $soneto->module('router');
//
// $router->get('/list',function($http){
//     global $router;
//     $router->table();
//     // foreach($list as $route){
//     //     $action = is_callable($route['action']) ? 'Closure' : $route['action'];
//     //     $method = strtoupper($route['method']);
//     //     echo "$method => {$route['path']} &nbsp; &nbsp; &nbsp; &nbsp; $action <br/>";
//     // }
// });
//
// $router->resources('User');

$routes = [
  [
    'path' => '/:name',
    'method' => 'get',
    'action' => 'User#foo'
  ]
];

?>
