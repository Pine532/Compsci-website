<?php session_start();
require '../_db/db-config.php';
header('Content-Type: application/json');
#retrive parameters from request
$username = strtolower(trim($_POST['username']));
$password = $_POST['password'];
// var_dump($_REQUEST);

#set the response variable
$response = (object) [
    'status' => (object)[
		'code' 		=> 401,
		'message' 	=> 'Unauthorized',
	]
];

#validate the inputs

#if something is wrong, return error message
if (empty($username)) die('missing username');
if (empty($password)) die('missing password');

$conn = OpenCon();

$sql = "SELECT username, firstname, lastname, email FROM Users WHERE username = '$username' and userps = SHA1(CONCAT(salt,'$password'))";
$stmt = $conn->prepare($sql);

if ($stmt) {
    $stmt->bind_param("ss", $username, $password);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows == 1) {
        $response->status->code = 200;
        $response->status->message = 'MATCHED';
        $response->data = $result->fetch_object();
        
        #set session data
        $_SESSION['username']  = $response->data->username ;
        $_SESSION['firstname'] = $response->data->firstname;
        $_SESSION['lastname']  = $response->data->lastname ;
        $_SESSION['email']     = $response->data->email    ;
    } else {
        $response->errors = ['Wrong user or password.'];
    }

    $stmt->close();
} else {
    $response->errors = ['Error preparing statement.'];
}

die(json_encode($response));
?>