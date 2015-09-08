<?php
use mappeador\custom\orm\Mapper;

class User extends Mapper {

  protected static $table_name="users";
  public $id;
  public $name;


}