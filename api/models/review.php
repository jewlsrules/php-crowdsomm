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

class AverageStars {
  public $dish_name;
  public $dish_id;
  public $stars;
  public function __construct($dish_name, $dish_id, $stars){
    $this->dish_name = $dish_name;
    $this->dish_id = $dish_id;
    $this->stars = $stars;
  }
}

// this is the "factory" that all of the functions will go through
class Reviews {

  //get all reviews function
  static function all(){
    $reviews = array();

    $results = pg_query("SELECT * FROM reviews"); //getting the information from the database for all applications
    $row_object = pg_fetch_object($results);

    while($row_object){ //while there's a result object...
      $new_review = new Review(
        intval($row_object->id),
        $row_object->user_id,
        $row_object->restaurant_id,
        $row_object->dish_name,
        intval($row_object->dish_id),
        intval($row_object->stars),
        $row_object->review_text
      );
      $reviews[] = $new_review;
      $row_object = pg_fetch_object($results);
    }
    return $reviews;
  }

  static function findById($id){
    $reviews = array();

    $query = "SELECT * FROM reviews WHERE restaurant_id = $1";
    $query_params = array($id);

    $results = pg_query_params($query, $query_params);
    $row_object = pg_fetch_object($results);

    while($row_object){ //while there's a result object...
      $new_review = new Review(
        intval($row_object->id),
        $row_object->user_id,
        $row_object->restaurant_id,
        $row_object->dish_name,
        intval($row_object->dish_id),
        intval($row_object->stars),
        $row_object->review_text
      );
      $reviews[] = $new_review;
      $row_object = pg_fetch_object($results);
    }
    return $reviews;
  }

  static function findByDishId($id){
    $reviews = array();

    $query = "SELECT * FROM reviews WHERE dish_id = $1";
    $query_params = array($id);

    $results = pg_query_params($query, $query_params);
    $row_object = pg_fetch_object($results);

    while($row_object){ //while there's a result object...
      $new_review = new Review(
        intval($row_object->id),
        $row_object->user_id,
        $row_object->restaurant_id,
        $row_object->dish_name,
        intval($row_object->dish_id),
        intval($row_object->stars),
        $row_object->review_text
      );
      $reviews[] = $new_review;
      $row_object = pg_fetch_object($results);
    }
    return $reviews;
  }

  static function getAverageStarsByDish($id){

    $query = "SELECT dish_name, dish_id, AVG(stars) AS stars FROM reviews WHERE dish_id = $1 GROUP BY dish_id, dish_name SORT BY stars DESC";
    $query_params = array($id);

    $results = pg_query_params($query, $query_params);
    $row_object = pg_fetch_object($results);

    $new_avg = new AverageStars(
      $row_object->dish_name,
      intval($row_object->dish_id),
      intval($row_object->stars)
    );

    return $new_avg;
  }

  static function create($review){
    $query = "INSERT INTO reviews (user_id, restaurant_id, dish_name, dish_id, stars, review_text) VALUES ($1, $2, $3, $4, $5, $6)";
    $query_params = array($review->user_id, $review->restaurant_id, $review->dish_name, $review->dish_id, $review->stars, $review->review_text);
    pg_query_params($query, $query_params);
    return self::all();
  }

  static function update($updated_review){
    $query = "UPDATE reviews SET user_id = $1, restaurant_id = $2, dish_name = $3, dish_id = $4, stars = $5, review_text = $6 WHERE id = $7";
    $query_params = array($updated_review->user_id, $updated_review->restaurant_id, $updated_review->dish_name, $updated_review->dish_id, $updated_review->stars, $updated_review->review_text, $updated_review->id);
    $result = pg_query_params($query, $query_params);
    return self::all();
  }

  static function delete($id){
    $query = "DELETE FROM reviews WHERE id = $1";
    $query_params = array($id);
    $result = pg_query_params($query, $query_params);
    return self::all();
  }
}

?>
