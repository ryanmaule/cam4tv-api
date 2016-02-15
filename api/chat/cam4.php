<?php
/*
From: James

1. Scrape profile page for kN='<accessKey>'
2. GET http://webchat.cam4.com/requestAccess?roomname=<room>&accessHash=<accessKey>&username=<username>
2a. If success, read JSON <authToken>
2b. If failed, ????
3. Scrape profile page for cam4FirebaseHost='<cam4FirebaseHost>' Example: https://cam4-5.firebaseio.com/
4. GET http://<cam4FirebaseHost>chatRooms/<room>/chatMessages.json?auth=<authToken>

What does userFirebaseToken do?

Load chat in guest mode:
1. GET http://webchat.cam4.com/requestAccess?roomname=<room>
1a. If success, read <token>
1b. If failed, ????
2. GET https://cam4-5.firebaseio.com/chatRooms/<room>/chatMessages.json?auth=<token>

See https://www.firebase.com/docs/rest/api/ for methods to access
*/	
session_start();
error_reporting(E_ALL);

// Workaround to getting AngularJS post data as $_REQUEST does not work
$params = json_decode(file_get_contents('php://input'),true);

$room = isset($params['room']) ? $params['room'] : isset($_REQUEST['room']) ? $_REQUEST['room'] : '';

// Connect to webchat to get token
$cam4_webchat_url = "http://webchat.cam4.com/requestAccess?roomname=".$room;

try {
	$obj = curl_it($cam4_webchat_url);
	
	if (isset($obj['token'])) {
		$token = $obj['token'];
		
		$import_io_extractor_url = "https://api.import.io/store/connector/d449642d-6969-4df3-92c2-c71324b5548f/_query?input=webpage/url:http%3A%2F%2Fwww.cam4.com%2F".$room."&&_apikey=009709b75e614d4799af1403f3acaa884dbbb1c097366f504221fdb3c73b69057368743e61de127ae810cd55c2f92ee0cbd27125a9af044d43323c8f65a6c286f8674935dc940ab67af26c711fc9bbe3";
		$obj = curl_it($import_io_extractor_url);
		
		if (isset($obj['results'][0])) {
			$result = (array) $obj['results'][0];
			$cam4_firebase_server = $result['cam4firebasehost'];
		} 
		else {
			die('{"success": false, "message": "import.io Failure"}');
		}
		
		$cam4_firebase_url = $cam4_firebase_server."chatRooms/".$room."/chatMessages.json?auth=".$token;
		
		// This is all we need.  Javascript can handle the rest!
		die('{"success": true, "url": "'.$cam4_firebase_url.'"}');
	} else {
		die('{"success": false, "message": "No token generated"}');
	} 
}
catch(Exception $e) {
	// DB Failed
	die('{"success": false, "message": "'.$e.'"}');
}

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