<?php
session_start();
require '../connexion.php';

// Sécurité
if (!isset($_SESSION['user'])) {
    header("Location: ../login.php");
    exit();
}

if (isset($_POST['submit'])) {

    $code = $_POST['code'];
    $date = $_POST['date'];
    $depart = $_POST['depart'];
    $arrivee = $_POST['arrivee'];
    $destination = $_POST['destination'];
    $user = $_SESSION['id'];

    // Vérification simple
    if ($depart >= $arrivee) {
        $erreur = "L'heure d'arrivée doit être supérieure à l'heure de départ";
    } else {

        $sql = "INSERT INTO vol(code_vol,date_depart,heure_depart,heure_arrivee,id_destination,id_user)
                VALUES(?,?,?,?,?,?)";

        $stmt = $pdo->prepare($sql);
        $stmt->execute([$code,$date,$depart,$arrivee,$destination,$user]);

        $success = "Réservation effectuée avec succès !";
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Réserver un vol</title>

    <style>
        body {
            font-family: Arial;
            background: #77a7e2;
            text-align: center;
        }

        .form-container {
            background: white;
            padding: 25px;
            margin: 40px auto;
            width: 350px;
            border-radius: 10px;
            box-shadow: 0px 0px 10px rgba(0,0,0,0.1);
        }

        input, select {
            width: 90%;
            padding: 10px;
            margin: 8px;
        }

        button {
            padding: 10px 15px;
            background: #007BFF;
            color: white;
            border: none;
            border-radius: 5px;
        }

        button:hover {
            background: #0056b3;
        }

        .msg {
            color: green;
        }

        .error {
            color: red;
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
</head>

<body>

<div class="form-container">

<h2>✈️ Réserver un vol</h2>

<p>Utilisateur : <b><?= $_SESSION['user']; ?></b></p>

<?php if (isset($erreur)) echo "<p class='error'>$erreur</p>"; ?>
<?php if (isset($success)) echo "<p class='msg'>$success</p>"; ?>

<form method="POST">

Code vol:
<input type="text" name="code" required>

Date de départ:
<input type="date" name="date" required>

Heure de départ:
<input type="time" name="depart" required>

Heure d'arrivée:
<input type="time" name="arrivee" required>

Destination:
<select name="destination" required>
    <option value="">-- Choisir destination --</option>

    <?php
    $stmt = $pdo->query("SELECT * FROM destination");
    foreach ($stmt as $d) {
        echo "<option value='{$d['id_destination']}'>{$d['ville']} ({$d['pays']})</option>";
    }
    ?>
</select>

<br>

<button name="submit">Soumettre</button>

</form>

<br>
<a href="liste.php">Voir les réservations</a> |
<a href="../index.php">Retour accueil</a>

</div>

</body>
</html>


