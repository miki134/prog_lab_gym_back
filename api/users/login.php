<?php
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Headers: access");
header("Access-Control-Allow-Methods: GET");
header("Access-Control-Allow-Credentials: true");
header('Content-Type: application/json');
header("Content-Type: application/json; charset=UTF-8");
header('Access-Control-Allow-Headers:', 'Content-Type, Origin, Authorization, Accept');

include_once '../config/database.php';
include_once '../objects/users.php';
include_once '../config/authenticate.php';

$database = new Database();
$db = $database->getConnection();

$data = json_decode(file_get_contents("php://input"));

$user = new Users($db);

$user->email = $data->email;

if ($user->getUser() && $user->password === md5($data->password)) {
    $token = new Authenticate($user->email);
    http_response_code(200);
    echo json_encode(array("token" => $token->getToken(), "data" => $user->role));
} else {
    header('HTTP/1.1 401 Unauthorized');
    echo json_encode(array("error" => "Błędne dane"));
}
