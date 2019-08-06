<?php
//logout unsets session variables which are login in creds and then redirects to index
session_start();
unset($_SESSION["success"]);
unset($_SESSION["email"]);
session_destroy();
header("location: index.php");
return;
?>