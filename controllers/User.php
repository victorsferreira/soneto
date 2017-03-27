<?php

namespace Controller;

class User extends \Core\Controller{

    public function foo($http){
        $r = Model::get('user')->all()->first();
        \core\debug($r->contacts()->first()->label());
    }

    function show($http){
        //   $soneto = $GLOBALS[Soneto];
        //   $http = $soneto->get('HTTP');
        echo $http->params['id'];
    }

}

?>
