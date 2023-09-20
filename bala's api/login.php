<?php

if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
  header("Access-Control-Allow-Origin: *");
  header("Access-Control-Allow-Headers: content-type, Access-Control-Allow-Headers, Authorization, X-Requested-With");
  http_response_code(200);
  die();
}


header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Methods: POST,OPTIONS");
header("Access-Control-Request-Method: POST,OPTIONS");
header('Access-Control-Allow-Credentials', 'true');
header("Access-Control-Max-Age: 3600");

include_once 'config/database.php';
include_once 'objects/user.php';
 
// get database connection
$database = new Database();
$db = $database->getConnection();
 
$user = new User($db);

$data = json_decode(file_get_contents("php://input"));
 
$user->username = $data->username;
$username_exists = $user->usernameExists(); 

include_once 'config/core.php';
include_once 'libs/php-jwt-master/src/BeforeValidException.php';
include_once 'libs/php-jwt-master/src/ExpiredException.php';
include_once 'libs/php-jwt-master/src/SignatureInvalidException.php';
include_once 'libs/php-jwt-master/src/JWT.php';
use \Firebase\JWT\JWT;

if ($username_exists && md5($data->password) == $user->password){
    
    $payloadData = array(
           "id" => $user->id,
           "username" => $user->username,
           "first_name" => $user->first_name,
       );

    $token = array(
       "iss" => $iss,
       "aud" => $aud,
       "iat" => $iat,
       "nbf" => $nbf,
       "data" => $payloadData
    );
 
    http_response_code(200);
 
    // generate jwt
    $jwt = JWT::encode($token, $key);
    echo json_encode(
            array(
                "token" => $jwt,
            )
        );
}
else{
    http_response_code(401); 
    echo json_encode(array("message" => "Login failed."));
}
?>