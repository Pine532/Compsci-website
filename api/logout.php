<?php session_start();
header('Content-Type: application/json');

#set the response variable
$response = (object) [
    'status' => (object)[
		'code' 		=> 200,
		'message' 	=> 'OK',
	]
];

#remove all session variables
session_unset();

#destroy the session
session_destroy();

die(json_encode($response));
?>