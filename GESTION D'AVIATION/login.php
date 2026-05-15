<?php
session_start();
require 'connexion.php';

if (isset($_POST['login'])) {

    $email = $_POST['email'];
    $password = $_POST['password'];

    $sql = "SELECT * FROM utilisateur WHERE email=?";
    $stmt = $pdo->prepare($sql);
    $stmt->execute([$email]);

    $user = $stmt->fetch();

    if ($user) {

        if (password_verify($password, $user['mot_de_passe'])) {

            $_SESSION['user'] = $user['nom'];
            $_SESSION['id'] = $user['id_user'];
            $_SESSION['role'] = $user['role'];

            header("Location: index.php");
            exit();

        } else {
            $erreur = "Mot de passe incorrect";
        }

    } else {
        $erreur = "Email introuvable";
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Connexion</title>

    
</head>
<body>



<?php if (isset($erreur)) echo "<p style='color:red;'>$erreur</p>"; ?>

<form method="POST">
    <h2>Connexion utilisateur</h2>

    Email:
    <input type="email" name="email" required><br><br>

    Mot de passe:
    <input type="password" name="password" required><br><br>

    <button name="login">Se connecter</button>

</form>

<br>
<a href="register.php">Créer un compte</a>
</body>
</html>

<style>
   body {
            margin: 400px;
            font-family: Arial, sans-serif;
            background: url('bg.jpg') no-repeat center/cover;
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

 {
    margin:0;
    font-family: Arial;
    background: url('bg.jpg') no-repeat center/cover;
    height:100vh;
    display:flex;
    justify-content:center;
    align-items:center;
}

  .card {
    background: font-family: Arial;
    background: url('bg.jpg') no-repeat center/cover;
    height:100vh;
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

  a { display:block; margin-top:10px; }
</style>