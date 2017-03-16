<?php

namespace Controller;

class User extends \Core\Controller{

  public function foo(){
    echo 'Foo xxx';
  }

  function show($http){
    //   $soneto = $GLOBALS[Soneto];
    //   $http = $soneto->get('HTTP');
      echo $http->params['id'];
  }

}

 ?>
