<?php

include_once '../config/database.php';
include_once '../objects/users.php';
include_once '../config/authenticate.php';


$database = new Database();
$db = $database->getConnection();
$users = new Users($db);

// $users->dropTable();
// $users->createTable();

// $data = json_decode(file_get_contents("php://input"));

// $sql = "INSERT INTO `users`(`name`, `surname`, `email`, `password`) VALUES (\'a\', \'a\', \'a\', \'0cc175b9c0f1b6a831c399e2697726611\')";


$arr = array(
    'name' => 'jasdasds',
    'email' => 'mail@asd.pl',
);
echo md5('a');

$token = new Authenticate($arr);
// echo $token->getToken();
$temp = $token->getToken();
$toke1 = 'Bearer ' . $temp;
$mess = '';
echo $token->checkToken($toke1, $mess);
echo $mess;
