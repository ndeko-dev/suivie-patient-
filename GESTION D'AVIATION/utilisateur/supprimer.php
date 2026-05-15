
PHP
<?php
require '../connexion.php';

$id = $_GET['id'];

$pdo->prepare("DELETE FROM utilisateur WHERE id_user=?")
    ->execute([$id]);

header("Location: liste.php");
