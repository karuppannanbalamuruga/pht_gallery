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

if (empty($data->album_id) || !isset($_FILES['image'])) {
	http_response_code(400);
	echo json_encode(array("message" => "Photo not created. Please provide required details."));
}
else {
	if (isset($data->name)) $photo->name = $data->name;
	else $photo->name = "untitled_photo";

	if (isset($data->description)) $photo->description = $data->description;
	else $photo->description = "No description";

	$photo->created_at = date('Y-m-d H:i:s');
	$photo->album_id = $data->album_id;

	$res = $photo->create();
	if ($res) {
		$photo->id = $res;

		$base = "C:/xampp/htdocs/api/data/photos/{$photo->album_id}/";
		$target = "{$res}.jpeg";
		if (!file_exists($base)) {
		    mkdir($base, 0777, true);
		}
		$base .= $target;
		if (move_uploaded_file($_FILES['image']['tmp_name'], $base)) {
			// $photo->image = $target;
			$photo->update('image', $base);
		}

		// update photo_count of album
		$album->id = $photo->album_id;
		$album->increment("photo_count");

		http_response_code(200);
		echo json_encode(array(
			"message" => "photo successfully created",
			"id" => $photo->id
		));
	}
	else {
		http_response_code(503);
		echo json_encode(array("message" => "Unable to create photo. Please provide a existing album id."));
	}

}