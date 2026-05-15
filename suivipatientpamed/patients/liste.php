<?php include("../config.php"); ?>

<!DOCTYPE html>
<html>
<head>
<title>Patients</title>

<style>
body {
    font-family: Arial;
    background: #0f490a13;
    margin: 0;
    padding: 0;
}

.container {
    width: 98%;
    margin: auto;
    text-align: center;
    padding: 20px;
}

/* TABLEAU PLUS COMPACT */
table {
    margin: auto;
    border-collapse: collapse;
    width: 100%;
    background: white;
    font-size: 13px;
}

th, td {
    padding: 7px;
    border: 1px solid #ddd;
    text-align: center;
}

th {
    background: #28a745;
    color: white;
    font-size: 12px;
}

/* BOUTONS */
a {
    padding: 5px 8px;
    text-decoration: none;
    color: white;
    border-radius: 5px;
    font-size: 12px;
    display: inline-block;
    margin: 2px;
}

.add {
    background: green;
}

.edit {
    background: orange;
}

.delete {
    background: red;
}

.back {
    background: black;
}

/* COLONNE ANTECEDENT */
td:nth-child(6) {
    max-width: 180px;
    word-wrap: break-word;
    font-size: 12px;
}

/* ACTION */
td:last-child {
    min-width: 180px;
}

.table-wrapper {
    overflow-x: auto;
}

.tarif-box {
    background: #ffffff;
    padding: 15px;
    border-radius: 8px;
    margin-bottom: 20px;
    border: 1px solid #ddd;
    text-align: left;
}
</style>

</head>

<body>

<div class="container">

<h2>👤 Liste des Patients avec Chambres, Lit, Antécdents et Tarif</h2>

<a class="add" href="ajouter.php">+ Ajouter Patient</a>
<a class="back" href="../index.php">⬅ Retour</a>

<br><br>


<div class="table-wrapper">

<table>
<tr>
    <th>ID</th>
    <th>Nom</th>
    <th>Prénom</th>
    <th>Sexe</th>
    <th>Téléphone</th>
    <th>Antécédent</th>
    <th>Chambre</th>
    <th>Type</th>
    <th>Lit</th>
    <th>Tarif / Jour</th>
    <th>Action</th>
</tr>

<?php
$result = $conn->query("
    SELECT 
        patients.*,
        chambres.numero,
        chambres.type,
        chambres.lit
    FROM patients
    LEFT JOIN chambres
        ON patients.patient_id = chambres.patient_id
    ORDER BY patients.patient_id DESC
");

while($row = $result->fetch_assoc()){

    // Détermination du tarif selon type de chambre
    $type = strtolower($row['type'] ?? '');

    if ($type == "privee" || $type == "privée") {
        $tarif = "20 USD";
    } elseif ($type == "commun") {
        $tarif = "10 USD";
    } elseif (!empty($type)) {
        $tarif = "5 USD";
    } else {
        $tarif = "-";
    }
?>
<tr>
    <td><?= $row['patient_id'] ?></td>

    <td><?= htmlspecialchars($row['nom']) ?></td>

    <td><?= htmlspecialchars($row['prenom']) ?></td>

    <td><?= htmlspecialchars($row['sexe']) ?></td>

    <td><?= htmlspecialchars($row['telephone']) ?></td>

    <td>
        <?= !empty($row['antecedent']) ? htmlspecialchars($row['antecedent']) : "Aucun" ?>
    </td>

    <td>
        <?= !empty($row['numero']) ? htmlspecialchars($row['numero']) : "Non attribuée" ?>
    </td>

    <td>
        <?= !empty($row['type']) ? htmlspecialchars($row['type']) : "-" ?>
    </td>

    <td>
        <?= !empty($row['lit']) ? htmlspecialchars($row['lit']) : "-" ?>
    </td>

    <td>
        <strong><?= $tarif ?></strong>
    </td>

    <td>
        <a class="edit" href="modifier.php?id=<?= $row['patient_id'] ?>">
            Modifier
        </a>

        <a class="delete" href="supprimer.php?id=<?= $row['patient_id'] ?>"
           onclick="return confirm('Voulez-vous vraiment supprimer ce patient ?')">
            Supprimer
        </a>
    </td>
</tr>
<?php } ?>

</table>

</div>

</div>

</body>
</html>