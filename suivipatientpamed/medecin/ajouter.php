<?php include("../config.php");

if(isset($_POST['save'])){
    $nom = $_POST['nom'];
    $specialite = $_POST['specialite'];
    $telephone = $_POST['telephone'];
    $service = $_POST['service'];

    $conn->query("INSERT INTO Medecins(nom,specialite,telephone,service_id)
                  VALUES('$nom','$specialite','$telephone','$service')");

    header("Location: liste.php");
}
?>

<!DOCTYPE html>
<html>
<head>
<title>Ajouter</title>

<style>
body { font-family:Arial; background:#f4f6f9; text-align:center; }

form {
    width:300px;
    margin:auto;
    margin-top:50px;
    background:white;
    padding:20px;
    border-radius:10px;
}

input, select {
    width:100%;
    padding:10px;
    margin:10px 0;
}

button {
    width:100%;
    padding:10px;
    background:green;
    color:white;
    border:none;
}

a { display:block; margin-top:10px; }
</style>

</head>

<body>

<h2>Ajouter Médecin</h2>

<form method="POST">

<input type="text" name="nom" placeholder="Nom" required>
<input type="text" name="specialite" placeholder="Spécialité">
<input type="text" name="telephone" placeholder="Téléphone">

<select name="service">
<option value="">-- Service --</option>

<?php
$res = $conn->query("SELECT * FROM Services");
while($s = $res->fetch_assoc()){
    echo "<option value='{$s['service_id']}'>{$s['nom_service']}</option>";
}
?>

</select>

<button name="save">Enregistrer</button>

<a href="liste.php">Retour</a>

</form>

</body>
</html>