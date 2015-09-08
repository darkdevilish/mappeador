<?php
use mappeador\custom\orm\MySQLDatabase;

class UserTest extends PHPUnit_Framework_TestCase {

  function test_save() {
    $john = new User();
    $john->name = "John";

    $this->assertTrue($john->save());
  }

}