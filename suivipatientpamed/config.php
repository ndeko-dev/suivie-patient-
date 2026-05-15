<?php
$host = "localhost";
$user = "root";
$password = "";
$db = "suivipatients";

$conn = mysqli_connect($host, $user, $password, $db);

if (!$conn) {
    die("Erreur de connexion : " . mysqli_connect_error());
}

// Sécurité session
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
?>
