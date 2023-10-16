<?php session_start();
require '../_db/db-config.php';
header('Content-Type: application/json');

#set the response variable
$response = (object) [
    'status' => (object)[
		'code' 		=> 401,
		'message' 	=> 'Unauthorized',
	]
];

if(!empty($_SESSION)){
    $response->status->code = 200;
    $response->status->message = 'OK';
    
    #get session data
    $response->data->username  = $_SESSION['username'] ;
    $response->data->firstname = $_SESSION['firstname'];
    $response->data->lastname  = $_SESSION['lastname'] ;
    $response->data->email     = $_SESSION['email']    ;
} else {
    $response->errors = ['No session available.'];
}

die(json_encode($response));
?>