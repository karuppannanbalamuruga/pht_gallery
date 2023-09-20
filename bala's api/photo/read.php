<?php

header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Headers: content-type, Access-Control-Allow-Headers, Authorization, X-Requested-With");

include_once '../config/database.php';
include_once '../objects/photo.php';
 
$database = new Database();
$db = $database->getConnection();

$photo = new Photo($db);

$photo->id = $_GET['id'];
$stmt = $photo->read();
$num = $stmt->rowCount();


if ($num > 0) {
	$row = $stmt->fetch(PDO::FETCH_ASSOC);

	$photo->name = $row['name'];
	$photo->description = $row['description'];
	$photo->image = $row['image'];
	$photo->album_id = $row['album_id'];
	$photo->likes_count = $row['likes_count'];
	$photo->created_at = $row['created_at'];

	http_response_code(200);
	echo json_encode($photo);
}
else {
	http_response_code(404);
	echo json_encode(array("message" => "Photo not found"));
}