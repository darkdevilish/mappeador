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

  function test_find_all_order_by(){
    $first_User = array_shift(User::find_all("id DESC"));
    $last_user = array_pop(User::find_all());

    $this->assertEquals($first_User->id, $last_user->id);
  }

  function test_find_by_id() {
    $john = User::find_by_id($this->john()->id);

    $this->assertEquals($john->name, "John");
  }

  function test_count_all() {
    $count_all = User::count_all();

    $this->assertTrue($count_all != 0);
  }

  function test_update() {
    $jane = User::find_by_id((int) $this->john()->id);
    $jane->name = "Jane";

    $this->assertTrue($jane->update());
  }

  private function create_some_users() {
    for($i=1; $i<4; $i++){
      $u = new User();
      $u->name = "John"."$i";
      $u->save();
    }
  }

  private function john() {
    $john = array_shift(User::find_all());
    return $john;
  }

}