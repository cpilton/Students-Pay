<?php

require 'database.php';

$user_id = $_COOKIE['user_id'];
$password = sha1($_POST['password']);
$people = $_POST['people'];
$house_id = rand(1000000,9999999);
$check = 1;
$count = 0;
$joint_account = 1;

$sql = "SELECT id FROM house";
if ($check == 1) {
	$house_id = rand(1000000,9999999);
foreach($con->query($sql) as $row) {
	if ($house_id == $row['house_id']) {
		$count++;
	}
}
	if ($count == 0) {
		$check = 0;
	}
}

$create_house = $DBH->prepare('INSERT INTO house VALUES(:id, :password, :owner_id, :people, :joint_account)');
$create_house->bindParam('id', $house_id);
$create_house->bindParam('password', $password);
$create_house->bindParam('owner_id', $user_id);
$create_house->bindParam('people', $people);
$create_house->bindParam('joint_account', $joint_account);
$create_house->execute();

$sql = "SELECT id FROM house WHERE owner_id = '".$user_id."'";
foreach($con->query($sql) as $row) {
$house_id = $row['id'];
}

$update_house_id = $DBH->prepare("UPDATE users SET house_id = '".$house_id."' WHERE id = '".$user_id."'");
$update_house_id->execute();

session_start();
$_SESSION['redirect'] = 'created';
header("Location:" . $_SESSION['current_page']);

die();

?>
<!-- Callum Pilton -->