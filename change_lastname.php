<?php

require 'database.php';

$user_id = $_COOKIE['user_id'];
$last_name = $_POST['last_name'];

$update = $DBH->prepare( "UPDATE users SET last_name = '" . $last_name . "' WHERE id = '" . $user_id . "'" );
	$update->execute();

session_start();
header("Location:" . $_SESSION['current_page']);

?>

<!-- Callum Pilton --> 