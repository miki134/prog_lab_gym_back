<?php
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Methods: POST");
header("Access-Control-Max-Age: 3600");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");


include_once '../config/database.php';
include_once '../objects/workouts.php';
include_once '../config/authenticate.php';


$database = new Database();
$db = $database->getConnection();

$tab = new Workouts($db);
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
        !empty($data->lengthOfTime) &&
        !empty($data->quantityOfExercises) &&
        !empty($data->difficulty)
    ) {

        $tab->name = $data->name;
        $tab->lengthOfTime = $data->lengthOfTime;
        $tab->quantityOfExercises = $data->quantityOfExercises;
        $tab->difficulty = $data->difficulty;
        $tab->description = $data->description;

        $mess = '';
        if ($tab->checkCredentials($mess)) {
            $tab->difficulty = md5($tab->difficulty);
            if ($tab->create()) {
                http_response_code(201);
                echo json_encode(array("data" => 'Dodano trening'));
            } else {
                echo json_encode(array("error" => "Przepraszamy dodawanie treningow jest niedostepne!. Prosze spróbowac pozniej"));
            }
        } else {
            http_response_code(400);
            echo json_encode(array("error" => $mess));
        }
    } else {
        http_response_code(400);
        echo json_encode(array("error" => $data));
    }
} else {
    echo json_encode(array("error" => $message));
}
