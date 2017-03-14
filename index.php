<?php
session_start();

require('config/setup.php');
require('core/helpers.php');

$_DATA = [
  'body' => $_POST,
  'query' => $_GET,
  'session' => $_SESSION,
  'cookie' => $_COOKIE,
  'params' => []
];

global $config;

if(isset($config['installation_path'][0]) && $config['installation_path'][0] !== '/'){
  $config['installation_path'] = '/'.$config['installation_path'];
}

$method = $_SERVER['REQUEST_METHOD'];
$url = $_SERVER['REQUEST_URI'];
$filename = __FILE__;

$directory = dirname($filename);
$parts = explode('/',$directory);
$directory = array_pop($parts);
if($directory == '/') $directory = array_pop($parts);

if(strpos($url,$config['installation_path']) !== false) $url = substr($url,strlen($config['installation_path']));
if($url[count($url) - 1] == '/') $url = substr($url,0,-1);

$routes = [
  [
    'path' => '/:id/:id2/:name',
    'action' => 'controller1#foo1'
  ]
];

foreach($routes as $route){
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

  if(preg_match('/'.$path.'/', $url)){
    setRouteParams($route['path'],$url);
    $action = $route['action'];

    if(is_callable($action)) $action();
    // pick controller and action
    break;
  }
}

?>
