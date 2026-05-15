<?php
session_start();
require '../connexion.php';

$sql = "SELECT * FROM utilisateur";
$users = $pdo->query($sql)->fetchAll();
?>

<h2>Liste des utilisateurs</h2>

<a href="ajouter.php">Ajouter</a> |
<a href="../index.php">Retour</a>

<table border="1">
<tr>
    <th>ID</th>
    <th>Nom</th>
    <th>Email</th>
    <th>Actions</th>
</tr>

<?php foreach($users as $u): ?>
<tr>
    <td><?= $u['id_user'] ?></td>
    <td><?= $u['nom'] ?></td>
    <td><?= $u['email'] ?></td>
    <td>
        <a href="modifier.php?id=<?= $u['id_user'] ?>">Modifier</a>
        <a href="supprimer.php?id=<?= $u['id_user'] ?>">Supprimer</a>
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
