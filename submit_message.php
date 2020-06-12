<?php

$first = $_POST['first_name'];
$last = $_POST['last_name'];
$user = $_POST['username'];
$email = $_POST['email'];
$message = $_POST['message'];
$ipaddress = $_SERVER['REMOTE_ADDR'];

date_default_timezone_set("Europe/London");
$date = date('d/m/Y');
$time = date('H:i:s');

$name = $first . " " . $last;

$to      = 'contact@studentspay.x10.mx';
$subject = 'Contact Form Message';
$headers = 'From: webmaster@studentspay.tk' . "\r\n" .
    "Reply-To: $email" . "\r\n";
$content = "Name: {$name}
Username: {$user}
Email Address: {$email}

Message: {$message} 

This message was sent from the IP Address: {$ipaddress} on {$date} at {$time}";

mail($to, $subject, $content, $headers);

session_start();
$_SESSION['redirect'] = 'sent';
header("Location:" . $_SESSION['current_page']);

     ?>
     

     
