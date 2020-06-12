<?php

require 'database.php';

session_start();

$user_id = $_COOKIE[ 'user_id' ];
$current_password = sha1($_POST['current_password']);
$new_password = sha1( $_POST[ 'password' ] );

$sql = "SELECT password FROM users WHERE id = '" . $user_id . "'";
foreach ( $con->query( $sql ) as $row ) {
	$real_current_password = $row[ 'password' ];
}

if ( $current_password == $real_current_password ) {

	$update = $DBH->prepare( "UPDATE users SET password = '" . $new_password . "' WHERE id = '" . $user_id . "'" );
	$update->execute();

	$_SESSION[ 'redirect' ] = 'pwdchanged';

} else {
	$_SESSION[ 'redirect' ] = 'pwdnotchanged';
}

header( "Location:" . $_SESSION[ 'current_page' ] );

?>

<!-- Callum Pilton -->