<?php
    // required headers
    header("Access-Control-Allow-Origin: *");
    header("Content-Type: application/json; charset=UTF-8");
    header("Access-Control-Allow-Methods: POST");
    header("Access-Control-Max-Age: 3600");
    header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");
    
    
    include_once '../config/database.php';
    include_once '../objects/users.php';
    
    $database = new Database();
    $db = $database->getConnection();
    
    $users = new Users($db);
    
    $data = json_decode(file_get_contents("php://input"));
    
    
    if(!empty($data->surname) && !empty($data->name))
    {
        
        $users->surname = $data->surname;
        $users->name = $data->name;
        
        // $product->price = $data->price;
        // $product->description = $data->description;
        // $product->category_id = $data->category_id;
        // $product->created = date('Y-m-d H:i:s');
        
        if($users->create()){
            
            // set response code - 201 created
            http_response_code(201);
            
            echo json_encode(array("message" => "User was created."));
        }
        else{
            //  echo json_encode(array("name" => $users->name, "surname" => $users->surname));
            
            // set response code - 503 service unavailable
            //http_response_code(503);
    
            echo json_encode(array("message" => "Unable to add user."));
        }
    }
    else{
        // set response code - 400 bad request
        http_response_code(400);
    
        // tell the user
        echo json_encode(array("message" => "Unable to add user. Data is incomplete."));
    }
?>