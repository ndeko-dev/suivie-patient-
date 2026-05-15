<?php
require '../connexion.php';

$id = $_GET['id'];

$stmt = $pdo->prepare("SELECT * FROM destination WHERE id_destination=?");
$stmt->execute([$id]);
$data = $stmt->fetch();

if(isset($_POST['update'])){
    $ville = $_POST['ville'];
    $pays = $_POST['pays'];

    $pdo->prepare("UPDATE destination SET ville=?, pays=? WHERE id_destination=?")
        ->execute([$ville,$pays,$id]);

    header("Location: liste.php");
}
?>

<h2>Modifier destination</h2>

<form method="POST">

Ville:
<input type="text" name="ville" value="<?= $data['ville'] ?>"><br><br>

Pays:
<input type="text" name="pays" value="<?= $data['pays'] ?>"><br><br>

<button name="update">Modifier</button>

</form>

<br>
<a href="liste.php">Retour</a>
<style>
        body {
            margin: 0;
            font-family: Arial, sans-serif;
            background: #93b6b3;
        }

        header {
            background: linear-gradient(90deg, #007BFF, #00c6ff);
            color: white;
            padding: 20px;
            text-align: center;
        }

        .user-info {
            margin-top: 10px;
            font-size: 14px;
        }

  a{
     width: 100%; /* Très important */
    border-collapse: collapse;
    font-size: 19px; /* Texte plus grand */
        }
        a{
     padding: 15px;
    text-align: center
        }