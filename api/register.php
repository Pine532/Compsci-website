<?php
require '../_db/db-config.php';

#retrive parameters from request
$user  = strtolower(trim($_REQUEST["username"]));
$email = strtolower(trim($_REQUEST["email"]));
$pass  = $_REQUEST["password"];
$fname = ucwords(trim($_REQUEST["fname"]));
$lname = ucwords(trim($_REQUEST["lname"]));
// var_dump($_REQUEST);

#set the response variable
$response = (object) [
    'status' => (object)[
		'code' 		=> 400,
		'message' 	=> 'Bad Request',
	]
];
$error = [];
// var_dump($response);

// Validate inputs

## VALIDATE USER
#if something is missing, return error message
if (empty($user)){
	array_push($error,'Missing username');
}
#add a validation for username format (no spaces, only character and number or maybe a - or _ allowed only)


## VALIDATE EMAIL
if (empty($email)){
	array_push($error,'Missing email');
}
#I think this validates email format
elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
    array_push($error,'Invalid email format');
}

## VALIDATE PASSWORD
// $password_pattern = '/^(?=.*[!@#$%^&*-])(?=.*[A-Za-z])(?=.*\d)[A-Za-z\d!@#$%^&*-]{8,}$/';
$password_pattern = '/^(?=.*\d)(?=.*[a-z])(?=.*[A-Z])[a-zA-Z\d]{8,}$/';
if (empty($pass)){
    array_push($error,'Missing password');
}
elseif (!preg_match($password_pattern, $pass)) {
    array_push($error,'Password must be at least 8 characters and contain at least one uppercase letter, one lowercase letter, and one digit');
}


## VALIDATE FN/LN
if (empty($fname)) array_push($error,'Missing first name');
if (empty($lname)) array_push($error,'Missing last name');


while(true)
{
	if(!empty($error)) break;
	
	$conn = OpenCon();
	if (!$conn) {
		array_push($error,"Connection failed: " . mysqli_connect_error());
		break;
	}

	// here I am checking in username is taken
	$sql = "SELECT count(*) as total FROM Users WHERE username = '$user'";
	$result = mysqli_query($conn, $sql);
	if ($result->fetch_object()->total > 0) {
		array_push($error,"Username already taken.");
		break;
	}

	
	// Insert user into database
	$salt = md5(uniqid('', true));
	$sql = "INSERT INTO Users (username, firstname, lastname, email, userps, creationdate, salt) VALUES ('$user', '$fname', '$lname', '$email', SHA1(CONCAT('$salt','$pass')), CURRENT_DATE, '$salt')";
	if (mysqli_query($conn, $sql)) {
		$response->status->code = 200;
		$response->status->message = 'Registration success';
	} else {
		array_push($error,"There is an error creating your account, try it later or contact the administrator.");
		array_push($error,"Error: " . $sql);
		array_push($error,"Error: " . mysqli_error($conn));
		break;
	}
	
	#stopper for while
	break;
}

//close connection if was created
if(!empty($conn)) CloseCon($conn);

// this checks if there has been an error, if the error variable is empty there are no errors, if there is an error, it echos the error and exits
if(!empty($error)){
	$response->errors = $error;
}

die(json_encode($response));
?>