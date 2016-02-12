<?php
/*
Generate an auth_code for a device
If a uuid can be passed by AppleTV it should, but for now ever call here will generate a new auth_code in devices
*/
session_start();

// Generate an auth_code
$auth_code = "";
$string = "ABCDEFGHIJKLMNOPQRSTUVWXYZ"; 
for($i=0;$i<6;$i++){ 
    $pos = rand(0,25); 
    $auth_code .= $string{$pos}; 
}

// Get credentials
require_once __DIR__.'/db.php';	

try {
	// Update the auth_code with user and key values
	$dbh = new PDO("mysql:host=$mysql_hostname;dbname=$mysql_dbname", $mysql_username, $mysql_password);
	$dbh->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
	// In the future, gather Version from AppleTV device
	$stmt = $dbh->prepare("INSERT INTO devices (auth_code, auth_code_expiry, device_type, version)
							VALUES (:auth_code, DATE_ADD(current_timestamp, INTERVAL 1 DAY), 'AppleTV', 1)");
	$stmt->bindParam(':auth_code', $auth_code, PDO::PARAM_STR);
	
	$stmt->execute();
	
	// Get the new device_id			
	$dbh->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
	$stmt = $dbh->prepare("SELECT device_id FROM devices WHERE auth_code = :auth_code");
	$stmt->bindParam(':auth_code', $auth_code, PDO::PARAM_STR);
	
	$stmt->execute();
	
	$device_id = $stmt->fetchColumn();	
}
catch(Exception $e) {
	die('{"success": false, "message": "'.$e.'"}');
}			

// Return success
die('{"success": true, "auth_code": "'.$auth_code.'", "device_id": '.$device_id.'}');	
?>