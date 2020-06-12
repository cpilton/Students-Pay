<?php

require 'database.php';


$password = sha1($_POST['password']);


$login = $DBH->prepare('SELECT id, password FROM users WHERE username=:username LIMIT 0,1;');


$login->bindParam('username', $_POST['username']);

$login->execute();

$user = $login->fetch();



if ($user['password'] == $password) {
   
   setcookie('user_id', $user['id'], time()+(60*60));
	session_start();
  header("Location:index.php");
	
    die();
} else {
    
    header("Location: session.php?a=login&m=invalid");
    die();
}
?>
<!-- Callum Pilton -->