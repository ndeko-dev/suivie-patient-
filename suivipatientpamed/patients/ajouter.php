<?php
include("../config.php");

if (isset($_POST['save'])) {

    // =========================
    // DONNÉES PATIENT
    // =========================
    $nom = trim($_POST['nom']);
    $prenom = trim($_POST['prenom']);
    $sexe = $_POST['sexe'];
    $telephone = trim($_POST['telephone']);
    $antecedent = trim($_POST['antecedent']);

    // =========================
    // DONNÉES CHAMBRE
    // =========================
    $numero = trim($_POST['numero']);
    $type = trim($_POST['type']);
    $lit = trim($_POST['lit']);

    // =========================
    // TARIF SELON TYPE DE CHAMBRE
    // =========================
    $typeMin = strtolower($type);

    // PRIVÉE = 20$
    if ($typeMin == "privee" || $typeMin == "privée") {
        $montant = 20;
        $libelle = "Chambre Privée";
        $code_tarif = "CH-PRIVEE";
    } 
    // COMMUN = 10$
    elseif ($typeMin == "commun") {
        $montant = 10;
        $libelle = "Chambre Commun";
        $code_tarif = "CH-COMMUN";
    } 
    // AUTRES = 5$
    else {
        $montant = 5;
        $libelle = "Autre Chambre";
        $code_tarif = "CH-AUTRE";
    }

    // =========================
    // INSERTION PATIENT
    // =========================
    $stmt = $conn->prepare("
        INSERT INTO patients (nom, prenom, sexe, telephone, antecedent)
        VALUES (?, ?, ?, ?, ?)
    ");

    $stmt->bind_param("sssss", $nom, $prenom, $sexe, $telephone, $antecedent);
    $stmt->execute();

    // ID du patient ajouté
    $patient_id = $conn->insert_id;

    // =========================
    // INSERTION CHAMBRE
    // =========================
    if (!empty($numero) && !empty($type) && !empty($lit)) {

        $stmt2 = $conn->prepare("
            INSERT INTO chambres (numero, type, lit, patient_id)
            VALUES (?, ?, ?, ?)
        ");

        $stmt2->bind_param("ssii", $numero, $type, $lit, $patient_id);
        $stmt2->execute();
    }

    // =========================
    // INSERTION TARIF
    // =========================
    $check = $conn->prepare("
        SELECT id_tarif 
        FROM tarif 
        WHERE code_tarif = ?
    ");

    $check->bind_param("s", $code_tarif);
    $check->execute();
    $result = $check->get_result();

    // insérer seulement si le tarif n'existe pas déjà
    if ($result->num_rows == 0) {

        $description = "Tarif journalier de la chambre";
        $categorie = "chambre";
        $devise = "USD";
        $unite = "jour";
        $statut = "actif";

        $stmt3 = $conn->prepare("
            INSERT INTO tarif
            (
                code_tarif,
                libelle,
                categorie,
                description,
                montant,
                devise,
                unite,
                statut
            )
            VALUES (?, ?, ?, ?, ?, ?, ?, ?)
        ");

        $stmt3->bind_param(
            "ssssdsss",
            $code_tarif,
            $libelle,
            $categorie,
            $description,
            $montant,
            $devise,
            $unite,
            $statut
        );

        $stmt3->execute();
    }

    header("Location: liste.php");
    exit();
}
?>

<!DOCTYPE html>
<html>
<head>
<title>Ajouter Patient + Chambre + Tarif</title>

<style>
body {
    font-family: Arial;
    background: #f4f6f9;
}

.container {
    width: 550px;
    margin: 40px auto;
    background: white;
    padding: 30px;
    border-radius: 10px;
    box-shadow: 0px 0px 10px #ccc;
}

h2, h3 {
    text-align: center;
}

input, select, textarea {
    width: 100%;
    padding: 10px;
    margin-top: 8px;
    margin-bottom: 15px;
    border: 1px solid #ccc;
    border-radius: 5px;
}

textarea {
    resize: vertical;
    height: 100px;
}

button {
    width: 100%;
    padding: 12px;
    background: green;
    color: white;
    border: none;
    border-radius: 5px;
    font-size: 16px;
    cursor: pointer;
}

button:hover {
    background: #0a7a0a;
}

.info-tarif {
    background: #f0f9ff;
    border: 1px solid #38bdf8;
    padding: 15px;
    border-radius: 8px;
    margin-bottom: 20px;
}

a {
    display: block;
    text-align: center;
    margin-top: 15px;
    text-decoration: none;
}
</style>

</head>
<body>

<div class="container">

<h2>👤 Ajouter Patient + Chambre + Tarif</h2>

<div class="info-tarif">
    <strong>Tarifs des chambres :</strong><br><br>

    🏥 Privée = <strong>20 USD / jour</strong><br>
    🏥 Commun = <strong>10 USD / jour</strong><br>
    🏥 Autres = <strong>5 USD / jour</strong>
</div>

<form method="POST">

    <label>Nom</label>
    <input type="text" name="nom" required>

    <label>Prénom</label>
    <input type="text" name="prenom" required>

    <label>Sexe</label>
    <select name="sexe" required>
        <option value="">-- Choisir --</option>
        <option value="Masculin">Masculin</option>
        <option value="Féminin">Féminin</option>
    </select>

    <label>Téléphone</label>
    <input type="text" name="telephone" required>

    <label>Antécédent Médical</label>
    <textarea
        name="antecedent"
        placeholder="Ex: Diabète, Hypertension, Allergies..."
    ></textarea>

    <hr>

    <h3>🛏 Informations Chambre</h3>

    <label>Numéro Chambre</label>
    <input type="number" name="numero" required>

    <label>Type Chambre</label>
    <select name="type" required>
        <option value="">-- Choisir --</option>
        <option value="Privee">Privée (20$/jour)</option>
        <option value="Commun">Commun (10$/jour)</option>
        <option value="Autre">Autre (5$/jour)</option>
    </select>

    <label>Nombre de Lit</label>
    <input
        type="number"
        name="lit"
        placeholder="Ex: 1, 2, 3..."
        required
    >

    <button type="submit" name="save">
        Enregistrer
    </button>

</form>

<a href="liste.php">⬅ Retour à la liste</a>

</div>

</body>
</html>