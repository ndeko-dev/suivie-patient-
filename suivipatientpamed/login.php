<?php
session_start();

$conn = new mysqli("localhost", "root", "", "SuiviPatients");

if ($conn->connect_error) {
    die("Erreur connexion : " . $conn->connect_error);
}

$error = "";

if (isset($_POST['login'])) {

    $nom = $_POST['nom'];
    $password = $_POST['password'];

    $stmt = $conn->prepare("SELECT * FROM Utilisateurs WHERE nom = ?");
    $stmt->bind_param("s", $nom);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {

        $user = $result->fetch_assoc();

        // ✅ CORRECTION IMPORTANTE ICI
        if (password_verify($password, $user['mot_de_passe'])) {

            $_SESSION['user'] = $user['nom'];
            $_SESSION['role'] = $user['role_id'];

            header("Location: index.php");
            exit();

        } else {
            $error = "❌ Mot de passe incorrect";
        }

    } else {
        $error = "❌ Utilisateur introuvable";
    }
}
?>

<!DOCTYPE html>
<html>
<head>
<title>Connexion PAMED</title>

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
    background: rgba(0,0,0,0.3);
    padding:30px;
    width:350px;
    border-radius:15px;
    box-shadow:0 0 20px rgba(0,0,0,0.3);
    text-align:center;
    color:white;
}

h2 { color:#00c6ff; }

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
    background:#007BFF;
    color:white;
    cursor:pointer;
}

button:hover { background:#0056b3; }

.google {
    background:#DB4437;
    margin-top:10px;
}

a {
    display:block;
    margin-top:10px;
    color:#00c6ff;
}

.error {
    color:yellow;
}
</style>

</head>

<body>

<div class="card">
    <h2>🏥 HOPITAL PAMED</h2>

    <?php if($error != "") echo "<p class='error'>$error</p>"; ?>

    <form method="POST">

        <input type="text" name="nom" placeholder="Nom utilisateur" required>

        <input type="password" name="password" placeholder="Mot de passe" required>

        <button name="login">Se connecter</button>
    </form>

    <button class="google" onclick="googleLogin()">
        Se connecter avec Google
    </button>

    <a href="register.php">Créer un compte</a>
</div>

<script>
function googleLogin(){
    alert("Google OAuth à configurer plus tard");
}
</script>

</body>
</html>