<?php
/*
	Verify device authorization.
	Save authorization for a new device.
	Update authorization for a re-authorized device.
	? Remove device authorization.
	? Save usage history for a device.
*/
session_start();

// Get credentials
require_once __DIR__.'/db.php';

// Workaround to getting AngularJS post data as $_REQUEST does not work
$params = json_decode(file_get_contents('php://input'),true);
	
$user_id = $params['user_id'];
$auth_code = $params['auth_code'];
$key_code = $params['key_code'];
$type = $params['type'];

// Check database for user_id and get the device_id
try {
	$dbh = new PDO("mysql:host=$mysql_hostname;dbname=$mysql_dbname", $mysql_username, $mysql_password);
	$dbh->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
	$stmt = $dbh->prepare("SELECT device_id FROM devices WHERE auth_code = :auth_code");
	$stmt->bindParam(':auth_code', $auth_code, PDO::PARAM_STR);
	
	$stmt->execute();
	
	$device_id = $stmt->fetchColumn();
	
	if ($device_id == false) {
		die('{"success": false, "message": "Invalid Device Code.  Restart."}');		
	}
	else {
		try {
			// User Not Found, Save User
			$dbh->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
			// In the future, gather Version from AppleTV device
			$stmt = $dbh->prepare("UPDATE devices SET user_id = :user_id, key_code = :key_code, status = 1
									WHERE auth_code = :auth_code");
			$stmt->bindParam(':user_id', $user_id, PDO::PARAM_INT);
			$stmt->bindParam(':auth_code', $auth_code, PDO::PARAM_STR);
			$stmt->bindParam(':key_code', $key_code, PDO::PARAM_STR);
			
			$stmt->execute();	
		}
		catch(Exception $e) {
			die('{"success": false, "message": "'.$e.'"}');
		}			
	}
} 
catch(Exception $e) {
	// DB Failed
	die('{"success": false, "message": "'.$e.'"}');
}

// Return success
die('{"success": true, "user_id": '.$user_id.', "auth_code": "'.$auth_code.'", "key_code": "'.$key_code.'", "device_id": '.$device_id.'}');	
?>