<?php
/*
Poll an auth_code to see if authorized
Example: AFSPSM
*/
session_start();

// Get credentials
require_once __DIR__.'/db.php';	

$auth_code = $_REQUEST['auth_code'];

try {
	// Get the new device_id	
	$dbh = new PDO("mysql:host=$mysql_hostname;dbname=$mysql_dbname", $mysql_username, $mysql_password);		
	$dbh->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
	$stmt = $dbh->prepare("SELECT status, key_code FROM devices WHERE auth_code = :auth_code");
	$stmt->bindParam(':auth_code', $auth_code, PDO::PARAM_STR);
	
	$stmt->execute();
		
	$status = $stmt->fetchColumn();	
	$key_code = $stmt->fetchColumn(1);
}
catch(Exception $e) {
	die('{"success": false, "message": "'.$e.'"}');
}

// Even though auth_code checks out, also do OAuth token check
$access_token = $key_code;

// Add an HTTP call to OAuth2 to verify the $key_code

die('{"success": '.(($status==1)?'true':'false').'}');
?>