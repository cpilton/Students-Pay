<?php

require 'database.php';

if(isset($_POST["username"]))
{
	
	if(!isset($_SERVER['HTTP_X_REQUESTED_WITH']) AND strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) != 'xmlhttprequest') {
		die();
	}
	
	
	$connecDB = mysqli_connect($host, $user, $password,$database)or die('could not connect to database');
	
	
	$username =  strtolower(trim($_POST["username"])); 
	
	
	$username = filter_var($username, FILTER_SANITIZE_STRING, FILTER_FLAG_STRIP_LOW|FILTER_FLAG_STRIP_HIGH);
	
	
	$results = mysqli_query($connecDB,"SELECT id FROM users WHERE username='$username'");
	
	
	$username_exist = mysqli_num_rows($results);
	
	
	if($username_exist) {
		die('<img src="img/not-available.png" />');
	}else{
		die('<img src="img/available.png" />');
	}
	
	
	mysqli_close($connecDB);

}
?>
<!-- Callum Pilton -->

