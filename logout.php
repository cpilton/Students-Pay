<?php
 setcookie('user_id', 'none', time()+(0));
 setcookie('view_ip', 'none', time()+(0));
setcookie('accept', 'none', time()+(0));
session_start();
    header("Location:" . $_SESSION['current_page']);
die();
?>

<!-- Callum Pilton -->