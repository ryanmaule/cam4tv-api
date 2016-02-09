<?php
/*
	Verify User Authentication
*/
session_start();
	
// Get credentials
require_once __DIR__.'/db.php';

// Workaround to getting AngularJS post data as $_REQUEST does not work
$params = json_decode(file_get_contents('php://input'),true);

$username = $params['username'];
$password = $params['password'];

try {
	$dbh = new PDO("mysql:host=$mysql_hostname;dbname=$mysql_dbname", $mysql_username, $mysql_password);
	$dbh->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
	$stmt = $dbh->prepare("SELECT user_id FROM dummy_users WHERE username = :username AND password_hash = md5(concat(password_salt,:password))");
	$stmt->bindParam(':username', $username, PDO::PARAM_STR);
	$stmt->bindParam(':password', $password, PDO::PARAM_STR, 40);
	
	$stmt->execute();
	
	$user_id = $stmt->fetchColumn();
	
	if ($user_id == false) {
		// Login Failed
		die('{"success": false, "error": "Login Failed, Try Again"}');
	}
	else {
		$_SESSION['user_id'] = $user_id;
	}
} 
catch(Exception $e) {
	// DB Failed
	die('{"success": false, "message": "'.$e.'"}');
}

// Return success
die('{"success": true, "user_id": '.$user_id.'}');	
?>