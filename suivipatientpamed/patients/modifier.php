<?php
include("../config.php");

$id = $_GET['id'];

/* =========================
   RÉCUPÉRER PATIENT + CHAMBRE
========================= */

$result = $conn->query("
    SELECT 
        patients.*, 
        chambres.numero, 
        chambres.type,
        chambres.lit,
        chambres.chambre_id
    FROM patients
    LEFT JOIN chambres
        ON patients.patient_id = chambres.patient_id
    WHERE patients.patient_id = $id
");

$data = $result->fetch_assoc();

/* =========================
   SI ON CLIQUE SUR MODIFIER
========================= */

if (isset($_POST['save'])) {

    /* =========================
       DONNÉES PATIENT
    ========================= */

    $nom = trim($_POST['nom']);
    $prenom = trim($_POST['prenom']);
    $sexe = $_POST['sexe'];
    $telephone = trim($_POST['telephone']);

    // colonne correcte : antecedent
    $antecedent = trim($_POST['antecedent']);

    /* =========================
       DONNÉES CHAMBRE
    ========================= */

    $numero = trim($_POST['numero']);
    $type = trim($_POST['type']);
    $lit = trim($_POST['lit']);

    /* =========================
       TARIF SELON TYPE CHAMBRE
    ========================= */

    $typeMin = strtolower($type);

    if ($typeMin == "privee" || $typeMin == "privée") {
        $montant = 20;
        $libelle = "Chambre Privée";
        $code_tarif = "CH-PRIVEE";
    } 
    elseif ($typeMin == "commun") {
        $montant = 10;
        $libelle = "Chambre Commun";
        $code_tarif = "CH-COMMUN";
    } 
    else {
        $montant = 5;
        $libelle = "Autre Chambre";
        $code_tarif = "CH-AUTRE";
    }

    /* =========================
       MODIFIER PATIENT
    ========================= */

    $conn->query("
        UPDATE patients
        SET
            nom = '$nom',
            prenom = '$prenom',
            sexe = '$sexe',
            telephone = '$telephone',
            antecedent = '$antecedent'
        WHERE patient_id = $id
    ");

    /* =========================
       CHAMBRE : UPDATE OU INSERT
    ========================= */

    if (!empty($data['chambre_id'])) {

        // Modifier chambre existante
        $conn->query("
            UPDATE chambres
            SET
                numero = '$numero',
                type = '$type',
                lit = '$lit'
            WHERE patient_id = $id
        ");

    } else {

        // Ajouter nouvelle chambre
        if (!empty($numero) && !empty($type) && !empty($lit)) {

            $conn->query("
                INSERT INTO chambres (
                    numero,
                    type,
                    lit,
                    patient_id
                )
                VALUES (
                    '$numero',
                    '$type',
                    '$lit',
                    '$id'
                )
            ");
        }
    }

    /* =========================
       TARIF : INSERT SI ABSENT
    ========================= */

    $check = $conn->query("
        SELECT id_tarif
        FROM tarif
        WHERE code_tarif = '$code_tarif'
    ");

    if ($check->num_rows == 0) {

        $description = "Tarif journalier de la chambre";
        $categorie = "chambre";
        $devise = "USD";
        $unite = "jour";
        $statut = "actif";

        $conn->query("
            INSERT INTO tarif (
                code_tarif,
                libelle,
                categorie,
                description,
                montant,
                devise,
                unite,
                statut
            )
            VALUES (
                '$code_tarif',
                '$libelle',
                '$categorie',
                '$description',
                '$montant',
                '$devise',
                '$unite',
                '$statut'
            )
        ");
    }

    header("Location: liste.php");
    exit();
}
?>

<!DOCTYPE html>
<html>
<head>
<title>Modifier Patient + Chambre + Tarif</title>

<style>
body {
    font-family: Arial;
    background: #f4f4f4;
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
    margin: 10px 0;
    padding: 10px;
    border: 1px solid #ccc;
    border-radius: 5px;
    box-sizing: border-box;
}

textarea {
    height: 100px;
    resize: vertical;
}

button {
    background: blue;
    color: white;
    padding: 12px;
    width: 100%;
    border: none;
    border-radius: 5px;
    cursor: pointer;
}

button:hover {
    background: #0b5ed7;
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

<h2>Modifier Patient + Chambre + Tarif</h2>

<div class="info-tarif">
    <strong>Tarifs des chambres :</strong><br><br>

    🏥 Privée = <strong>20 USD / jour</strong><br>
    🏥 Commun = <strong>10 USD / jour</strong><br>
    🏥 Autres = <strong>5 USD / jour</strong>
</div>

<form method="POST">

    <label>Nom</label>
    <input
        type="text"
        name="nom"
        value="<?= $data['nom']; ?>"
        required
    >

    <label>Prénom</label>
    <input
        type="text"
        name="prenom"
        value="<?= $data['prenom']; ?>"
        required
    >

    <label>Sexe</label>
    <select name="sexe" required>

        <option value="Masculin"
            <?= ($data['sexe'] == "Masculin") ? "selected" : ""; ?>>
            Masculin
        </option>

        <option value="Féminin"
            <?= ($data['sexe'] == "Féminin") ? "selected" : ""; ?>>
            Féminin
        </option>

    </select>

    <label>Téléphone</label>
    <input
        type="text"
        name="telephone"
        value="<?= $data['telephone']; ?>"
    >

    <label>Antécédent Médical</label>
    <textarea
        name="antecedent"
        placeholder="Ex: diabète, hypertension, chirurgie..."
    ><?= $data['antecedent']; ?></textarea>

    <hr>

    <h3>🛏 Informations Chambre</h3>

    <label>Numéro Chambre</label>
    <input
        type="number"
        name="numero"
        value="<?= $data['numero']; ?>"
    >

    <label>Type Chambre</label>
    <select name="type" required>
        <option value="Privee" <?= ($data['type'] == "Privee" || $data['type'] == "Privée") ? "selected" : ""; ?>>
            Privée (20$/jour)
        </option>

        <option value="Commun" <?= ($data['type'] == "Commun") ? "selected" : ""; ?>>
            Commun (10$/jour)
        </option>

        <option value="Autre" <?= ($data['type'] == "Autre") ? "selected" : ""; ?>>
            Autre (5$/jour)
        </option>
    </select>

    <label>Nombre de Lit</label>
    <input
        type="number"
        name="lit"
        value="<?= $data['lit']; ?>"
        placeholder="Ex: 1, 2, 3..."
    >

    <button name="save">
        Modifier
    </button>

    <a href="liste.php">
        ⬅ Retour
    </a>

</form>

</div>

</body>
</html>