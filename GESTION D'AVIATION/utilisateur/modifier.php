<?php
require '../connexion.php';

$id = $_GET['id'];

$user = $pdo->prepare("SELECT * FROM utilisateur WHERE id_user=?");
$user->execute([$id]);
$data = $user->fetch();

if(isset($_POST['update'])){
    $nom = $_POST['nom'];
    $email = $_POST['email'];

    $pdo->prepare("UPDATE utilisateur SET nom=?,email=? WHERE id_user=?")
        ->execute([$nom,$email,$id]);

    header("Location: liste.php");
}
?>

<h2>Modifier utilisateur</h2>

<form method="POST">
Nom: <input type="text" name="nom" value="<?= $data['nom'] ?>"><br><br>
Email: <input type="email" name="email" value="<?= $data['email'] ?>"><br><br>

<button name="update">Modifier</button>
</form>

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