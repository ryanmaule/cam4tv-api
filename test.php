<?php
$cookie_value = (isset($_GET["affiliate_code"]))?$_GET["affiliate_code"]:((isset($_COOKIE["affiliate_code"]))?$_COOKIE["affiliate_code"]:"200466");
setcookie("affiliate_code",$cookie_value,time() + (86400 * 30), "/");
die("Cookie Value: ".$cookie_value);
?>