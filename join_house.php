<?php

require 'database.php';
session_start();

$user_id = $_COOKIE[ 'user_id' ];
$house_id = $_POST[ 'id' ];
$password = sha1( $_POST[ 'password' ] );
$real_password = "password_not_entered";

$sql = "SELECT password FROM house WHERE id = '" . $house_id . "'";
foreach ( $con->query( $sql ) as $row ) {
	$real_password = $row[ 'password' ];
}

if ( $real_password == $password ) {
	$update_house_id = $DBH->prepare( "UPDATE users SET house_id = '" . $house_id . "' WHERE id = '" . $user_id . "'" );
	$update_house_id->execute();
	$_SESSION['redirect'] = 'joined';
	header("Location:" . $_SESSION['current_page']);
} else {
	$_SESSION['redirect'] = 'incorrect';
header("Location:" . $_SESSION['current_page']);
}
die();
?>
<!-- Callum Pilton -->