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
<form method="post" action="confirm.php?response_type=<?php echo $_REQUEST['response_type']?>&client_id=<?php echo $_REQUEST['client_id']?>&state=<?php echo $_REQUEST['state']?>">
  <label>Do You Authorize <?php echo $_REQUEST['client_id']?> To Access Your Account?</label><br />
  <input type="submit" name="authorized" value="yes">
  <input type="submit" name="authorized" value="no">
</form>