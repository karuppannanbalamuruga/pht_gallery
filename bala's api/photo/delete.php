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
include_once '../objects/photo.php';
include_once '../objects/album.php';

$database = new Database();
$db = $database->getConnection();

$photo = new Photo($db);
$album = new Album($db);

$data = json_decode(file_get_contents("php://input"));
$data = $data->photo;

if (isset($data->id)) {
	$photo->id = $data->id;
	$photo->getAlbumId();
	if ($photo->delete()) {

		// decrease photo_count in album
		$album->id = $photo->album_id;
		$album->decrement("photo_count");

		http_response_code(200);
		echo json_encode(array("message" => "Photo Deleted Successfully."));
	}
	else {
		http_response_code(503);
		echo json_encode(array("message" => "Unable to delete photo"));
	}
}
else {
	http_response_code(400);
	echo json_encode(array("message" => "Please provide a photo id"));
}