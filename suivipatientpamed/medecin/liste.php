<?php include("../config.php"); ?>

<!DOCTYPE html>
<html>
<head>
<title>Médecins</title>

<style>
body {
    font-family: Arial;
    background: #f4f6f9;
    text-align:center;
}

.container {
    width:80%;
    margin:auto;
    margin-top:40px;
}

table {
    margin:auto;
    width:100%;
    border-collapse: collapse;
    background:white;
}

th {
    background:#007BFF;
    color:white;
}

th, td {
    padding:10px;
    border:1px solid #ddd;
}

a {
    padding:5px 10px;
    text-decoration:none;
    color:white;
    border-radius:5px;
}

.add { background:green; }
.edit { background:orange; }
.delete { background:red; }
.back { background:black; }

.top {
    margin-bottom:15px;
}
</style>

</head>

<body>

<div class="container">

<h2>Liste des Médecins</h2>

<div class="top">
<a class="add" href="ajouter.php">+ Ajouter</a>
<a class="back" href="../index.php">Retour</a>
</div>

<table>

<tr>
<th>ID</th>
<th>Nom</th>
<th>Spécialité</th>
<th>Téléphone</th>
<th>Service</th>
<th>Action</th>
</tr>

<?php
$sql = "SELECT m.*, s.nom_service 
        FROM Medecins m
        LEFT JOIN Services s ON m.service_id = s.service_id";

$res = $conn->query($sql);

while($row = $res->fetch_assoc()){
?>

<tr>
<td><?= $row['medecin_id'] ?></td>
<td><?= $row['nom'] ?></td>
<td><?= $row['specialite'] ?></td>
<td><?= $row['telephone'] ?></td>
<td><?= $row['nom_service'] ?></td>

<td>
<a class="edit" href="modifier.php?id=<?= $row['medecin_id'] ?>">Modifier</a>
<a class="delete" href="supprimer.php?id=<?= $row['medecin_id'] ?>" onclick="return confirm('Supprimer ?')">Supprimer</a>
</td>
</tr>

<?php } ?>

</table>

</div>

</body>
</html>