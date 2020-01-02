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
    $dbconn = pg_connect("host=localhost dbname=app_tracker");
}

class Review {
  public $id;
  public $user_id;
  public $restaurant_id;
  public $dish_name;
  public $dish_id;
  public $stars;
  public $review_text;
  public function __construct($id, $user_id, $restaurant_id, $dish_name, $dish_id, $stars, $review_text){
    $this->id = $id;
    $this->user_id = $user_id;
    $this->restaurant_id = $restaurant_id;
    $this->dish_name = $dish_name;
    $this->dish_id = $dish_id;
    $this->stars = $stars;
    $this->review_text = $review_text;
  }
}

?>
