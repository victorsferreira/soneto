<?php

namespace Core;

// Create Soneto
require_once('core/Soneto.php');
$soneto = Soneto::getInstance();
$soneto->start();
$GLOBALS[Soneto] = $soneto;

// Import and load routes
require_once('config/routes.php');
global $routes;
$soneto->routes($routes);

// Import and load middlewares
require_once('core/Middleware.php');
$soneto->set('Middleware',Middleware::getInstance());
require_once('config/middlewares.php');

// Creates HTTP object
require_once('core/HTTP.php');
$HTTP = HTTP::getInstance([
  'body' => $_POST,
  'query' => $_GET,
  'session' => $_SESSION,
  'cookie' => $_COOKIE,
  'params' => [],
  'method' => strtolower($_SERVER['REQUEST_METHOD']),
  'headers' => apache_request_headers()
]);

$soneto->set('HTTP',$HTTP);

$HTTP->findRoute();
$HTTP->handle();

?>
