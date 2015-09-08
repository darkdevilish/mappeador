<?php
namespace mappeador\custom\orm;
use mappeador\custom\orm\MySQLDatabase;
use mappeador\custom\orm\DatabaseObject;

abstract class Mapper extends DatabaseObject{

  function save() {
  // A new record won't have an id yet.
    return isset($this->id) ? $this->update() : $this->create();
  }

  function create() {
    $db = MySQLDatabase::getInstance();
    
    $attrs = $this->attributes();
    unset($attrs['id']);
    $sql = $this->prepared_sql();
    $stmt = $db->prepared_stmt($sql);
    $params_type = static::check_bind_params_type($attrs);
    $bind_result = $stmt->bind_param($params_type, join(", ", $attrs));
    $db->confirm_bind_result($bind_result, $stmt);
    $db->execute($stmt);
    $this->id = $db->insert_id();
    $stmt->free_result();
    $stmt->close();
    return true;
  }

  protected function attributes() {
    $attributes = array();
    foreach(static::get_db_tbl_fields() as $field) {
      if(property_exists($this, $field)) {
        $attributes[$field] = $this->$field;
      }
    }
    return $attributes;
  }

  private function prepared_attrs() {
    $attrs = array();

    foreach($this->attributes() as $key => $value){
      $attrs[$key] = "?";
    }
    return $attrs;
  }
  
  private function prepared_sql() {
    $attributes = $this->prepared_attrs();
    unset($attributes['id']);
    $sql = "INSERT INTO ".static::$table_name." (";
    $sql .= join(", ", array_keys($attributes));
    $sql .= ") VALUES (";
    $sql .= join(", ", array_values($attributes));
    $sql .= ")";
    return $sql;
  } 

}