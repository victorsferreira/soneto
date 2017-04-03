<?php

namespace Core;

include('teste.php');

define('Soneto','__soneto');

session_start();

// Import helper functions
require_once('core/helpers.php');

// Create Soneto
require_once('core/Soneto.php');
$soneto = Soneto::getInstance();
$GLOBALS[Soneto] = $soneto;

$soneto->loadApplicationJson();

// Set misc data
require_once('core/Middleware.php');
require_once('core/Model.php');

$soneto->set('directory',dirname(__FILE__));
$soneto->set('Middleware',Middleware::getInstance());
$soneto->set('Model',Model::getInstance());

// load application setup
require_once('core/Module.php');
require_once('core/ModuleBridge.php');
require_once('core/ModelBridge.php');
$soneto->loadModules();

// load helpers
includeAll('helpers');

// Import and load routes
require_once('config/routes.php');
global $routes;
$soneto->routes($routes);

// Import and load middlewares
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
