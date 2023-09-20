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
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Methods: POST");
header("Access-Control-Max-Age: 3600");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");

include_once '../config/core.php';
include_once '../libs/php-jwt-master/src/BeforeValidException.php';
include_once '../libs/php-jwt-master/src/ExpiredException.php';
include_once '../libs/php-jwt-master/src/SignatureInvalidException.php';
include_once '../libs/php-jwt-master/src/JWT.php';
use \Firebase\JWT\JWT;

include_once '../config/database.php';
include_once '../objects/album.php';

$database = new Database();
$db = $database->getConnection();

$album = new Album($db);

$data = json_decode(file_get_contents("php://input"));
$data = $data->album;

$headers = getallheaders();

if (isset($headers['Authorization'])) {
	$authHeader = explode(" ", $headers['Authorization']);
	$jwt = $authHeader[1];
}
else $jwt = null;


if($jwt){
    try {
        $decoded = JWT::decode($jwt, $key, array('HS256'));

        if (empty($data->created_by)) {
			http_response_code(400);
			echo json_encode(array("message" => "Album not created. Please provide a username"));
		}
		else {
			if (isset($data->name)) $album->name = $data->name;
			else $album->name = "untitled_album";

			if (isset($data->description)) $album->description = $data->description;
			else $album->description = "No description";

			$album->created_by = $data->created_by;
			$album->created_at = date('Y-m-d H:i:s');

			$res = $album->create();
			if ($res) {
				$album->id = $res;
				/*if (isset($_FILES['cover_photo'])) {
					$target = 'C:/xampp/htdocs/api/data/albumCoverPhotos/' . $res . ".jpeg";

					if (move_uploaded_file($_FILES['cover_photo']['tmp_name'], $target)) {
						// $album->cover_photo = $target;
						$album->update('cover_photo', $target);
					}
				}*/
				http_response_code(200);
				echo json_encode(array(
					"message" => "Album successfully created",
					"token" => $jwt,
					"album" => array(
						"id" => $album->id,
						"name" => $album->name,
						"description" => $album->description,
						"created_by" => $album->created_by,
						"created_at" => $album->created_at
					)
				));
			}
			else {
				http_response_code(503);
				echo json_encode(array("message" => "Unable to create Album. Please provide a registered username."));
			}
		}

    }catch (Exception $e){
	    http_response_code(401);

	    echo json_encode(array(
	        "message" => "Access denied.",
	        "error" => $e->getMessage()
	    ));
	}
}
?>