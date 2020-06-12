<?php

require 'database.php';

$house_id = 0;
$user_id = $_COOKIE['user_id'];

$leave_house = $DBH->prepare( "UPDATE users SET house_id = '" . $house_id . "' WHERE id = '" . $user_id . "'" );
	$leave_house->execute();

session_start();
header("Location:" . $_SESSION['current_page']);

?>

<!-- Callum Pilton --> 