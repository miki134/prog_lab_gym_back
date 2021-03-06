<?php
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Methods: POST");
header("Access-Control-Max-Age: 3600");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");


include_once '../config/database.php';
include_once '../objects/users.php';
include_once '../config/authenticate.php';

$database = new Database();
$db = $database->getConnection();

$data = json_decode(file_get_contents("php://input"));

$arr = array();
foreach (getallheaders() as $name => $value) {
    $arr[$name] = $value;
}

$token = $arr['Authorization'];
// echo json_encode(array("a" => $token));

$auth = new Authenticate('');
if ($auth->checkToken($token, $message)) {
    $user = new Users($db);
    $user->email = $token->data;

    if ($user->ifExists()) {
        if ($user->getRole() === 'admin') {
            $tok = new Authenticate($user->email);
            echo json_encode(array("token" => $tok->getToken(), "data" =>  $user->getUsers()));
        } else {
            http_response_code(404);
            echo json_encode(array("error" => "Brak wystarczajacych uprawnien. Zaloguj sie ponownie!"));
        }
    } else {
        echo json_encode(array("error" => "Uzytkownik nie istnieje. Zaloguj sie ponownie!"));
        http_response_code(404);
    }
} else {
    echo json_encode(array("error" => $message));
}
