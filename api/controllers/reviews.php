<?php
include_once __DIR__ . '/../models/review.php';
header('Content-Type: application/json');

if ($_REQUEST['action'] == 'index') {
  echo json_encode(Reviews::all());
} elseif ($_REQUEST['action'] == 'post') {
  $request_body = file_get_contents('php://input');
  $body_object = json_decode($request_body);
  $new_review = new Review(null, $body_object->user_id, $body_object->restaurant_id, $body_object->dish_name, $body_object->dish_id, $body_object->stars, $body_object->review_text);
  $all_reviews = Reviews::create($new_review);
  echo json_encode($all_reviews);
} elseif ($_REQUEST['action'] == 'update'){
  $request_body = file_get_contents('php://input');
  $body_object = json_decode($request_body);
  $updated_review = new Review($_REQUEST['id'], $body_object->user_id, $body_object->restaurant_id, $body_object->dish_name, $body_object->dish_id, $body_object->stars, $body_object->review_text);
  $all_reviews = Reviews::update($updated_review);
  echo json_encode($all_reviews);
} elseif ($_REQUEST['action'] == 'delete') {
  $all_reviews = Reviews::delete($_REQUEST['id']);
  echo json_encode($all_reviews);
} elseif ($_REQUEST['action'] == 'find') {
  $all_reviews = Reviews::findById($_REQUEST['id']);
  echo json_encode($all_reviews);
};

?>
