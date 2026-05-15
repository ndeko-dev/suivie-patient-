<?php
require '../connexion.php';

$id = $_GET['id'];

$stmt = $pdo->prepare("SELECT * FROM vol WHERE id_vol=?");
$stmt->execute([$id]);
$data = $stmt->fetch();

if(isset($_POST['update'])){
    $date = $_POST['date'];
    $depart = $_POST['depart'];
    $arrivee = $_POST['arrivee'];

    $pdo->prepare("UPDATE vol SET date_depart=?, heure_depart=?, heure_arrivee=? WHERE id_vol=?")
        ->execute([$date,$depart,$arrivee,$id]);

    header("Location: liste.php");
}
?>

<h2>Modifier réservation</h2>

<form method="POST">

Date:
<input type="date" name="date" value="<?= $data['date_depart'] ?>"><br><br>

Heure départ:
<input type="time" name="depart" value="<?= $data['heure_depart'] ?>"><br><br>

Heure arrivée:
<input type="time" name="arrivee" value="<?= $data['heure_arrivee'] ?>"><br><br>

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

