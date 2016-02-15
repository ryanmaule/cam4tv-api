<?php
/*

*/
session_start();

$username = $_REQUEST['username'];
$password = $_REQUEST['password'];
$password = $_REQUEST['deviceId'];

// Need to answer security questions
$cam4_security_url = "http://api.cam4.com/loginsecurenonweb?app=COMMUNICATOR&username=".$username;

$obj = curl_it($cam4_security_url);	

if (isset($obj['questionID'])) {
	$questionid = $obj['questionID'];
	$question = $obj['question'];
	$answer = '' // Need an interface to get the answer	
}	
else {
	// Something went wrong, maybe JSON will reveal it?
	die('Error Fetching Question');
}

function curl_it($url) {
		// Get cURL resource
	$curl = curl_init();
	// Set some options - we are passing in a useragent too here
	curl_setopt_array($curl, array(
	    CURLOPT_RETURNTRANSFER => 1,
	    CURLOPT_URL => $cam4_auth_url,
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
<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8" />
    <title>CAM4</title>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.1.1/css/bootstrap.min.css" />
    <link rel="stylesheet" href="/cam4tv/css/cam4tv.css" />
    
    <meta name="viewport" content="width=device-width; initial-scale=1.0; maximum-scale=1.0; minimum-scale=1.0; user-scalable=0;" />
	<meta name="apple-mobile-web-app-capable" content="yes" />
</head>
<body>

    <div class="container">
        <div class="card card-container">
            <img id="profile-img" class="profile-img-card" src="//ssl.gstatic.com/accounts/ui/avatar_2x.png" />
            <p id="profile-name" class="profile-name-card"></p>

<div ng-show="error" class="alert alert-danger">{{error}}</div>
<form method="post" action="cam4.php">
<input type="hidden" name="username" value="<?php echo $username ?>" />
<input type="hidden" name="password" value="<?php echo $password ?>" />
<input type="hidden" name="deviceId" value="<?php echo $deviceId ?>" />
    <div class="form-group">
        <i class="fa fa-key"></i>
        <input type="text" name="answer" id="answer" class="form-control" ng-model="answer" placeholder="Answer" required />
        <span ng-show="form.answer.$dirty && form.answer.$error.required" class="help-block">Answer is required</span>
    </div>
    <div class="form-actions">
        <button type="submit" ng-disabled="form.$invalid || dataLoading" class="btn btn-lg btn-primary btn-block btn-signin">Sign In</button>
        <img ng-if="dataLoading" src="data:image/gif;base64,R0lGODlhEAAQAPIAAP///wAAAMLCwkJCQgAAAGJiYoKCgpKSkiH/C05FVFNDQVBFMi4wAwEAAAAh/hpDcmVhdGVkIHdpdGggYWpheGxvYWQuaW5mbwAh+QQJCgAAACwAAAAAEAAQAAADMwi63P4wyklrE2MIOggZnAdOmGYJRbExwroUmcG2LmDEwnHQLVsYOd2mBzkYDAdKa+dIAAAh+QQJCgAAACwAAAAAEAAQAAADNAi63P5OjCEgG4QMu7DmikRxQlFUYDEZIGBMRVsaqHwctXXf7WEYB4Ag1xjihkMZsiUkKhIAIfkECQoAAAAsAAAAABAAEAAAAzYIujIjK8pByJDMlFYvBoVjHA70GU7xSUJhmKtwHPAKzLO9HMaoKwJZ7Rf8AYPDDzKpZBqfvwQAIfkECQoAAAAsAAAAABAAEAAAAzMIumIlK8oyhpHsnFZfhYumCYUhDAQxRIdhHBGqRoKw0R8DYlJd8z0fMDgsGo/IpHI5TAAAIfkECQoAAAAsAAAAABAAEAAAAzIIunInK0rnZBTwGPNMgQwmdsNgXGJUlIWEuR5oWUIpz8pAEAMe6TwfwyYsGo/IpFKSAAAh+QQJCgAAACwAAAAAEAAQAAADMwi6IMKQORfjdOe82p4wGccc4CEuQradylesojEMBgsUc2G7sDX3lQGBMLAJibufbSlKAAAh+QQJCgAAACwAAAAAEAAQAAADMgi63P7wCRHZnFVdmgHu2nFwlWCI3WGc3TSWhUFGxTAUkGCbtgENBMJAEJsxgMLWzpEAACH5BAkKAAAALAAAAAAQABAAAAMyCLrc/jDKSatlQtScKdceCAjDII7HcQ4EMTCpyrCuUBjCYRgHVtqlAiB1YhiCnlsRkAAAOwAAAAAAAAAAAA==" />
    </div>
</form>

        </div>
    </div>

</body>
</html>