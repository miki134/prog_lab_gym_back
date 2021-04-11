<?php
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Methods: POST");
header("Access-Control-Max-Age: 3600");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");


include_once '../config/database.php';
include_once '../objects/equipment.php';
include_once '../config/authenticate.php';


$database = new Database();
$db = $database->getConnection();

$tab = new Equipment($db);
$data = json_decode(file_get_contents("php://input"));

$arr = array();
foreach (getallheaders() as $name => $value) {
    $arr[$name] = $value;
}

$token = $arr['Authorization'];
$auth = new Authenticate('');

if ($auth->checkToken($token, $message)) {
    if (
        !empty($data->name) &&
        !empty($data->length) &&
        !empty($data->height) &&
        !empty($data->width) &&
        !empty($data->weight) 
    ) {

        $tab->name = $data->name;
        $tab->length = $data->length;
        $tab->height = $data->height;
        $tab->width = $data->width;
        $tab->weight = $data->weight;
        $tab->description = $data->description;

        $mess = '';
        if ($tab->checkCredentials($mess)) {
            if ($tab->create()) {
                http_response_code(201);
                echo json_encode(array("data" => 'Dodano sprzet'));
            } else {
                echo json_encode(array("error" => "Przepraszamy dodawanie sprzetu jest niedostepne!. Prosze spróbowac pozniej"));
                http_response_code(400);
            }
        } else {
            // echo '$mess';
            echo json_encode(array("error" => $mess));
            http_response_code(400);
        }
    } else {
        http_response_code(400);
        echo json_encode(array("error" => "Niekompletne dane"));
    }
} else {
    echo json_encode(array("error" => $message));
}
