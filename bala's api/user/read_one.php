<?php

// To handle preflight request
if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
  header("Access-Control-Allow-Origin: *");
  header("Access-Control-Allow-Headers: content-type, Access-Control-Allow-Headers, Authorization, X-Requested-With");
  http_response_code(200);
  die();
}

header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Headers: access");
header("Access-Control-Allow-Methods: GET");
header("Access-Control-Allow-Credentials: true");
header('Content-Type: application/json');

include_once '../config/database.php';
include_once '../objects/user.php';

$database = new Database();
$db = $database->getConnection();

$user = new User($db);

if (isset($_GET['id'])) {
	$user->id = $_GET['id'];
	$user->readOneById();
}
else if (isset($_GET['username'])) {
	$user->username = $_GET['username'];
	$user->readOneByUsername();
}

if ($user->username != null && $user->id != null) {
	$user_arr = array(
		"id" => $user->id,
		"username" => $user->username,
		"first_name" => $user->first_name,
		"last_name" => $user->last_name,
		"email" => $user->email,
		"gender" => $user->gender,
		"profile_picture" => $user->profile_picture
	);


	http_response_code(200);
	echo json_encode(array('users'=> $user_arr));
}
else {
	http_response_code(404);
	echo json_encode(array("message" => "User does not exist"));
}