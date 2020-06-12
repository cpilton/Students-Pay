<?php

require 'database.php';

$password = sha1($_POST['password']);
$house_id = 0;

$register = $DBH->prepare('INSERT INTO users VALUES(null, :first_name, :last_name, :username, :password, :house_id)');

$register->bindParam('first_name', $_POST['first_name']);
$register->bindParam('last_name', $_POST['last_name']);
$register->bindParam('username', $_POST['username']);
$register->bindParam('password', $password);
$register->bindParam('house_id', $house_id);

$register->execute();

header("Location: session.php?a=login&m=reg_succ");
die();
?>
<!-- Callum Pilton -->