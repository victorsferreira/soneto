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

function isMultidimensionalArray($array){
  return is_array($array) && count($array) == count($array, COUNT_RECURSIVE);
}

function isAssociativeArray($array){
  if(!is_array($array)) return false;
  if (array() === $array) return false;
  return array_keys($array) !== range(0, count($array) - 1);
}

function camelCaseToLispCase($input){
  return camelCaseToSnakeCase(snakeCaseToLispCase($input));
}

function snakeCaseToLispCase($input){
  return strtolower(str_replace('_','-',$input));
}

function lispCaseToCamelCase($input){
  return snakeCaseToCamelCase(lispCaseToSnakeCase($input));
}

function lispCaseToSnakeCase($input){
  return strtolower(str_replace('-','_',$input));
}

function snakeCaseToCamelCase($input,$ucfirst=false){
  $output = str_replace(' ','',ucwords(str_replace('_',' ', $input)));
  if(!$ucfirst) $output = lcfirst($output);
  return $output;
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
