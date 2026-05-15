<?php
require '../connexion.php';

$id = $_GET['id'];

$pdo->prepare("DELETE FROM destination WHERE id_destination=?")
    ->execute([$id]);

header("Location: liste.php");
