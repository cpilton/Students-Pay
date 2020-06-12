<?php

require 'database.php';

session_start();

$user_id = $_COOKIE[ 'user_id' ];

$sql = "SELECT house_id FROM users WHERE id = '" . $user_id . "'";
foreach ( $con->query( $sql ) as $row ) {
	$house_id = $row[ 'house_id' ];
}

$sql = "SELECT joint_account FROM house WHERE id = '" . $house_id . "'";
foreach ( $con->query( $sql ) as $row ) {
	$joint_account = $row[ 'joint_account' ];
}

if ($joint_account == 1) {
	$new = 0;
} else {
	$new = 1;
}

	$update = $DBH->prepare( "UPDATE house SET joint_account = '" . $new . "' WHERE id = '" . $house_id . "'" );
	$update->execute();

header( "Location:" . $_SESSION[ 'current_page' ] );

?>

<!-- Callum Pilton -->