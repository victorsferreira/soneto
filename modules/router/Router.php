<?php

namespace Module;
use \Core\Soneto as Soneto;

class Router{

    static $instance = null;

    public function getInstance(){
        if(self::$instance === null){
            self::$instance = new self();
        }

        return self::$instance;
    }

    private function __clone(){

    }

    protected function __construct(){

    }

    public function get($path,$action){
        $this->add('get',$path,$action);
    }

    public function post($path,$action){
        $this->add('post',$path,$action);
    }

    public function put($path,$action){
        $this->add('put',$path,$action);
    }

    public function patch($path,$action){
        $this->add('patch',$path,$action);
    }

    public function delete($path,$action){
        $this->add('delete',$path,$action);
    }

    public function getAll(){
        $soneto = Soneto::getInstance();
        return $soneto->getRoutes();
    }

    public function table(){
        $soneto = Soneto::getInstance();
        $soneto->get('HTTP')->render('../modules/router/table',[
            'routes'=>$soneto->getRoutes()
        ]);
    }

    public function resources($controller){
        $route = \Core\camelCaseToLispCase($controller);
        $this->get('/'.$route,$controller.'#index');
        $this->get('/'.$route.'/:id',$controller.'#show');
        $this->post('/'.$route,$controller.'#create');
        $this->put('/'.$route.'/:id',$controller.'#update');
        $this->delete('/'.$route.'/:id',$controller.'#delete');
    }

    private static function add($method,$path,$action){
        $route = [
            'path' => $path,
            'method' => $method,
            'action' => $action
        ];

        $soneto = Soneto::getInstance();
        $routes = $soneto->getRoutes();
        $routes[] = $route;
        $soneto->routes($routes);
    }
}

?>
