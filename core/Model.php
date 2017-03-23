<?php

namespace Core;

class Model{

    public static $loaded = [];
    public $name = '';
    private static $driver;
    private static $connection;

    static $instance = null;

    public function getInstance(){
        if(self::$instance === null){
            self::$instance = new static();
        }

        return self::$instance;
    }

    private function __clone(){
    }

    protected function __construct(){
    }

    public static function get($model_class_name){
      $name = camelCaseToSnakeCase($model_class_name);
      if(isset(self::$loaded[$name])) return self::$loaded[$name];

      $model_class_name = snakeCaseToCamelCase($model_class_name,true);

      require_once('./models/'.$model_class_name.'.php');

      $model_class_name = '\Model\\'.$model_class_name;

      $model = new $model_class_name;
      $model->name = $name;
      self::$loaded[$name] = $model;

      return $model;
    }

    public static function __callStatic($model_class_name, $arguments){
        return self::get($model_class_name);
    }

    public function __call($name, $arguments){
        if(method_exists(self::$driver,$name)){
          array_unshift($arguments,$this);
          return call_user_func_array(array(self::$driver,$name),$arguments);
        }

        return $this->publicFunction($name,$arguments);
    }

    public function publicFunction($name,$arguments=null){
      $functions = [
        'insert'=>function($data){
          self::$connection->query($this->getInsertQuery($data));
          return self::$connection->affected();
        },
        'update'=>function($data,$conditions=null){
          self::$connection->query($this->getUpdateQuery($data,$conditions));
          return self::$connection->affected();
        },
        'delete'=>function($conditions){
          self::$connection->query($this->getDeleteQuery($conditions));
          return self::$connection->affected();
        },
        'select'=>function($conditions=null){
          self::$connection->query($this->getSelectQuery($conditions));
          return self::$connection->toArray();
        }
      ];

      if(isset($functions[$name])) return call_user_func_array($functions[$name],$arguments);
    }

    public function setDriver($driver){
        self::$driver = $driver;
    }

    public function setConnection($connection){
        self::$connection = $connection;
    }

    public function resolveValue($data){
        $operator = '$eq';
        if(is_array($data)){
            foreach($data as $key => $value){
                if(is_int($key)){
                    // $operator = '$eq';
                    $value = "'$value'";
                }else{
                    $parts = explode(':',$key);

                    // $operator = '$eq';
                    if(count($parts) === 1) $key = $parts[0];
                    else{
                        $operator = $parts[0];
                        $key = $parts[1];
                    }

                    if(in_array($key,['$number','$function','$raw','$n','$f','$r'])){

                    }else if(in_array($key,['$like','$l'])){
                        $operator = '$like';
                        $value = "'$value'";
                    }else if(in_array($key,['$string','$s'])){
                        $value = "'$value'";
                    }else if(in_array($key,['$nin','$in'])){
                        $operator = $key;
                        $options = [];

                        foreach($value as $option_key => $option_value){
                            $resolved = $this->resolveValue([$option_key=>$option_value]);
                            $options[] = $resolved['value'];
                        }

                        $value = '('.implode(', ',$options).')';
                    }else{
                        $value = "'$value'";
                    }
                }
            }
        }else{
            // $operator = '$eq';
            $value = "'$data'";
        }

        if(in_array($operator,['$eq','$equals'])) $operator = '=';
        else if(in_array($operator,['$nin','$not_in'])) $operator = 'NOT IN';
        else if(in_array($operator,['$like'])) $operator = 'LIKE';
        else if(in_array($operator,['$in','$in'])) $operator = 'IN';
        else if(in_array($operator,['$neq','$not_equals'])) $operator = '<>';
        else if(in_array($operator,['$gt','$greater'])) $operator = '>';
        else if(in_array($operator,['$lt','$lower'])) $operator = '<';
        else if(in_array($operator,['$gte','$greater_or_equals'])) $operator = '>=';
        else if(in_array($operator,['$lte','$lower_or_equals'])) $operator = '<=';

        return ['operator'=>$operator,'value'=>$value];
    }

    public function resolveCondition($data,$separator=' AND '){
        $sql = [];

        foreach($data as $key => $value){
            if(is_int($key) && isAssociativeArray($value)){
                $sql[] = '('.$this->resolveCondition($value,' OR ').')';
            }else{
                if(is_array($value)){
                    if(isAssociativeArray($value)){
                        $resolved = $this->resolveValue($value);
                        $v = $resolved['value'];
                        $op = $resolved['operator'];

                        $sql[] = "`$key` $op $v";
                    }else{
                        $options = [];

                        foreach($value as $option){
                            $resolved = $this->resolveValue($option);
                            $v = $resolved['value'];
                            $op = $resolved['operator'];

                            $options[] = "`$key` $op $v";
                        }

                        $sql[] = '('.implode(' OR ', $options).')';
                    }
                }else{
                    $resolved = $this->resolveValue($value);
                    $v = $resolved['value'];
                    $op = $resolved['operator'];

                    $sql[] = "`$key` $op $v";
                }
            }
        }

        return implode($separator, $sql);
    }

    public function getSelectQuery($conditions=null){
        if($conditions) $conditions = $this->resolveCondition($conditions);
        if($conditions) $conditions = "WHERE $conditions";

        $name = $this->name;

        return "SELECT * FROM $name $conditions";
    }

    public function getInsertQuery($data){
        $attributes = [];
        $values = [];

        foreach($data as $attribute => $value){
            $resolved = $this->resolveValue($value);

            $attributes[] = "`$attribute`";
            $values[] = $resolved['value'];
        }

        $attributes = implode(', ',$attributes);
        $values = implode(', ',$values);

        $name = $this->name;
        return "INSERT INTO $name ($attributes) VALUES ($values)";
    }

    public function getDeleteQuery($conditions){
        $conditions = $this->resolveCondition($conditions);

        $name = $this->name;
        return "DELETE FROM $name WHERE $conditions";
    }

    public function getUpdateQuery($data,$conditions){
        $updated_data = [];
        foreach($data as $attribute => $value){
            $resolved = $this->resolveValue($value);
            $updated_data[] = "`$attribute`=".$resolved['value'];
        }

        $conditions = $this->resolveCondition($conditions);
        $updated_data = implode(', ',$updated_data);

        $name = $this->name;

        return "UPDATE `$name` SET $updated_data WHERE $conditions";
    }

}


?>
