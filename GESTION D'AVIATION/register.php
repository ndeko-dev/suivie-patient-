<?php
session_start();
require 'connexion.php';

if (isset($_POST['register'])) {

    $nom = $_POST['nom'];
    $email = $_POST['email'];
    $password = password_hash($_POST['password'], PASSWORD_DEFAULT);

    // Vérifier si email existe
    $check = $pdo->prepare("SELECT * FROM utilisateur WHERE email=?");
    $check->execute([$email]);

    if ($check->rowCount() > 0) {
        $erreur = "Email déjà utilisé";
    } else {

        $sql = "INSERT INTO utilisateur(nom, email, mot_de_passe)
                VALUES (?, ?, ?)";

        $stmt = $pdo->prepare($sql);
        $stmt->execute([$nom, $email, $password]);

        header("Location: login.php");
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Inscription</title>
</head>
<body>

<h2>Créer un compte</h2>

<?php if (isset($erreur)) echo "<p style='color:red;'>$erreur</p>"; ?>

<form method="POST">

    Nom:
    <input type="text" name="nom" required><br><br>

    Email:
    <input type="email" name="email" required><br><br>

    Mot de passe:
    <input type="password" name="password" required><br><br>

    <button name="register">S'inscrire</button>

</form>

<br>
<a href="login.php">Déjà un compte ? Se connecter</a>

</body>
</html>
<style>
body {
    margin: 400px;
    font-family: Arial;
    background: url('bg.jpg') no-repeat center/cover;
    
}

.card {
    background: rgba(127, 175, 165, 0.95);
    padding:30px;
    width:350px;
    border-radius:15px;
    box-shadow:0 0 20px rgba(0,0,0,0.3);
    text-align:center;
}

input {
    width:100%;
    padding:10px;
    margin:10px 0;
    border-radius:8px;
    border:1px solid #ccc;
}

button {
    width:100%;
    padding:10px;
    border:none;
    border-radius:8px;
    background:green;
    color:white;
}

a { display:block; 
margin-top:10px; 
position: absolute;

text-align:center;
}
</style>
