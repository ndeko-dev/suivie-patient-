<?php
session_start();
require '../connexion.php';

$sql = "SELECT * FROM destination";
$destinations = $pdo->query($sql)->fetchAll();
?>

<h2>Liste des destinations</h2>

<a href="ajouter.php">Ajouter une destination</a> |
<a href="../index.php">Retour</a>

<table border="1">
<tr>
    <th>ID</th>
    <th>Ville</th>
    <th>Pays</th>
    <th>Actions</th>
</tr>

<?php foreach($destinations as $d): ?>
<tr>
    <td><?= $d['id_destination'] ?></td>
    <td><?= $d['ville'] ?></td>
    <td><?= $d['pays'] ?></td>
    <td>
        <a href="modifier.php?id=<?= $d['id_destination'] ?>">Modifier</a>
        <a href="supprimer.php?id=<?= $d['id_destination'] ?>" onclick="return confirm('Supprimer ?')">Supprimer</a>
    </td>
</tr>
<?php endforeach; ?>
</table>
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

        .form-container {
            background: white;
            padding: 25px;
            margin: 40px auto;
            width: 350px;
            border-radius: 10px;
            box-shadow: 0px 0px 10px rgba(0,0,0,0.1);
        }

    </style>