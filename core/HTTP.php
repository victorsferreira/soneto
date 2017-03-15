<?php

namespace Core;

class HTTP{
    public $params = [];
    public $query = [];
    public $body = [];
    public $session = [];
    public $cookie = [];
    public $headers = [];
    public $method = '';
    public $controller = '';
    public $action = false;
    public $url = '';

    static $instance = null;

    public function getInstance($data=[]){
        if(self::$instance === null){
            self::$instance = new static($data);
        }

        return self::$instance;
    }

    private function __clone(){

    }

    protected function __construct($data){
        foreach($data as $k => $v){
            if(property_exists($this,$k)) $this->$k = $v;
        }
    }

    public function status($code){
        http_response_code($code);
        return $this;
    }

    public function json($object){
        echo json_encode($object);
        return $this;
    }

    public function plain($text){
        echo $text;
    }

    public function findRoute(){
        $url = $_SERVER['REQUEST_URI'];

        $soneto = Soneto::getInstance();
        $installation_path = $soneto->get('installation_path');

        if(strpos($url,$installation_path) !== false) $url = substr($url,strlen($installation_path));
        if($url[strlen($url) - 1] == '/') $url = substr($url,0,-1);

        foreach($soneto->getRoutes() as $route){
            $method = isset($route['method']) ? strtolower($route['method']) : 'get';

            if($method !== $this->method) continue;

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
                $this->setRouteParams($route['path'],$url);
                $this->setRouteData($route,$url);
                break;
            }
        }
    }

    public function handle(){
        require_once('./core/Middleware.php');
        $middleware = Middleware::getInstance();

        $middleware->before(function($http,$next){
            if($http->method == 'get'){
                echo 'Não aceitamos requisições GET';
            }else $next($http);
        });

        if($this->action){
            $middleware->before(function($http){
                if(is_callable($this->action)){
                    $function = $this->action;
                    $function($this);
                }else{
                    require_once('./core/Controller.php');
                    $action_name = $this->action;

                    $controller = Controller::load($this->controller);
                    $controller->$action_name();
                }
            });

        }else{
            // not found
            $middleware->before(function($http){
                $http->status(404)->plain('Not found');
            });
        }

        $middleware->runBefore($this);
    }

    public function setRouteParams($route_path,$url){
        $route_parts = explode('/',$route_path);
        $url_parts = explode('/',$url);
        $params = [];

        foreach($route_parts as $i => $route_part){
            if($route_part != '' && $route_part[0] == ':'){
                $params[substr($route_part,1)] = $url_parts[$i];
            }
        }

        $this->params = $params;
    }

    public function setRouteData($route,$url){
        $action = $route['action'];

        if(is_string($action)){
            $parts = explode('#',$action);

            $this->controller = $parts[0];
            $this->action = $parts[1];
        }else if(is_callable($action)){
            $this->action = $action;
        }

        $this->url = $url;
    }
}

?>
