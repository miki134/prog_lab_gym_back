<?php
    header("Access-Control-Allow-Origin: *");
    header("Content-Type: application/json; charset=UTF-8");

    include_once("../objects/users.php");
    include_once("../config/database.php");

    $db = new Database();
    $conn = $db->getConnection();

    $users = new Users($conn);

    $stmt =  $users->getUsers();
    $num = $stmt->rowCount();

    if($num>0){
  
        $products_arr=array();
        $products_arr["records"]=array();
      
        while ($row = $stmt->fetch(PDO::FETCH_ASSOC)){
            extract($row);
      
            $user=array(
                "id" => $id,
                "name" => $name
            );
      
            array_push($products_arr["records"], $user);
        }
      
        // set response code - 200 OK
        http_response_code(200);
      
        echo json_encode($products_arr);
    }