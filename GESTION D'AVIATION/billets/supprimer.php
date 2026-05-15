<?php
require_once("../connexion.php");

$pdo->prepare("DELETE FROM billets WHERE id=?")
->execute([$_GET['id']]);

header("Location: liste.php");
?>