<?php include("../config.php");

$id = $_GET['id'];
$data = $conn->query("SELECT * FROM Medecins WHERE medecin_id=$id")->fetch_assoc();

if(isset($_POST['update'])){
    $nom = $_POST['nom'];
    $specialite = $_POST['specialite'];
    $telephone = $_POST['telephone'];
    $service = $_POST['service'];

    $conn->query("UPDATE Medecins SET
    nom='$nom',
    specialite='$specialite',
    telephone='$telephone',
    service_id='$service'
    WHERE medecin_id=$id");

    header("Location: liste.php");
}
?>

<!DOCTYPE html>
<html>
<head>
<title>Modifier</title>

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
    background:orange;
    color:white;
    border:none;
}

a { display:block; margin-top:10px; }
</style>

</head>

<body>

<h2>Modifier Médecin</h2>

<form method="POST">

<input type="text" name="nom" value="<?= $data['nom'] ?>">
<input type="text" name="specialite" value="<?= $data['specialite'] ?>">
<input type="text" name="telephone" value="<?= $data['telephone'] ?>">

<select name="service">
<?php
$res = $conn->query("SELECT * FROM Services");
while($s = $res->fetch_assoc()){
    $sel = ($s['service_id'] == $data['service_id']) ? "selected" : "";
    echo "<option $sel value='{$s['service_id']}'>{$s['nom_service']}</option>";
}
?>
</select>

<button name="update">Modifier</button>

<a href="liste.php">Retour</a>

</form>

</body>
</html>