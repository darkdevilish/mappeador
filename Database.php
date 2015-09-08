<?php
namespace mappeador\custom\orm;
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

  private function open_connection() {
    $this->_connection = new mysqli(DB_SERVER, DB_USER, DB_PASS, DB_NAME);
    if(!$this->_connection) {
      die("Connect failed: (" . $mysqli->connect_errno . ") " . $mysqli->connect_error);
    }
  }

  /**
   * Empty clone magic method to prevent duplication. 
   */
  private function __clone() {}

}
