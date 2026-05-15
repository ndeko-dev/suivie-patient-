
<?php
require '../connexion.php';

if(isset($_POST['submit'])){
    $nom = $_POST['nom'];
    $email = $_POST['email'];
    $pass = password_hash($_POST['password'], PASSWORD_DEFAULT);

    $sql = "INSERT INTO utilisateur(nom,email,mot_de_passe)
            VALUES(?,?,?)";

    $pdo->prepare($sql)->execute([$nom,$email,$pass]);

    header("Location: liste.php");
}
?>

<h2>Ajouter utilisateur</h2>

<form method="POST">
Nom: <input type="text" name="nom"><br><br>
Email: <input type="email" name="email"><br><br>
Mot de passe: <input type="password" name="password"><br><br>

<button name="submit">Soumettre</button>
</form>

<a href="liste.php">Retour</a>
<style>
        body {
            margin: 0;
            font-family: Arial, sans-serif;
            background: #93b6b3;
            margin: 150px;
             padding: 20px;
              margin-top:100px;
        }
        input,select{
    padding:10px;
    margin:5px;}
    button{padding:10px;background:#007BFF;
    color:white;
     border:none;}  

        header {
            background: linear-gradient(90deg, #007BFF, #00c6ff);
            color: white;
            padding: 20px;
            text-align: center;
            font-size: 19px;
            
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
</style>