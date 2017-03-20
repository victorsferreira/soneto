<?php

namespace Controller;

class User extends \Core\Controller{

  public function foo(){
    $result = Model::User()->select(['name'=>'Victor']);
    var_dump($result);
  }

  function show($http){
    echo $http->params['id'];
  }

}

?>
