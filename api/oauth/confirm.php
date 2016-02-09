<?php
/*

*/
session_start();

// include our OAuth2 Server object
require_once __DIR__.'/server.php';

$request = OAuth2\Request::createFromGlobals();
$response = new OAuth2\Response();

$user_id = $_SESSION['user_id'];
if (empty($user_id)) die('Invalid User');

// validate the authorize request
if (!$server->validateAuthorizeRequest($request, $response)) {
    //$response->send();
    die('Bad Client');
}	
	
// print the authorization code if the user has authorized your client
$is_authorized = ($_POST['authorized'] === 'yes');
$server->handleAuthorizeRequest($request, $response, $is_authorized, $user_id);
if ($is_authorized) {
	// this is only here so that you get to see your code in the cURL request. Otherwise, we'd redirect back to the client
	$code = substr($response->getHttpHeader('Location'), strpos($response->getHttpHeader('Location'), 'code=')+5, 40);
	
	// sample code in case we want the user_id - for use when re-verifying key
	$token = $server->getAccessTokenData(OAuth2\Request::createFromGlobals());
	//echo "User ID associated with this token is {$token['user_id']}";
	
	exit('<meta http-equiv="refresh" content="0; url=http://dylan.ryanmaule.com/cam4tv/index.html#/auth/'.$code.'" />');
} else {
	die('Not Authorized Error');
}
$response->send();
?>