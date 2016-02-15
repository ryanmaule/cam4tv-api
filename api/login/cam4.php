<?php
/*
	Verify User Authentication with Cam4
*/
session_start();
error_reporting(E_ALL);
	
/*
From: https://cam4com.atlassian.net/wiki/display/MSG/Communicator+Arhitecture+Document	
	
1. POST to http://api.cam4.com/loginnonweb?app=COMMUNICATOR&username=name&password=pass&deviceId=uniqueDeviceId
1a. If success, read accessHash
1b. If failed, read details
1c. If security, read details + token
2. If security, GET http://api.cam4.com/loginsecurenonweb?app=COMMUNICATOR&username=name
2a. If success, read questionID and display question
2b. If failed ????
3. If security, POST http://api.cam4.com/loginsecurenonweb?app=COMMUNICATOR&username=name&questionID=questionID&answer=city&deviceId=uniqueDeviceId&token=token
3a. If success, read token (same as accessHash above - why name change??)
3b. If failed, ????
*/
// Workaround to getting AngularJS post data as $_REQUEST does not work
$params = json_decode(file_get_contents('php://input'),true);

$username = isset($params['username']) ? $params['username'] : isset($_REQUEST['username']) ? $_REQUEST['username'] : '';
$password = isset($params['password']) ? $params['password'] : isset($_REQUEST['password']) ? $_REQUEST['password'] : '';
$deviceId = isset($params['deviceId']) ? $params['deviceId'] : isset($_REQUEST['deviceId']) ? $_REQUEST['deviceId'] : '';
$answer = isset($params['answer']) ? $params['answer'] : isset($_REQUEST['answer']) ? $_REQUEST['answer'] : '';

// Generate a device id
$deviceId = "";
$string = "ABCDEFGHIJKLMNOPQRSTUVWXYZ"; 
for($i=0;$i<6;$i++){ 
    $pos = rand(0,25); 
    $deviceId .= $string{$pos}; 
}

$cam4_auth_url = "http://api.cam4.com/loginnonweb?app=COMMUNICATOR&username=".$username."&password=".$password."&deviceId=".$deviceId;

// Step 1
try {
	$obj = curl_it($cam4_auth_url);
	
	if (!isset($obj['details'])) {
		$token = $obj['accessHash'];
	} 
	else {
		if ($obj['details']=='IpAddressNotKnown') {
			if (empty($answer)) {
				// Redirect to get question
				$cam4_security_url = 'security.php?username='.$username.'&password='.$password.'&deviceId='.$deviceId;
				header('Location: '.$cam4_security_url);
				exit();
			}
			else {
				$cam4_security_request = "http://api.cam4.com/loginsecurenonweb?app=COMMUNICATOR&username=".$username."&questionID=".$questionid."&answer=".$answer."&deviceId=".$deviceId."&token=".$token;
				
				$obj = curl_it($cam4_security_request);	
				
				if (isset($obj['token'])) {
					$token = $obj['token'];
				}	
				else {
					// Something went wrong, maybe JSON will reveal it?
					die('{"success": false, "message": "Error fetching token"}');
				}
			}
		}
		elseif ($obj['details']=='InvalidCredentials') {
			die('{"success": false, "message": "Login Failed"}');
		}
		else {
			// Something went wrong
			die('{"success": false, "message": "'.$obj['details'].'"}');
		}
	}
} 
catch(Exception $e) {
	// DB Failed
	die('{"success": false, "message": "'.$e.'"}');
}

// Return success
die('{"success": true, "token": '.$token.'}');	

function curl_it($url) {
	// Get cURL resource
	$curl = curl_init();
	// Set some options - we are passing in a useragent too here
	curl_setopt_array($curl, array(
	    CURLOPT_RETURNTRANSFER => 1,
	    CURLOPT_URL => $url,
	    CURLOPT_USERAGENT => 'CAM4TV'
	));
	// Send the request & save response to $resp
	$resp = curl_exec($curl);
	// Close request to clear up some resources
	curl_close($curl);
	
	$obj = (array) json_decode($resp);
	
	return $obj;
}
?>