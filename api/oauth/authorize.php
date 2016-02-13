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
    
    <meta name="viewport" content="width=device-width; initial-scale=1.0; maximum-scale=1.0; minimum-scale=1.0; user-scalable=0;" />
	<meta name="apple-mobile-web-app-capable" content="yes" />
    
	<style>
	body, html {
	    height: 100%;
	    background-repeat: no-repeat;
	    background-image: linear-gradient(rgb(0, 0, 0), rgb(115, 115, 115));
	}
	
	.card-container.card {
	    max-width: 350px;
	    padding: 40px 40px;
	}
	
	.btn {
	    font-weight: 700;
	    height: 36px;
	    -moz-user-select: none;
	    -webkit-user-select: none;
	    user-select: none;
	    cursor: default;
	}
	
	/*
	 * Card component
	 */
	.card {
	    background-color: #F7F7F7;
	    /* just in case there no content*/
	    padding: 20px 25px 30px;
	    margin: 0 auto 25px;
	    margin-top: 50px;
	    /* shadows and rounded borders */
	    -moz-border-radius: 2px;
	    -webkit-border-radius: 2px;
	    border-radius: 2px;
	    -moz-box-shadow: 0px 2px 2px rgba(0, 0, 0, 0.3);
	    -webkit-box-shadow: 0px 2px 2px rgba(0, 0, 0, 0.3);
	    box-shadow: 0px 2px 2px rgba(0, 0, 0, 0.3);
	}
	
	.profile-img-card {
	    width: 96px;
	    height: 96px;
	    margin: 0 auto 10px;
	    display: block;
	    -moz-border-radius: 50%;
	    -webkit-border-radius: 50%;
	    border-radius: 50%;
	}
	
	/*
	 * Form styles
	 */
	.profile-name-card {
	    font-size: 16px;
	    font-weight: bold;
	    text-align: center;
	    margin: 10px 0 0;
	    min-height: 1em;
	}
	
	.reauth-email {
	    display: block;
	    color: #404040;
	    line-height: 2;
	    margin-bottom: 10px;
	    font-size: 14px;
	    text-align: center;
	    overflow: hidden;
	    text-overflow: ellipsis;
	    white-space: nowrap;
	    -moz-box-sizing: border-box;
	    -webkit-box-sizing: border-box;
	    box-sizing: border-box;
	}
	
	.form-signin #inputEmail,
	.form-signin #inputPassword {
	    direction: ltr;
	    height: 44px;
	    font-size: 16px;
	}
	
	.form-signin input[type=email],
	.form-signin input[type=password],
	.form-signin input[type=text],
	.form-signin button {
	    width: 100%;
	    display: block;
	    margin-bottom: 10px;
	    z-index: 1;
	    position: relative;
	    -moz-box-sizing: border-box;
	    -webkit-box-sizing: border-box;
	    box-sizing: border-box;
	}
	
	.form-signin .form-control:focus {
	    border-color: rgb(104, 145, 162);
	    outline: 0;
	    -webkit-box-shadow: inset 0 1px 1px rgba(0,0,0,.075),0 0 8px rgb(104, 145, 162);
	    box-shadow: inset 0 1px 1px rgba(0,0,0,.075),0 0 8px rgb(104, 145, 162);
	}
	
	.btn.btn-signin {
	    /*background-color: #4d90fe; */
	    background-color: rgb(104, 145, 162);
	    /* background-color: linear-gradient(rgb(104, 145, 162), rgb(12, 97, 33));*/
	    padding: 0px;
	    font-weight: 700;
	    font-size: 14px;
	    height: 36px;
	    -moz-border-radius: 3px;
	    -webkit-border-radius: 3px;
	    border-radius: 3px;
	    border: none;
	    -o-transition: all 0.218s;
	    -moz-transition: all 0.218s;
	    -webkit-transition: all 0.218s;
	    transition: all 0.218s;
	}
	
	.btn.btn-signin:hover,
	.btn.btn-signin:active,
	.btn.btn-signin:focus {
	    background-color: rgb(104, 145, 162);
	}
	</style>
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