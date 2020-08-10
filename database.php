<?php

$host = $_ENV["SQL_HOST"];
$user = $_ENV["SQL_USER"];
$password = $_ENV["SQL_PASSWORD"];
$database = $_ENV["SQL_DATABASE"];

$DBH = new PDO( "mysql:host=$host;dbname=$database", $user, $password );

$con = new mysqli($host, $user, $password, $database);
?>
<!-- Callum Pilton -->