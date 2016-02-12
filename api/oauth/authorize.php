<?php
/*

*/
session_start();

// include our OAuth2 Server object
require_once __DIR__.'/server.php';

$request = OAuth2\Request::createFromGlobals();
$response = new OAuth2\Response();

// validate the authorize request
if (!$server->validateAuthorizeRequest($request, $response)) {
    $response->send();
    die('Bad Client');
}
?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8" />
    <title>CAM4TV</title>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.1.1/css/bootstrap.min.css" />
    <style>
	    body {
		    background-color: #eee;
	    }
	</style>
</head>
<body>

    <div class="jumbotron">
        <div class="container">
            <div>
                <div>
	                
<form method="post" action="confirm.php?response_type=<?php echo $_REQUEST['response_type']?>&client_id=<?php echo $_REQUEST['client_id']?>&state=<?php echo $_REQUEST['state']?>">
  <label>Do You Authorize <?php echo $_REQUEST['client_id']?> To Access Your Account?</label><br />
  <input type="submit" name="authorized" value="yes">
  <input type="submit" name="authorized" value="no">
</form>

                </div>
            </div>
        </div>
    </div>

</body>
</html>