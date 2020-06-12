<?php

require 'database.php';
session_start();

$user_id = $_COOKIE[ 'user_id' ];
$payment_id = $_GET['a'];

$sql = "SELECT status FROM pay_status WHERE payment_id = '" . $payment_id . "' AND user_id = '".$user_id."'";
foreach ( $con->query( $sql ) as $row ) {
	$status = $row['status'];
}

if ($status == 'paid') {
	$status = 'unpaid';
}
else {
	$status = 'paid';
}

	$update_status = $DBH->prepare( "UPDATE pay_status SET status = '" . $status . "' WHERE payment_id = '" . $payment_id . "' AND user_id = '".$user_id."'");
	$update_status->execute();

header("Location:" . $_SESSION['current_page']);

die();

?>
<!-- Callum Pilton -->