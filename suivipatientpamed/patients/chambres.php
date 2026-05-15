<?php include("../config.php"); ?>

<!DOCTYPE html>
<html>
<head>
<title>Chambres</title>

<style>
body { 
    font-family: Arial; 
    background: #f4f6f9; 
}

.container {
    width: 90%;
    margin: auto;
    text-align: center;
}

table {
    margin: auto;
    border-collapse: collapse;
    width: 100%;
    background: white;
}

th, td {
    padding: 10px;
    border: 1px solid #ddd;
}

th {
    background: #007bff;
    color: white;
}

a {
    padding: 5px 10px;
    text-decoration: none;
    color: white;
    border-radius: 5px;
}

.add { background: green; }
.edit { background: orange; }
.delete { background: red; }
.back { background: black; }

h2 {
    margin-top: 20px;
}
</style>

</head>

<body>

<div class="container">

<h2>🛏 Liste des Chambres</h2>

<a class="add" href="ajouter.php">+ Ajouter Chambre</a>
<a class="back" href="../index.php">⬅ Retour</a>

<br><br>

<table>
<tr>
    <th>ID</th>
    <th>Numéro Chambre</th>
    <th>Type</th>
    <th>Prix</th>
    <th>Statut</th>
    <th>Patient</th>
    <th>Action</th>
</tr>

<?php
$result = $conn->query("
    SELECT chambres.*, patients.nom, patients.prenom
    FROM chambres
    LEFT JOIN patients 
    ON chambres.patient_id = patients.patient_id
");

while($row = $result->fetch_assoc()){
?>

<tr>
    <td><?= $row['chambre_id'] ?></td>
    <td><?= $row['numero_chambre'] ?></td>
    <td><?= $row['type_chambre'] ?></td>
    <td><?= $row['prix'] ?> $</td>
    <td><?= $row['statut'] ?></td>
    <td>
        <?= $row['nom'] ? $row['nom']." ".$row['prenom'] : "Aucun patient" ?>
    </td>

    <td>
        <a class="edit" href="modifier.php?id=<?= $row['chambre_id'] ?>">Modifier</a>
        <a class="delete" href="supprimer.php?id=<?= $row['chambre_id'] ?>">Supprimer</a>
    </td>
</tr>

<?php } ?>

</table>

</div>

</body>
</html>