#Mappeador by Anthony Gonzalez

Mappeador is a simple, flexible and easy way to perform php crud with MYSQL.

# Configuration
Go to config.php file:
```php
defined('DB_SERVER') ? null : define("DB_SERVER", "your_host");
defined('DB_USER')   ? null : define("DB_USER", "your_username");
defined('DB_PASS')   ? null : define("DB_PASS", "your_password");
defined('DB_NAME')   ? null : define("DB_NAME", "db_name");
```

# Usage
First you need to configure the initialize file depending on your file structure and require the file.
Once the database and the table is created you have to create classs that inherits from Mapper, public variables for each table field will be created automatically you can override if you want.

Example create and table called users with 2 fields id and name, then create the following class:
```php
use mappeador\Mapper;

class User extends Mapper {
  //if you want to override the table name
  protected static $table_name="users";


}
```

[NOTE: Careful when naming table names in database and you don't override the class property, the class name used to set that variable it won't get pluralize it only adds an "s" at the end of the name.]

Save function it will return true if saved:
```php
$john = new User();
$john->name = "John";
$john->save();
```

Save function at instantiation with array params:
```php
$john = new User(array( 'name' => 'John' ));
$john->save();
```

Find all function returns an object array:
```php
$users = User::find_all();
foreach($users as $user){
    echo $user->id . " | " . $user->name;
}
```

To find order by you just have to pass a parameter to find_all().
Example:
```php
$users = User::find_all("id DESC");
```

Find by id (the parameter has to be an integer):
```php
$user = User::find_by_id(1);
echo $user->name;
```

Find where (will return one object if LIMIT 1):
```php
$find_johns = User::find_where( "name = ?", array("John") );
$find_john = User::find_where( "name = ? LIMIT 1", array('John') );
```

Count all:
```php
User::count_all();
```

Update(first you need to find a record, it will return true if updated):
```php
$user = User::find_by_id(1);
$user->name = "John";
$user->update();
```
Delete(you need to find record first also, and returns true if deleted):
```php
$user = User::find_by_id(1);
$user->name = "John";
$user->delete();
```
[Note: after deleted it will still be in the object, it will only be deleted from database.]

Find by sql can be use directly with DatabaseObject class, Mapper or class that inherits from Mapper. If the sql doesn't need sanitazation just pass one parameter with sql otherwise pass 2 parameter the sql and an array with the bind params.

Example that doesn't need sanitazation(returns object array):
```php
use mappeador\DatabaseObject;

$sql = "SELECT * FROM users";
$result_set = DatabaseObject::find_by_sql($sql);
```
Example that needs sanitazation:
```php
use mappeador\DatabaseObject;

$param = array(1);
$sql = "SELECT * FROM users WHERE id=? LIMIT 1";
$result_set = DatabaseObject::find_by_sql($sql, $param);
```

Mysql query:
```php
use mappeador\MySQLDatabase;

$db = MySQLDatabase::getInstance();

$db->query($sql);
```
[Dangerous: don't use if you need sanitazation.]

Close connection:
```php
use mappeador\MySQLDatabase;

$db = MySQLDatabase::getInstance();
if( isset($db) ) { $db->close_connection(); }
```
