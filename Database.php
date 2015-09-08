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
