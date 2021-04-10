<?php
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Methods: POST");
header("Access-Control-Max-Age: 3600");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");

include_once '../config/database.php';
include_once '../objects/users.php';



$database = new Database();
$db = $database->getConnection();

$user = new Users($db);

$data = json_decode(file_get_contents("php://input"));

$user->name = $data->name;
$user->surname = $data->surname;
$user->email = $data->email;
$user->password = $data->password;
$user->role = $data->role;

$passwordChanged = false;
if ($user->password !== "") {
    $passwordChanged = true;
}

if ($user->ifExists()) {
    $mess = '';
    if ($user->checkCredentials($mess, $passwordChanged)) {
        if ($user->update()) {
            http_response_code(200);
        } else {
            http_response_code(400);
            echo json_encode(array("error" => "Brak zmian"));
        }
    } else {
        echo json_encode(array("error" => $mess));
        http_response_code(400);
    }
} else {
    http_response_code(401);
    echo json_encode(array("error" => "Uzytkownik nie istnieje. Zaloguj sie ponownie!"));
}
