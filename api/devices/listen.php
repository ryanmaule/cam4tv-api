<?php
/*
Listen for channel updates
*/
session_start();

// Get credentials
require_once __DIR__.'/db.php';	

// Get PubNub client
require_once('autoloader.php');
use Pubnub\Pubnub;

$auth_code = $_REQUEST['auth_code'];

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


// PubNub
$pubnub = new Pubnub('sub-c-2023456c-d1a2-11e5-bcee-0619f8945a4f', 'pub-c-a9afac0f-597a-4d95-a975-83b16220f02b');
 
$pubnub->subscribe($auth_code, function ($envelope) {
    $msg = $envelope['message'];
    print_r($msg);
 
    if (strcmp($msg, "exit") == 0) {
        print_r('<<< So long, and thanks for all the messages! >>>');
        return false;
    }
    else {
        print_r('>>> May I have some more message, please? <<<');
        return true;
    }
});
?>