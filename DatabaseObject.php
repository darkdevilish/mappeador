<?php
namespace mappeador\custom\orm;
use mappeador\custom\orm\MySQLDatabase;

abstract class DatabaseObject {

  static function find_all($orderBy=NULL) {
    $sql = "SELECT * FROM " . static::$table_name;
    if(!empty($orderBy)){
      $sql .= " ORDER BY " . $orderBy;
    }
    return static::find_by_sql($sql);
  }

  static function find_by_id($id=0) {
    $param = array($id);
    $sql = "SELECT * FROM ".static::$table_name." WHERE id=? LIMIT 1";
    $result_array = static::find_by_sql($sql, $param);
    return !empty($result_array) ? array_shift($result_array) : false;
  }

  static function find_by_sql($sql="", $bind_params_array=NULL){
    $db = MySQLDatabase::getInstance();

    $stmt = $db->prepared_stmt($sql);
    if(!empty($bind_params_array)){
      $params_type = static::check_bind_params_type($bind_params_array);
      $bind_result = $stmt->bind_param($params_type, join(", ", $bind_params_array));
      $db->confirm_bind_result($bind_result, $stmt);
    }
    $db->execute($stmt);
    $stmt->store_result();
    $object_array = array();
    $row = $db->bind_result_to_vars($stmt);
    while($stmt->fetch()){
      $record = array();
      foreach($row as $key => $val){
        $record[$key] = $val;
      }
      $object_array[] = static::instatiate($record);
    }
    $stmt->free_result();
    $stmt->close();
    return $object_array;
  }

  static function instatiate($record) {
    $class_name = get_called_class();
    $object = new $class_name;

    foreach($record as $attribute=>$value){
      if($object->has_attribute($attribute)){
        $object->$attribute = $value;
      }
    }
    return $object;
  }

  static function check_bind_params_type($bind_params) {
    $params_type = "";

    foreach($bind_params as $param){
      if(is_int($param)){ $params_type .= "i"; }
      if(is_string($param)){ $params_type .= "s"; }
    }
    return $params_type;
  }

  private function has_attribute($attribute) {
    return array_key_exists($attribute, $this->attributes());
  }

}