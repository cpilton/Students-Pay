<?php

require 'database.php';

$user_id = $_COOKIE['user_id'];
$first_name = $_POST['first_name'];

$update = $DBH->prepare( "UPDATE users SET first_name = '" . $first_name . "' WHERE id = '" . $user_id . "'" );
	$update->execute();

session_start();
header("Location:" . $_SESSION['current_page']);

?>

<!-- Callum Pilton --> 