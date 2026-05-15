<?php
session_start();
require_once("../connexion.php");

$billets = $pdo->query("SELECT * FROM billets")->fetchAll();
?>

<!DOCTYPE html>
<html>
<head>
<title>Billets</title>

<style>
    body {
            margin: 0;
            font-family: Arial, sans-serif;
            background: #93b6b3;
        }
table {
margin: auto;
border-collapse: collapse;
width: 70%;
background: white;
}
th, td {
padding: 10px;
border: 1px solid #ccc;
text-align: center;
}
th {
background: #28a745;
color: white;
}
.container {
text-align: center;
margin-top: 30px;
}
</style>

</head>

<body>

<div class="container">
<h2>🎫 Liste des Billets</h2>

<a href="ajouter.php"><button>➕ Ajouter</button></a>
<table>
<tr>
<th>ID</th>
<th>Numéro</th>
<th>Passager</th>
<th>Vol</th>
<th>Prix</th>
<th>Action</th>
</tr>

<?php foreach($billets as $b): ?>
<tr>
<td><?= $b['id'] ?></td>
<td><?= $b['numero_billet'] ?></td>
<td><?= $b['passager_id'] ?></td>
<td><?= $b['vol_id'] ?></td>
<td><?= $b['prix'] ?></td>
<td>
<a href="supprimer.php?id=<?= $b['id'] ?>">❌</a>
</td>
</tr>
<?php endforeach; ?>
<a href="../index.php">Retour</a>
</table>

</div>

</body>
</html>