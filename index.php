<?php

session_start();

require('config/setup.php');
require('core/helpers.php');
require('core/HTTP.php');

$GLOBALS['__soneto'] = [];

global $config;

if(isset($config['installation_path'][0]) && $config['installation_path'][0] !== '/'){
    $config['installation_path'] = '/'.$config['installation_path'];
}

$url = $_SERVER['REQUEST_URI'];
$filename = __FILE__;

$GLOBALS['__soneto']['directory'] = dirname($filename);

if(strpos($url,$config['installation_path']) !== false) $url = substr($url,strlen($config['installation_path']));
if($url[strlen($url) - 1] == '/') $url = substr($url,0,-1);

$HTTP = core\HTTP::getInstance([
    'body' => $_POST,
    'query' => $_GET,
    'session' => $_SESSION,
    'cookie' => $_COOKIE,
    'params' => [],
    'method' => strtolower($_SERVER['REQUEST_METHOD']),
    'headers' => apache_request_headers()
]);

$GLOBALS['__soneto']['HTTP'] = $HTTP;

$routes = [
  [
    'path' => '/:name',
    'method' => 'get',
    'action' => 'user#foo'
  ]
];

foreach($routes as $route){
    $method = isset($route['method']) ? strtolower($route['method']) : 'get';

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
        setRouteParams($route['path'],$url);
        setRouteData($route,$url);

        $HTTP->handle($route);

        break;
    }
}

?>
