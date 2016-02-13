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
    <link rel="stylesheet" href="/cam4tv/css/cam4tv.css" />
    
    <meta name="viewport" content="width=device-width; initial-scale=1.0; maximum-scale=1.0; minimum-scale=1.0; user-scalable=0;" />
	<meta name="apple-mobile-web-app-capable" content="yes" />
</head>
<body>

    <div class="container">
        <div class="card card-container">
            <div>
                <div>
	                
<form method="post" action="confirm.php?response_type=<?php echo $_REQUEST['response_type']?>&client_id=<?php echo $_REQUEST['client_id']?>&state=<?php echo $_REQUEST['state']?>">
  <label>Do You Authorize <?php echo $_REQUEST['client_id']?> To Access Your Account?</label><br />
  <button type="submit" name="authorized" value="yes" class="btn btn-sm btn-primary btn-block btn-signin">YES</button>
  <button type="submit" name="authorized" value="no" class="btn btn-sm btn-primary btn-block btn-signin">NO</button>
</form>

                </div>
            </div>
        </div>
    </div>

</body>
</html>