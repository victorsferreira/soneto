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

// Import application setup
require_once('config/setup.php');
global $setup;
$setup = $soneto->setupCheck($setup);
$soneto->setup($setup);

// Import and load modules
require_once('config/modules.php');
global $modules;
$soneto->modules($modules);
$soneto->loadModules();

// Import and load routes
require_once('config/routes.php');
global $routes;
$soneto->routes($routes);

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

// refactor
$url = $_SERVER['REQUEST_URI'];
if(strpos($url,$setup['installation_path']) !== false) $url = substr($url,strlen($setup['installation_path']));
if($url[strlen($url) - 1] == '/') $url = substr($url,0,-1);

foreach($soneto->getRoutes() as $route){
  $method = isset($route['method']) ? strtolower($route['method']) : 'get';

  if($method !== $HTTP->method) continue;

  $path = $route['path'];
  $path = str_replace('(:any)','(.+)',$path); //any string
  $path = str_replace('(:number)','([0-9]+)',$path); //any number
  $path = str_replace('(:any?)','(.*)',$path); //any optional string
  $path = str_replace('(:number?)','([0-9]*)',$path); //any optional number

  $path = regexReplaceRecursively('\/:(.*?)\/','/(.+)/',$path); // any parameter
  $path = regexReplaceRecursively('\/:(.*?)$','/(.+)',$path); // any parameter
  $path = regexReplaceRecursively('\/:(.*?)\?\/','/(.*)/',$path); // any optional parameter
  $path = regexReplaceRecursively('\/:(.*?)\?$','/(.*)',$path); // any optional parameter

  $path = str_replace('/','\/',$path);

  if(preg_match('/^'.$path.'$/', $url)){
    // refactor
    $HTTP->setRouteParams($route['path'],$url);
    $HTTP->setRouteData($route,$url);

    // Handle request with found route
    $HTTP->handle($route);

    break;
  }
}

?>
