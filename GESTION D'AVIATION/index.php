<?php
session_start();
if (!isset($_SESSION['user'])) {
    header("Location: login.php");
    exit();
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Aéroport de Goma - Accueil</title>

    <style>
        body {
            margin: 0;
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

        nav {
            background: #222;
            padding: 12px;
            text-align: center;
        }

        nav a {
            color: white;
            text-decoration: none;
            margin: 8px;
            padding: 10px 15px;
            background: #444;
            border-radius: 5px;
            transition: 0.3s;
        }

        nav a:hover {
            background: #007BFF;
        }

        .container {
            padding: 40px;
            text-align: center;
        }

        .card {
            display: inline-block;
            width: 260px;
            margin: 15px;
            padding: 20px;
            background: white;
            border-radius: 12px;
            box-shadow: 0 4px 10px rgba(0,0,0,0.1);
            transition: 0.3s;
        }

        .card:hover {
            transform: scale(1.05);
        }

        .card h3 {
            margin-bottom: 10px;
        }

        .card p {
            font-size: 14px;
            color: #555;
        }

        .card a {
            display: block;
            margin-top: 10px;
            padding: 10px;
            background: #007BFF;
            color: white;
            text-decoration: none;
            border-radius: 6px;
        }

        .card a:hover {
            background: #0056b3;
        }

        footer {
            background: #222;
            color: white;
            text-align: center;
            padding: 12px;
            margin-top: 40px;
        }
        a {
              background: #222;
            color: white;
            text-align: center;
            padding: 12px;
            margin-top: 40px;
            font-size: 19px;
        }
  
    
    </style>
</head>

<body>

<header>
    <h1>✈️ Aéroport de Goma</h1>
    <div class="user-info">
        Bienvenue <strong><?= $_SESSION['user']; ?></strong> |
        Rôle : <strong><?= $_SESSION['role']; ?></strong>
    </div>
</header>

<nav>
    <a href="index.php">Accueil</a>
    <a href="utilisateur/liste.php">Utilisateurs</a>
    <a href="destination/liste.php">Destinations</a>
    <a href="vol/liste.php">Réservations</a>
    <a href="vol/reserver.php">Réserver un vol</a>
     <a href="billets/liste.php">Billets</a>
     <a href="login.php"><button>Retour a la connexion</button></a>
   
</nav>

<div class="container">

    <div class="card">
        <h3>👤 Gestion Utilisateurs</h3>
        <p>Créer, modifier et supprimer les comptes</p>
        <a href="utilisateur/liste.php">Accéder</a>
    </div>

    <div class="card">
        <h3>🌍 Gestion Destinations</h3>
        <p>Ajouter et gérer les villes et pays</p>
        <a href="destination/liste.php">Accéder</a>
    </div>

    <div class="card">
        <h3>✈️ Réservations</h3>
        <p>Voir toutes les réservations</p>
        <a href="vol/liste.php">Accéder</a>
    </div>

    <div class="card">
        <h3>🛫 Réserver un vol</h3>
        <p>Créer une nouvelle réservation</p>
        <a href="vol/reserver.php">Réserver</a>
    </div>
    <div class="card"> 
        <h3>✈️ billets</h3>
        <p>Voir toutes les billets</p>
        <a href="billets/liste.php">Accéder</a>
    </div>

</div>
 
<footer>
    © 2026 Aéroport de Goma | Système de gestion des vols
    NURU,NDEKO,NINELLE,SALAMA, Souhaitez la Bienvenue a tous
</footer>

</body>
</html>


