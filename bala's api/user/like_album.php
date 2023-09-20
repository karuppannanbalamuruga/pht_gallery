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
header("Access-Control-Max-Age: 3600");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");

include_once '../config/database.php';
include_once '../objects/user.php';
include_once '../objects/album.php';

$database = new Database();
$db = $database->getConnection();

$album = new Album($db);
$user = new User($db);

$data = json_decode(file_get_contents("php://input"));

if (empty($data->username) || empty($data->album_id)) {
	http_response_code(401);
	echo json_encode(array("message" => "Please provide username and album_id"));
}
else {
	$album->id = $data->album_id;
	$stmt = $album->readOne();
	$num = $stmt.rowCount();
	if ($num > 0) {
		$row = $stmt->fetch(PDO::FETCH_ASSOC);
		if ($row['created_by'] == $data->username) {
			$album->increment("like_count");
			$user->username = $data->username;
			$user->readOneByUsername();
			$user->like_album($album->id);
			http_response_code(200);
			echo json_encode(array("message" => "Success"));
			die();
		}
	}
	http_response_code(401);
	echo json_encode(array("message" => "Incorrect data"));
}