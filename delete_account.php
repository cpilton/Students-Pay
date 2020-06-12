<?php

require 'database.php';

$user_id = $_COOKIE['user_id'];

$sql = "DELETE FROM users WHERE id = '".$user_id."'";
$DBH->exec($sql);

session_start();
header("Location:logout.php");

?>

<!-- Callum Pilton --> 