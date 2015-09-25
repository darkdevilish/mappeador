<?php
use mappeador\MySQLDatabase;

require_once __DIR__ . '/initialize.php';

  //test save()
echo "<h3>Test Save</h3>";
  $john = new User();
  $john->name = "John";

  if($john->save()){
    echo $john->name . " was created.";
    echo "<br><br>";
  } else {
    echo $john->name . " was not created.";
    echo "<br><br>";
  }

  //test new Model(array)
echo "<h3>Test instantiation with array params</h3>";
  $jack = new User(array( 'name' => 'Jack' ));
  
  if($jack->save()){
    echo $jack->name . " was created.";
    echo "<br><br>";
  } else {
    echo $jack->name . " was not created.";
    echo "<br><br>";
  }

  //creates some users
  for($i=1; $i<4; $i++){
    $u = new User();
    $u->name = "John"."$i";
    $u->save();
  }
  //test find_all()
echo "<h3>Test find_all()</h3>";
  $all_users = User::find_all();
  foreach($all_users as $user){
    echo $user->id . " | " . $user->name . "<br><br>";
  }

  //test find_all(orderBy)
  echo "<h4>Order By id DESC</h4>";
  $all_users = User::find_all("id DESC");
  foreach($all_users as $user){
    echo $user->id . " | " . $user->name . "<br><br>";
  }

  //test find_by_id()
echo "<h3>Test find_by_id()</h3>";
  $john = array_shift( User::find_all() );
  $find_john = User::find_by_id( $john->id );
  echo $find_john->name . "<br><br>";

  //test find_where()
echo "<h3>Test find_where()</h3>";
  $find_johns = User::find_where( "name = ?", array("John") );
  foreach($find_johns as $johns) {
    echo $johns->id . " | " . $johns->name . "<br><br>";
  }
  $find_john = User::find_where( "name = ? LIMIT 1", array('John') );
  echo $find_john->name . "<br><br>";  
  
  //test count_all()
echo "<h3>Test count_all()</h3>";
  echo User::count_all();
  echo "<br><br>";

  //test update()
echo "<h3>Test update()</h3>";
  $john->name = "Jane";
  if($john->update()){
    echo $john->name . "<br><br>";
  } else {
    echo $john->name . "<br><br>";
  }
  
  //test delete()
echo "<h3>Test delete()</h3>";
  if($john->delete()){
    echo $john->name . " was deleted." . "<br><br>";
  } else {
    echo $john->name . " was not deleted." . "<br><br>";
  }

  //Clean Database
  $first_usr = array_shift( User::find_all() );
  $usr_id = (int) $first_usr->id;
  $last_usr = array_pop( User::find_all() );
  $last_usr_id = (int) $last_usr->id;

  for( $usr_id; $usr_id<=$last_usr_id; $usr_id++ ) {
    $u = User::find_by_id($usr_id);
    $u->delete();
  }

  if( !empty( User::find_all() ) ) {
    echo "Database Not Clean" . "<br><br>";
  }

  /**
   *Close Connection
   *@return Last Query
   */
  $db = MySQLDatabase::getInstance();
  if( isset($db) ) { $db->close_connection(); }
  echo var_dump($db);