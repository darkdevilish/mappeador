<?php
use mappeador\MySQLDatabase;

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

  function test_delete() {
    $this->assertTrue($this->john()->delete());
  }

  function test_destroy_created_users() {
    $this->clean_up();
    $users = User::find_all();

    $this->assertEmpty($users);
  }

  /**
   * @afterClass
   */
  static function tearDownAfterClass() {
    $db = MySQLDatabase::getInstance();
    $db->close_connection();
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

  private function clean_up() {
    $first_usr = array_shift(User::find_all());
    $usr_id = (int) $first_usr->id;
    $last_usr = array_pop(User::find_all());
    $last_usr_id = (int) $last_usr->id;

    for($usr_id; $usr_id<=$last_usr_id; $usr_id++){
      $u = User::find_by_id($usr_id);
      $u->delete();
    }
  }

}