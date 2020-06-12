<?php

require 'database.php';

session_start();

$user_id = $_COOKIE[ 'user_id' ];
$people = $_POST['size'];

$sql = "SELECT house_id FROM users WHERE id = '" . $user_id . "'";
foreach ( $con->query( $sql ) as $row ) {
	$house_id = $row[ 'house_id' ];
}

	$update = $DBH->prepare( "UPDATE house SET people = '" . $people . "' WHERE id = '" . $house_id . "'" );
	$update->execute();

header( "Location:" . $_SESSION[ 'current_page' ] );

?>

<!-- Callum Pilton -->