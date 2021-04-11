<?php
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Methods: POST");
header("Access-Control-Max-Age: 3600");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");


include_once '../config/database.php';
include_once '../objects/diets.php';
include_once '../config/authenticate.php';
include_once '../objects/users.php';


$database = new Database();
$db = $database->getConnection();

$tab = new Diets($db);
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
        !empty($data->quantityOfProducts) &&
        !empty($data->numberOfMealsPerDay) &&
        !empty($data->meat)
    ) {

        $tab->name = $data->name;
        $tab->quantityOfProducts = $data->quantityOfProducts;
        $tab->numberOfMealsPerDay = $data->numberOfMealsPerDay;
        $tab->meat = $data->meat;
        $tab->description = $data->description;

        $mess = '';
        if ($tab->checkCredentials($mess)) {
            $user = new Users($db);
            $user->email = $token->data;

            if ($user->getRole() !== 'admin') {
                http_response_code(404);
                echo json_encode(array("error" => "Brak wystarczajacych uprawnien. Zaloguj sie ponownie!"));
            }

            if ($tab->create()) {
                http_response_code(201);
                echo json_encode(array("data" => 'Dodano diete'));
            } else {
                echo json_encode(array("error" => "Przepraszamy dodawanie diet jest niedostepne!. Prosze spróbowac pozniej"));
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
