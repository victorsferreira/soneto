<?php

namespace Controller;

class User extends \Core\Controller{

  public function foo(){
    echo \Core\Model::User()->insert(['xxx'=>12121,'zzzz'=>'aaaa','yyyy'=>['$date'=>'15-08-1989'],'qqqq'=>['$number'=>1231231]]);
  }

  function show($http){
    //   $soneto = $GLOBALS[Soneto];
    //   $http = $soneto->get('HTTP');
      echo $http->params['id'];
  }

}

 ?>
