<?php
/*
Update channel
*/
session_start();

// Get credentials
require_once __DIR__.'/db.php';	

// Get PubNub client
require_once('autoloader.php');
use Pubnub\Pubnub;

$auth_code = $_REQUEST['auth_code'];
$current_view = $_REQUEST['current_view'];

try {
	// Get the new device_id	
	$dbh = new PDO("mysql:host=$mysql_hostname;dbname=$mysql_dbname", $mysql_username, $mysql_password);		
	$dbh->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
	$stmt = $dbh->prepare("SELECT status, device_id, key_code FROM devices WHERE auth_code = :auth_code");
	$stmt->bindParam(':auth_code', $auth_code, PDO::PARAM_STR);
	
	$stmt->execute();
		
	$status = $stmt->fetchColumn();	
	$device_id = $stmt->fetchColumn(1);
	$key_code = $stmt->fetchColumn(2);
}
catch(Exception $e) {
	die('{"success": false, "message": "'.$e.'"}');
}

// Add an HTTP call to OAuth2 to verify the $key_code


// Record session if one is not already open


// Update session if one is open with current_view


// PubNub
$pubnub = new Pubnub('pub-c-a9afac0f-597a-4d95-a975-83b16220f02b', 'sub-c-2023456c-d1a2-11e5-bcee-0619f8945a4f');
 
// Use the publish command separately from the Subscribe code shown above. 
// Subscribe is not async and will block the execution until complete.
$hereNow = $pubnub->hereNow($auth_code);
print_r($hereNow);

$publish_result = $pubnub->publish($auth_code,$current_view);
print_r($publish_result);
?>