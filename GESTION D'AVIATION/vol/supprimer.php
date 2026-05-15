<?php
require '../connexion.php';

$id = $_GET['id'];

$pdo->prepare("DELETE FROM vol WHERE id_vol=?")
    ->execute([$id]);

header("Location: liste.php");
