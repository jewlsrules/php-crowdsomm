<?php

$dbconn = null;
if(getenv('DATABASE_URL')){
    $connectionConfig = parse_url(getenv('DATABASE_URL'));
    $host = $connectionConfig['host'];
    $user = $connectionConfig['user'];
    $password = $connectionConfig['pass'];
    $port = $connectionConfig['port'];
    $dbname = trim($connectionConfig['path'],'/');
    $dbconn = pg_connect(
        "host=".$host." ".
        "user=".$user." ".
        "password=".$password." ".
        "port=".$port." ".
        "dbname=".$dbname
    );
} else {
    $dbconn = pg_connect("host=localhost dbname=crowdsomm");
}

class Dish {
  public $id;
  public $dish_name;
  public $restaurant_id;
  public function __construct($id, $dish_name, $restaurant_id){
    $this->id = $id;
    $this->dish_name = $dish_name;
    $this->restaurant_id = $restaurant_id;
  }
}

// this is the "factory" that all of the functions will go through
class Dishes {
  //get all dishes function
  static function all(){
    $dishes = array();

    $results = pg_query("SELECT * FROM dishes"); //getting the information from the database for all applications
    $row_object = pg_fetch_object($results);

    while($row_object){ //while there's a result object...
      $new_dish = new Dish(
        intval($row_object->id),
        $row_object->dish_name,
        $row_object->restaurant_id,
      );
      $dishes[] = $new_dish;
      $row_object = pg_fetch_object($results);
    }
    return $dishes;
  }

  static function findByRestId($id){
    $dishes = array();

    $query = "SELECT * FROM dishes WHERE restaurant_id = $1";
    $query_params = array($id);

    $results = pg_query_params($query, $query_params);
    $row_object = pg_fetch_object($results);

    while($row_object){ //while there's a result object...
      $new_dish = new Dish(
        intval($row_object->id),
        $row_object->dish_name,
        $row_object->restaurant_id,
      );
      $dishes[] = $new_dish;
      $row_object = pg_fetch_object($results);
    }
    return $dishes;
  }

  static function create($dish){
    $query = "INSERT INTO dishes (dish_name, restaurant_id) VALUES ($1, $2)";
    $query_params = array($dish->dish_name, $dish->restaurant_id);
    pg_query_params($query, $query_params);
    return self::all();
  }

  static function update($updated_dish){
    $query = "UPDATE dishes SET dish_name = $1, restaurant_id = $2";
    $query_params = array($updated_review->dish_name, $updated_review->restaurant_id);
    $result = pg_query_params($query, $query_params);
    return self::all();
  }

  static function delete($id){
    $query = "DELETE FROM dishes WHERE id = $1";
    $query_params = array($id);
    $result = pg_query_params($query, $query_params);
    return self::all();
  }
}

?>
