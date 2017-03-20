<?php

namespace Core;

define('Soneto','__soneto');

session_start();

// Import helper functions
require_once('core/helpers.php');

// Create Soneto
require_once('core/Soneto.php');
$soneto = Soneto::getInstance();
$GLOBALS[Soneto] = $soneto;

// Set misc data
$soneto->set('directory',dirname(__FILE__));

require_once('core/Middleware.php');
$soneto->set('Middleware',Middleware::getInstance());

require_once('core/Model.php');
$soneto->set('Model',Model::getInstance());

// load application setup
$soneto->loadApplicationJson($setup);
require_once('config/setup.php');
global $setup;
$setup = $soneto->setupCheck($setup);
$soneto->setup($setup);

// Import application database
require_once('config/database.php');
global $database;
$soneto->database($database);

// Import and load modules
require_once('config/modules.php');
global $modules;
$soneto->modules($modules);
$soneto->loadModules();

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
