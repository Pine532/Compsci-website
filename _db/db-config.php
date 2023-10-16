<?php
function OpenCon()
{
	$dbhost = "localhost";
	$dbuser = "heygabo1_micds-login";
	$dbpass = "login@2023";
	$db     = "heygabo1_micds-login";

	$conn = new mysqli($dbhost, $dbuser, $dbpass,$db) or die("Connect failed: %s\n". $conn -> error);

	return $conn;
}

function CloseCon($conn)
{
	$conn -> close();
}
?>