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
header("Access-Control-Allow-Methods: POST");
header("Access-Control-Request-Method: POST");
header('Access-Control-Allow-Credentials', 'true');
header("Access-Control-Max-Age: 3600");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");


include_once '../config/database.php';
include_once '../objects/user.php';

$database = new Database();
$db = $database->getConnection();

$user = new User($db);

// get posted data
// $data = json_encode($_POST['user']);
$data = json_decode(file_get_contents("php://input"));
$data = $data->user;
// if(isset($_FILES['profile_picture'])) {
// 	foreach ($_FILES as $row) {
// 		foreach ($row as $value => $val) {
// 			echo $value.'<br>'.$val.'<br>';
// 		}
// 	}
// 	// echo $_FILES['photo']['name'];
// }
// else echo false;	
// echo file_get_contents("php://input");
// echo $data->username;


if (empty($data->username) || empty($data->first_name) || empty($data->email) || empty($data->gender) || empty($data->password)) {
	// set http response code 400 - bad request
	http_response_code(400);

	echo json_encode(array("message" => "User is not registered. Data incomplete"));
}
else {
	if (isset($_FILES['profile_picture'])) {
		$target = 'C:/xampp/htdocs/api/data/userProfilePhotos/' . $data->username . '.jpeg';
		// $target .= basename( $_FILES['profile_picture']['name']);
		if (move_uploaded_file($_FILES['profile_picture']['tmp_name'], $target)) {
			$user->profile_picture = $target;
		}
	}
	else $user->profile_picture = null;

	if (isset($data->last_name)) $user->last_name = $data->last_name;
	else $user->last_name = "";

	$user->username = $data->username;
	$user->first_name = $data->first_name;
	$user->email = $data->email;
	$user->gender = $data->gender;
	$user->password = md5($data->password);
	$user->created_at = date('Y-m-d H:i:s');

	$res = $user->create();
	if ($res) {
		// created
		$user->id = $res;
		$ret = array();
		$ret['users'] = array(
			"id"=> $user->id,
			"username"=> $user->username,
			"first_name"=> $user->first_name,
			"last_name"=> $user->last_name,
			"email"=> $user->email,
			"gender"=> $user->gender,
			"profile_picture"=> $user->profile_picture
		);

		http_response_code(201);
		echo json_encode($ret);
	}
	else {
		// service unavailable
		http_response_code(503);
		echo json_encode(array("message" => "Unable to create user"));
	}
}