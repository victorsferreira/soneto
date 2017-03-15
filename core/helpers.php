<?php

namespace Core;

function regexReplaceRecursively($pattern,$replace,$input){
    while(true){
        $new_input = preg_replace('/'.$pattern.'/',$replace,$input);
        if($input == $new_input) break;
        else $input = $new_input;
    }

    return $input;
}

?>
