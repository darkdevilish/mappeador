<?php
use mappeador\custom\orm\MySQLDatabase;

class UserTest extends PHPUnit_Framework_TestCase {

  function test_save() {
    $john = new User();
    $john->name = "John";

    $this->assertTrue($john->save());
  }

  function test_find_all() {
    $this->create_some_users();
    $all_users = User::find_all();

    $this->assertNotEmpty($all_users);
  }

  private function create_some_users() {
    for($i=1; $i<4; $i++){
      $u = new User();
      $u->name = "John"."$i";
      $u->save();
    }
  }

}