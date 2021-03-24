<?php
    // required headers
    header("Access-Control-Allow-Origin: *");
    header("Access-Control-Allow-Headers: access");
    header("Access-Control-Allow-Methods: GET");
    header("Access-Control-Allow-Credentials: true");
    header('Content-Type: application/json');
    
    include_once '../config/database.php';
    include_once '../objects/users.php';
    
    $database = new Database();
    $db = $database->getConnection();
    
    $user = new Users($db);
    
    $data = json_decode(file_get_contents("php://input"));

    $user->id = isset($data->user_id) ? $data->user_id : die();
    
     $user->readOne();
   // echo json_encode(array("name" => $data->user_id, "surname" => "" ));

    if($user->name != null){
        // create array
        $user_arr = array(
            "id" =>  $user->id,
            "name" => $user->name,
            "surnname" => $user->surname,
        );
    
        http_response_code(200);
    
        echo json_encode($user_arr);
        
    }
    
    // else{
    //     // set response code - 404 Not found
    //     http_response_code(404);
    
    //     echo json_encode(array("message" => "user does not exist."));
    // }
?>