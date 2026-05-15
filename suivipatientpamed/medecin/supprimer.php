<?php
include("../config.php");

$id = $_GET['id'];

$conn->query("DELETE FROM Medecins WHERE medecin_id=$id");

header("Location: liste.php");
?>