<?php
require_once(__DIR__ . "/../app/bootstrap.php");


//Run the session logout method to logout the current user
$session->logout();


header("Location: index.php");
die();