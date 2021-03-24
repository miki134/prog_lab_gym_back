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
    
    // prepare users object
    $users = new User($db);
    
    // get id of users to be edited
    $data = json_decode(file_get_contents("php://input"));
    
    // set ID property of users to be edited
    $users->id = $data->id;
    
    $users->name = $data->name;
    // $users->price = $data->price;
    // $users->description = $data->description;
    // $users->category_id = $data->category_id;

    if($users->update()){
        http_response_code(200);
        echo json_encode(array("message" => "User was updated."));
    }
    else{
        http_response_code(503);
    
        echo json_encode(array("message" => "Unable to update user."));
    }
?>