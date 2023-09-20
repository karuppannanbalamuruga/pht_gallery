<?php

// To handle preflight request
if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
  header("Access-Control-Allow-Origin: *");
  header("Access-Control-Allow-Headers: content-type, Access-Control-Allow-Headers, Authorization, X-Requested-With");
  http_response_code(200);
  die();
}

// required headers
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Headers: access");
header("Access-Control-Allow-Methods: GET");
header("Access-Control-Allow-Credentials: true");
header('Content-Type: application/json');

// include database and object files
include_once '../config/database.php';
include_once '../objects/album.php';
 
// instantiate database
$database = new Database();
$db = $database->getConnection();

$album = new Album($db);

$album->id = $_GET['id'];

$stmt = $album->readOne();
$num = $stmt->rowCount();

if ($num > 0) {
	$row = $stmt->fetch(PDO::FETCH_ASSOC);

	$album->name = $row['name'];
	$album->description = $row['description'];
	$album->created_by = $row['created_by'];
	$album->cover_photo = $row['cover_photo'];
	$album->created_at = $row['created_at'];
	$album->photo_count = $row['photo_count'];
	$album->likes_count = $row['likes_count'];

	http_response_code(200);
	echo json_encode(array('albums' => $album));
}
else {
	http_response_code(404);
	echo json_encode(array("message" => "Album not found"));
}