<?php
include("config.php");

$error = "";
$success = "";

if(isset($_POST['register'])){

    $nom = trim($_POST['nom']);
    $password = password_hash($_POST['password'], PASSWORD_DEFAULT);

    // Vérifier si utilisateur existe déjà
    $check = $conn->prepare("SELECT * FROM Utilisateurs WHERE nom = ?");
    $check->bind_param("s", $nom);
    $check->execute();
    $result = $check->get_result();

    if($result->num_rows > 0){
        $error = "❌ Nom utilisateur déjà utilisé";
    } else {

        $stmt = $conn->prepare("
            INSERT INTO Utilisateurs (nom, mot_de_passe, role_id)
            VALUES (?, ?, 1)
        ");

        $stmt->bind_param("ss", $nom, $password);

        if($stmt->execute()){
            $success = "✅ Compte créé avec succès";
        } else {
            $error = "❌ Erreur lors de l'inscription";
        }
    }
}
?>

<!DOCTYPE html>
<html>
<head>
<title>Inscription PAMED</title>

<style>
body {
    margin:0;
    font-family: Arial;
    background: url('bg.jpg') no-repeat center/cover;
    height:100vh;
    display:flex;
    justify-content:center;
    align-items:center;
}

.card {
    background: rgba(0,0,0,0.5);
    padding:30px;
    width:350px;
    border-radius:15px;
    text-align:center;
    color:white;
}

input {
    width:100%;
    padding:10px;
    margin:10px 0;
    border-radius:8px;
    border:none;
}

button {
    width:100%;
    padding:10px;
    border:none;
    border-radius:8px;
    background:green;
    color:white;
}

.error { color:yellow; }
.success { color:lightgreen; }

a {
    display:block;
    margin-top:10px;
    color:white;
}
</style>

</head>

<body>

<div class="card">

    <h2>🏥 Création compte</h2>

    <?php if($error) echo "<p class='error'>$error</p>"; ?>
    <?php if($success) echo "<p class='success'>$success</p>"; ?>

    <form method="POST">

        <input type="text" name="nom" placeholder="Nom utilisateur" required>

        <input type="password" name="password" placeholder="Mot de passe" required>

        <button name="register">S'inscrire</button>
    </form>

    <a href="login.php">Retour connexion</a>

</div>

</body>
</html>