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

function lispCaseToCamelCase($input){
  return snakeCaseToCamelCase(str_replace('-','_',$input));
}

function lispCaseToSnakeCase($input){
  return str_replace('-','_',$input);
}

function snakeCaseToCamelCase($input){
  return str_replace(' ','',ucwords(str_replace('_',' ', $input)));
}

function camelCaseToSnakeCase($input) {
  preg_match_all('!([A-Z][A-Z0-9]*(?=$|[A-Z][a-z0-9])|[A-Za-z][a-z0-9]+)!', $input, $matches);
  $ret = $matches[0];
  foreach ($ret as &$match) {
    $match = $match == strtoupper($match) ? strtolower($match) : lcfirst($match);
  }
  return implode('_', $ret);
}

function debug($input){
  ?>
  <pre style="color:red">
    <?php var_dump($input); ?>
  </pre>
  <?php
}

?>
