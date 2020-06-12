<?php

require 'database.php';
session_start();

$user_id = $_COOKIE[ 'user_id' ];
$payment_id = $_GET['a'];

$sql = "SELECT group_id FROM payments WHERE id = '" . $payment_id . "' AND user_id = '".$user_id."'";
foreach ( $con->query( $sql ) as $row ) {
	$group_id = $row['group_id'];
}

$sql2 = "DELETE FROM payments WHERE group_id = '".$group_id."' AND user_id = '".$user_id."'";
$DBH->exec($sql2);

$_SESSION['redirect'] = 'removed';
header("Location:" . $_SESSION['current_page']);

die();

?>
<!-- Callum Pilton -->