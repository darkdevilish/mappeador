<?php
namespace mappeador;
use Mysqli;

class MySQLDatabase {

	private $_connection;
  private $last_query;
  // Store the single instance.
  private static $_instance;

  /**
   * Get an instance of the Database.
   * @return Database 
   */
  static function getInstance() {
    if (!self::$_instance) {
      self::$_instance = new self();
    }
    return self::$_instance;
  }

  function __construct() {
    $this->open_connection();
  }

  /**
   *You cannot unset static property, you need to set it to NULL instead.
   *If you dont set instance to NULL each time you get the instance after
   *closing the connection it will have the _connection undefined.
   */
  function close_connection() {
    if(isset($this->_connection)) {
      $this->_connection->close();
      unset($this->_connection);
      self::$_instance = NULL;
    }
  }

  function query($sql) {
    $this->last_query = $sql;
    $result = $this->_connection->query($sql);
    $this->confirm_query($result);
    return $result;
  }

  function prepared_stmt($sql) {
    $stmt = $this->_connection->prepare($sql);
    if(!$stmt) {
      die("Prepare failed: (" . $this->_connection->errno . ") " . $this->_connection->error);
    }
    return $stmt;
  }

  function confirm_bind_result($bind_result, $stmt) {
    if(!$bind_result){
      die("Binding failed: (" . $stmt->errno . ") " . $stmt->error);
    }
  }

  /**
   *Binds result to vars dynamically and @return result arrray
   */
  function bind_result_to_vars($stmt) {
    $params = array();
    $meta = $stmt->result_metadata();
    
    //fetch db field names from result to array
    while($field = $meta->fetch_field()){
      $params[] = &$row[$field->name];
    }

    //calls bind_result function dynamically with params array
    call_user_func_array(array($stmt, 'bind_result'), $params);
    return $row;
  }

  function execute($stmt) {
    if(!$execute_result = $stmt->execute()){
      die("Execute failed: (" . $stmt->errno . ") " . $stmt->error);
    } 
  }

  function insert_id() {
    return $this->_connection->insert_id;
  }

  private function open_connection() {
    $this->_connection = new mysqli(DB_SERVER, DB_USER, DB_PASS, DB_NAME);
    if(!$this->_connection) {
      die("Connect failed: (" . $mysqli->connect_errno . ") " . $mysqli->connect_error);
    }
  }

  private function confirm_query($result) {
    if(!$result){
      die("Database query failed: " . $this->_connection->errno . "<br><br>");
    }
  }

  /**
   * Empty clone magic method to prevent duplication. 
   */
  private function __clone() {}

}