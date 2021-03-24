<?php

header("Access-Control-Allow-Origin: *");
$rest_json = file_get_contents("php://input");
$_POST = json_decode($rest_json, true);

echo json_encode(array("message" => "asdasd", "fname" => $_POST["fname"]));

// if (empty($_POST['firstname']) && empty($_POST['email'])) die();
// if ($_POST)
// 	{

// 	// set response code - 200 OK

// 	http_response_code(200);
// 	$subject = $_POST['firstname'];
// 	$to = "me@malith.pro";
// 	$from = $_POST['email'];

// 	// data

// 	$msg = $_POST['number'] . $_POST['message'];

// 	// Headers

// 	$headers = "MIME-Version: 1.0\r\n";
// 	$headers.= "Content-type: text/html; charset=UTF-8\r\n";
// 	$headers.= "From: <" . $from . ">";
// 	mail($to, $subject, $msg, $headers);

// 	// echo json_encode( $_POST );

// 	echo json_encode(array(
// 		"sent" => true
// 	));
// 	}
//   else
// 	{

// 	// tell the user about error

// 	echo json_encode(["sent" => false, "message" => "Something went wrong"]);
// 	}


// if (isset($_POST['order_id']) && $_POST['order_id']!="") {
//  $order_id = $_POST['order_id'];
//  $url = "http://localhost::3000/".$order_id;
 
//  $client = curl_init($url);
//  curl_setopt($client,CURLOPT_RETURNTRANSFER,true);
//  $response = curl_exec($client);
 
//  $result = json_decode($response);
//  echo "kurwa";
//  print_r($_POST['order_id']);
// //  echo "<table>";
// //  echo "<tr><td>Order ID:</td><td>$result->order_id</td></tr>";
// //  echo "<tr><td>Amount:</td><td>$result->amount</td></tr>";
// //  echo "<tr><td>Response Code:</td><td>$result->response_code</td></tr>";
// //  echo "<tr><td>Response Desc:</td><td>$result->response_desc</td></tr>";
// //  echo "</table>";
// }