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
    
    $users = new Users($db);
    $users->createTable();
    $data = json_decode(file_get_contents("php://input"));
    
    
    if( !empty($data->name) &&
        !empty($data->surname) && 
        !empty($data->email) && 
        !empty($data->password))
    {
        
        $users->name = $data->name;
        $users->surname = $data->surname;
        $users->email = $data->email;
        $users->password = md5($data->password);
        $users->role = 'client';

        if($users->create()){            
            
            $token = new Authenticate($users->name);
            
            http_response_code(201);
            echo json_encode(array("token" => $token->getToken()));
            
            // echo json_encode(array("message" => "User was created."));
        }
        else{
            echo json_encode(array("message" => "Unable to add user."));
        }
    }
    else{
        http_response_code(400);
        echo json_encode(array("message" => "Unable to add user. Data is incomplete."));
    }
?>