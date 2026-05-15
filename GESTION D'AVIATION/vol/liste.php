<?php
session_start();
require '../connexion.php';

$sql = "SELECT v.*, d.ville, u.nom 
        FROM vol v
        JOIN destination d ON v.id_destination = d.id_destination
        LEFT JOIN utilisateur u ON v.id_user = u.id_user";

$vols = $pdo->query($sql)->fetchAll();
?>
<h2>Liste des réservations</h2>

<a href="reserver.php">Faire une réservation</a> |
<a href="../index.php">Retour</a>

<table border="1">
<tr>
    <th>Code</th>
    <th>Date</th>
    <th>Départ</th>
    <th>Arrivée</th>
    <th>Destination</th>
    <th>Utilisateur</th>
    <th>Actions</th>
</tr>

<?php foreach($vols as $v): ?>
<tr>
    <td><?= $v['code_vol'] ?></td>
    <td><?= $v['date_depart'] ?></td>
    <td><?= $v['heure_depart'] ?></td>
    <td><?= $v['heure_arrivee'] ?></td>
    <td><?= $v['ville'] ?></td>
    <td><?= $v['nom'] ?></td>
    <td>
        <a href="modifier.php?id=<?= $v['id_vol'] ?>">Modifier</a>
        <a href="supprimer.php?id=<?= $v['id_vol'] ?>" onclick="return confirm('Supprimer ?')">Supprimer</a>
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


