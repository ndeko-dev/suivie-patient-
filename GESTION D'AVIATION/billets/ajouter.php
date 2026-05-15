<?php
session_start();
require_once(__DIR__ . "/../connexion.php");

// récupérer passagers
$passagers = $pdo->query("SELECT * FROM passagers")->fetchAll();

// récupérer vols
$vols = $pdo->query("SELECT * FROM vols")->fetchAll();

if(isset($_POST['submit'])){

    // Vérifier passager
    $checkPassager = $pdo->prepare("SELECT id FROM passagers WHERE id=?");
    $checkPassager->execute([$_POST['passager']]);

    // Vérifier vol
    $checkVol = $pdo->prepare("SELECT id FROM vols WHERE id=?");
    $checkVol->execute([$_POST['vol']]);

    if($checkPassager->rowCount() == 0){
        echo "<p style='color:red;'>❌ Passager invalide</p>";
    }
    elseif($checkVol->rowCount() == 0){
        echo "<p style='color:red;'>❌ Vol invalide</p>";
    }
    else{

        $pdo->prepare("INSERT INTO billets(numero_billet,passager_id,vol_id,prix,statut) VALUES (?,?,?,?,?)")
        ->execute([
            $_POST['num'],
            $_POST['passager'],
            $_POST['vol'],
            $_POST['prix'],
            $_POST['statut']
        ]);

        echo "<p style='color:green;'>✅ Billet ajouté avec succès</p>";
    }
}
?>

<!DOCTYPE html>
<html>
<head>
<title>Ajouter Billet</title>

<style>
  body{text-align:center;font-family:Arial;
  margin-top:115px;
 background: #93b6b3;
}
input,select{
    padding:10px;
    margin:5px;}
button{padding:10px;background:#007BFF;
color:white;
border:none;}  
.container{
background:white;
padding:30px;
border-radius:12px;
width:400px;
box-shadow:0 10px 25px rgba(141, 78, 78, 0.3);
text-align:center;
}

/* TITRE */
h2{
margin-bottom:20px;
color:#333;
}

/* INPUT */
input,select{
width:90%;
padding:10px;
margin:8px 0;
border:1px solid #140707;
border-radius:5px;
}

/* BUTTON */
button{
width:95%;
padding:12px;
background:#007BFF;
color:white;
border:none;
border-radius:5px;
cursor:pointer;
font-size:16px;
transition:0.3s;
}

button:hover{
background:#0056b3;
}

/* LINK */
a{
display:block;
margin-top:10px;
text-decoration:none;
color:#007BFF;
}

/* MESSAGE */
.success{
color:green;
margin-bottom:10px;
}

.error{
color:red;
margin-bottom:10px;
}
</style>

</style>

</head>

<body>

<h2>🎫 Ajouter Billet</h2>

<form method="POST">

<input name="num" placeholder="Numéro billet" required><br>

<!-- PASSAGER -->
<select name="passager" required>
<option value="">Choisir passager</option>
<?php foreach($passagers as $p): ?>
<option value="<?= $p['id'] ?>">
<?= $p['nom'] ?> <?= $p['prenom'] ?>
</option>
<?php endforeach; ?>
</select><br>

<!-- VOL -->
<select name="vol" required>
<option value="">Choisir vol</option>
<?php foreach($vols as $v): ?>
<option value="<?= $v['id'] ?>">
<?= $v['code_vol'] ?> - <?= $v['destination'] ?>
</option>
<?php endforeach; ?>
</select><br>

<input name="prix" placeholder="Prix" required><br>
<input name="statut" placeholder="Statut" required><br>

<button name="submit">Ajouter</button>
<a href="liste.php">Retour</a>
</form>

</body>
</html>
