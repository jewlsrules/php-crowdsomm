<?php
include_once __DIR__ . '/../models/dish.php';
header('Content-Type: application/json');

if ($_REQUEST['action'] == 'index') {
  echo json_encode(Dishes::all());
} elseif ($_REQUEST['action'] == 'post') {
  $request_body = file_get_contents('php://input');
  $body_object = json_decode($request_body);
  $new_dish = new Dish(null, $body_object->dish_name, $body_object->restaurant_id);
  $all_dishes = Dishes::create($new_dish);
  echo json_encode($all_dishes);
} elseif ($_REQUEST['action'] == 'update'){
  $request_body = file_get_contents('php://input');
  $body_object = json_decode($request_body);
  $updated_dish = new Dish($_REQUEST['id'], $body_object->dish_name, $body_object->restaurant_id);
  $all_dishes = Dishes::update($updated_dish);
  echo json_encode($all_dishes);
} elseif ($_REQUEST['action'] == 'delete') {
  $all_dishes = Dishes::delete($_REQUEST['id']);
  echo json_encode($all_dishes);
} elseif ($_REQUEST['action'] == 'find') {
  $all_dishes = Dishes::findById($_REQUEST['id']);
  echo json_encode($all_reviews);
};

?>
