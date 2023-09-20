<?php

// To handle preflight request
if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
  header("Access-Control-Allow-Origin: *");
  header("Access-Control-Allow-Headers: content-type, Access-Control-Allow-Headers, Authorization, X-Requested-With");
  http_response_code(200);
  die();
}

header("Access-Control-Allow-Origin: *");
header("Content-Type: multipart/form-data; charset=UTF-8");
header("Access-Control-Allow-Methods: POST");
header("Access-Control-Max-Age: 3600");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");

include_once '../config/database.php';
include_once '../objects/user.php';
 
$database = new Database();
$db = $database->getConnection();

$user = new User($db);
$data = json_decode(file_get_contents("php://input"));
/*$data = json_encode($_POST);
$data = json_decode($data);*/
$user->id = $data->id;

$res = true;
foreach ($data as $key => $value) {
	if ($value) {
		if ($key == "password") $value = md5($value);
		$res = $res & $user->update($key, $value); 
	}
}

/*
if (isset($_FILES['profile_picture'])) {
	$target = 'C:/xampp/htdocs/api/data/userProfilePhotos/' . $user->username . '.jpeg';
	// $target .= basename( $_FILES['profile_picture']['name']);
	// echo $target;
	if (move_uploaded_file($_FILES['profile_picture']['tmp_name'], $target)) {
		$res = $res & $user->update("profile_picture", $target);
	}
} */


if ($res) {
	http_response_code(200);
	echo json_encode(array("message" => "Update successful"));
}
else {
	http_response_code(503);
	echo json_encode(array("message" => "Update unsuccessful"));
}