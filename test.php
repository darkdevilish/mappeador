<?php
use mappeador\MySQLDatabase;

require_once __DIR__ . '/initialize.php';

  //test save()
  $john = new User();
  $john->name = "John";
  
  if($john->save()){
    echo $john->name . " was created.";
    echo "<br><br>";
  } else {
    echo $john->name . " was not created.";
    echo "<br><br>";
  }

  //creates some users
  for($i=1; $i<4; $i++){
    $u = new User();
    $u->name = "John"."$i";
    $u->save();
  }
  //test find_all()
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
  $john = array_shift( User::find_all() );
  $find_john = User::find_by_id( $john->id );
  echo $john->name . "<br><br>";
  
  //test count_all()
  echo User::count_all();
  echo "<br><br>";

  //test update()
  $john->name = "Jane";
  if($john->update()){
    echo $john->name . "<br><br>";
  } else {
    echo $john->name . "<br><br>";
  }
  
  //test delete()
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