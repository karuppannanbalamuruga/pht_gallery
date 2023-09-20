<?php

// To handle preflight request
if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
  header("Access-Control-Allow-Origin: *");
  header("Access-Control-Allow-Headers: content-type, Access-Control-Allow-Headers, Authorization, X-Requested-With");
  http_response_code(200);
  die();
}

header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Headers: content-type, Access-Control-Allow-Headers, Authorization, X-Requested-With");

include_once '../config/database.php';
include_once '../objects/user.php';
 
$database = new Database();
$db = $database->getConnection();

$user = new User($db);

// query user
$stmt = $user->read();
$num = $stmt->rowCount();

if ($num > 0) {

	$user_arr = array();
	$user_arr['users'] = array();

	while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
		extract($row);
		
		$single_user = array(
			"id" => $id,
			"username" => $username,
			"first_name" => $first_name,
			"last_name" => $last_name,
			"email" => $email,
			"gender" => $gender,
			"profile_picture" => $profile_picture
		);

		array_push($user_arr['users'], $single_user);
	}

	http_response_code(200);
	echo json_encode($user_arr);
}
else {
	http_response_code(404);
	echo json_encode(
		array("message" => "No users found")
	);
}
