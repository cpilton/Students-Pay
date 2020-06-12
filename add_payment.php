<?php

require 'database.php';

$user_id = $_COOKIE['user_id'];
$name = $_POST['name'];
$type = $_POST['type'];
$amount = $_POST['amount'];
$payees = $_POST['payees'];
$account = $_POST['account'];
$added = date('Y-m-d');
$date = $_POST['date'];
$recurring = $_POST['recurring'];
$times = $_POST['times_recurring'];
$group_id = 1;

if ($times == NULL) {
$times = 0;
}

$sql = "SELECT house_id FROM users WHERE id= '".$user_id."'";
foreach($con->query($sql) as $row) {
$house_id = $row['house_id'];
}

$sql2 = "SELECT group_id FROM payments";
foreach($con->query($sql2) as $row2) {
if ($row2['group_id'] >= $group_id) {
	$group_id = $row2['group_id'] + 1;
}
}

for ($i = 0 ; $i <= $times ; $i++) {

$add_payment = $DBH->prepare('INSERT INTO payments VALUES(null, :group_id, :user_id, :house_id, :name, :type, :amount, :payees, :account, :added, :date)');
$add_payment->bindParam('group_id', $group_id);
$add_payment->bindParam('user_id', $user_id);
$add_payment->bindParam('house_id', $house_id);
$add_payment->bindParam('name', $name);
$add_payment->bindParam('type', $type);
$add_payment->bindParam('amount', $amount);
$add_payment->bindParam('payees', $payees);
$add_payment->bindParam('account', $account);	
$add_payment->bindPAram('added', $added);
$add_payment->bindParam('date', $date);
$add_payment->execute();
	
	if ($recurring == 'daily') {
		$added = $date;
		$date = date("Y-m-d", strtotime($date. ' + 1 day'));
	}
	else if ($recurring == 'weekly'){
		$added = $date;
		$date = date("Y-m-d", strtotime($date. ' + 1 week'));
	}
	else if ($recurring == 'monthly'){
		$added = $date;
		$date = date('Y-m-d', strtotime($date. ' + 1 month'));
	}
	else if ($recurring == 'yearly'){
		$added = $date;
		$date = date('Y-m-d', strtotime($date. ' + 1 year'));
	}
}

session_start();
$_SESSION['redirect'] = 'added';
header("Location:" . $_SESSION['current_page']);

die();

?>
<!-- Callum Pilton -->