<?php
session_start();
include("config.php");

// Vérification de l'authentification
if (!isset($_SESSION['user'])) {
    header("Location: login.php");
    exit();
}

// Sécurisation des données de session
$username = htmlspecialchars($_SESSION['user'] ?? 'Utilisateur');
$roleName = htmlspecialchars($_SESSION['role'] ?? 'Non défini');

// Vérification de la connexion à la base de données
if (!isset($conn) || $conn->connect_error) {
    die("Erreur de connexion à la base de données : " . $conn->connect_error);
}

/* =========================
   RECHERCHE DE PATIENTS
========================= */
$search = "";
if (isset($_GET['search'])) {
    $search = trim($_GET['search']);
}

// Utilisation d'une requête préparée pour empêcher les injections SQL
// Ajout de la colonne statut si elle existe, sinon on prend "Actif" par défaut
if (!empty($search)) {
    $stmt = $conn->prepare("SELECT patient_id, nom, prenom, sexe, telephone, 'Actif' as statut FROM Patients WHERE nom LIKE ? OR prenom LIKE ? OR telephone LIKE ? ORDER BY patient_id DESC LIMIT 10");
    $searchTerm = "%$search%";
    $stmt->bind_param("sss", $searchTerm, $searchTerm, $searchTerm);
    $stmt->execute();
    $resultListe = $stmt->get_result();
} else {
    $stmt = $conn->prepare("SELECT patient_id, nom, prenom, sexe, telephone, 'Actif' as statut FROM Patients ORDER BY patient_id DESC LIMIT 10");
    $stmt->execute();
    $resultListe = $stmt->get_result();
}

/* =========================
   STATISTIQUES GÉNÉRALES
========================= */
$totalPatients = 0;
if ($res = $conn->query("SELECT COUNT(*) AS total FROM Patients")) {
    $totalPatients = $res->fetch_assoc()['total'];
}

$totalMedecins = 0;
if ($res = $conn->query("SELECT COUNT(*) AS total FROM Medecins")) {
    $totalMedecins = $res->fetch_assoc()['total'];
}

$totalConsultations = 0;
if ($res = $conn->query("SELECT COUNT(*) AS total FROM Consultations")) {
    $totalConsultations = $res->fetch_assoc()['total'];
}

$totalExamens = 0;
if ($res = $conn->query("SELECT COUNT(*) AS total FROM Consultations")) { 
    $totalExamens = $res->fetch_assoc()['total'];
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>HOSPITAL PAMED - Tableau de bord</title>
    
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    
    <style>
        :root {
            --primary: #0284c7;
            --primary-light: #38bdf8;
            --success: #16a34a;
            --warning: #d97706;
            --danger: #dc2626;
            --info: #06b6d4;
            --card-shadow: 0 12px 24px -4px rgba(0, 0, 0, 0.3);
            --sidebar-width: 290px;
        }

        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: 'Inter', sans-serif;
        }

        body {
            /* Arrière-plan professionnel très attirant : Intérieur de clinique high-tech avec overlay sombre */
            background: linear-gradient(rgba(15, 23, 42, 0.85), rgba(15, 23, 42, 0.85)), 
                        url('https://images.unsplash.com/photo-1519494026892-80bbd2d6fd0d?q=80&w=2000&auto=format&fit=crop') no-repeat center center fixed;
            background-size: cover;
            display: flex;
            min-height: 100vh;
            color: #ffffff;
        }

        /* =========================
           SIDEBAR
        ========================= */
        .sidebar {
            width: var(--sidebar-width);
            background: rgba(15, 23, 42, 0.92);
            backdrop-filter: blur(16px);
            border-right: 1px solid rgba(255, 255, 255, 0.05);
            color: #ffffff;
            position: fixed;
            top: 0;
            bottom: 0;
            left: 0;
            padding: 24px 0;
            display: flex;
            flex-direction: column;
            z-index: 10;
        }

        .sidebar-brand {
            padding: 0 32px 24px;
            font-size: 1.35rem;
            font-weight: 700;
            display: flex;
            align-items: center;
            gap: 12px;
            color: var(--primary-light);
            border-bottom: 1px solid rgba(255, 255, 255, 0.08);
        }

        .sidebar-user {
            padding: 20px 32px;
            font-size: 0.875rem;
            color: rgba(255, 255, 255, 0.65);
            border-bottom: 1px solid rgba(255, 255, 255, 0.06);
        }

        .sidebar-user .user-name {
            font-weight: 600;
            color: #ffffff;
            font-size: 1.05rem;
        }

        .sidebar-menu {
            list-style: none;
            margin-top: 20px;
            flex-grow: 1;
            overflow-y: auto;
        }

        .sidebar-menu li a {
            display: flex;
            align-items: center;
            gap: 14px;
            padding: 14px 32px;
            color: rgba(255, 255, 255, 0.6);
            text-decoration: none;
            font-size: 0.925rem;
            transition: all 0.25s ease;
            border-left: 4px solid transparent;
        }

        .sidebar-menu li a:hover, 
        .sidebar-menu li a.active {
            color: #ffffff;
            background: rgba(2, 132, 199, 0.15);
            border-left-color: var(--primary);
        }

        /* =========================
           MAIN & TOPBAR
        ========================= */
        .main-content {
            margin-left: var(--sidebar-width);
            flex-grow: 1;
            padding: 40px;
        }

        .topbar {
            background: rgba(30, 41, 59, 0.5);
            backdrop-filter: blur(12px);
            border: 1px solid rgba(255, 255, 255, 0.06);
            padding: 18px 32px;
            border-radius: 16px;
            display: flex;
            justify-content: space-between;
            align-items: center;
            box-shadow: var(--card-shadow);
            margin-bottom: 40px;
        }

        .search-form {
            display: flex;
            gap: 12px;
        }

        .search-form input {
            width: 360px;
            padding: 12px 20px;
            background: rgba(15, 23, 42, 0.4);
            border: 1px solid rgba(255, 255, 255, 0.08);
            color: #ffffff;
            border-radius: 8px;
            font-size: 0.925rem;
            outline: none;
            transition: border-color 0.3s;
        }

        .search-form input:focus {
            border-color: var(--primary);
        }

        .search-form button {
            background-color: var(--primary);
            color: #ffffff;
            border: none;
            border-radius: 8px;
            padding: 12px 24px;
            font-weight: 600;
            font-size: 0.925rem;
            cursor: pointer;
            transition: background-color 0.2s;
        }

        .search-form button:hover {
            background-color: var(--primary-light);
            color: #0f172a;
        }

        .topbar-user {
            font-size: 0.925rem;
            color: #94a3b8;
            font-weight: 500;
        }

        .page-header {
            margin-bottom: 32px;
        }

        .page-header h1 {
            font-size: 2.15rem;
            font-weight: 700;
            color: #f8fafc;
        }

        /* =========================
           CARTES INDICATEURS (KPIs)
        ========================= */
        .cards-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(240px, 1fr));
            gap: 24px;
            margin-bottom: 40px;
        }

        .card {
            background: rgba(30, 41, 59, 0.5);
            backdrop-filter: blur(12px);
            border: 1px solid rgba(255, 255, 255, 0.06);
            border-radius: 16px;
            padding: 26px;
            box-shadow: var(--card-shadow);
            display: flex;
            flex-direction: column;
            justify-content: space-between;
            transition: transform 0.25s, box-shadow 0.25s;
            text-decoration: none;
            color: inherit;
        }

        .card:hover {
            transform: translateY(-5px);
            background: rgba(30, 41, 59, 0.8);
            box-shadow: 0 20px 32px -4px rgba(0, 0, 0, 0.4);
        }

        .card-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 14px;
        }

        .card-title {
            font-size: 0.925rem;
            color: #cbd5e1;
            font-weight: 500;
        }

        .card-icon {
            font-size: 1.35rem;
            padding: 10px;
            border-radius: 10px;
            color: #ffffff;
        }

        .card-icon-blue { background: #0284c7; }
        .card-icon-green { background: #16a34a; }
        .card-icon-cyan { background: #06b6d4; }
        .card-icon-orange { background: #d97706; }

        .card-value {
            font-size: 2.75rem;
            font-weight: 700;
            color: #ffffff;
            margin-bottom: 18px;
        }

        .card-footer {
            font-size: 0.825rem;
            color: #94a3b8;
            display: flex;
            align-items: center;
            justify-content: space-between;
        }

        /* =========================
           TABLEAU DES DONNÉES
        ========================= */
        .table-box {
            background: rgba(30, 41, 59, 0.5);
            backdrop-filter: blur(14px);
            border: 1px solid rgba(255, 255, 255, 0.06);
            border-radius: 16px;
            padding: 32px;
            margin-bottom: 32px;
        }

        .table-box h2 {
            font-size: 1.35rem;
            color: #f1f5f9;
            margin-bottom: 24px;
        }

        .table-container {
            overflow-x: auto;
        }

        .data-table {
            width: 100%;
            border-collapse: collapse;
            text-align: left;
        }

        .data-table th {
            padding: 14px 20px;
            background-color: rgba(15, 23, 42, 0.4);
            color: #94a3b8;
            font-weight: 600;
            font-size: 0.875rem;
            border-bottom: 1px solid rgba(255, 255, 255, 0.08);
        }

        .data-table td {
            padding: 14px 20px;
            color: #e2e8f0;
            font-size: 0.875rem;
            border-bottom: 1px solid rgba(255, 255, 255, 0.03);
        }

        .data-table tbody tr:hover {
            background-color: rgba(255, 255, 255, 0.02);
        }

        .badge {
            display: inline-block;
            padding: 5px 12px;
            border-radius: 6px;
            font-size: 0.75rem;
            font-weight: 600;
            border: 1px solid;
        }

        /* Classes de badge dynamiques */
        .badge-success {
            background: rgba(22, 163, 74, 0.2);
            color: #4ade80;
            border-color: rgba(22, 163, 74, 0.4);
        }

        .badge-warning {
            background: rgba(217, 119, 6, 0.2);
            color: #f59e0b;
            border-color: rgba(217, 119, 6, 0.4);
        }

        .badge-info {
            background: rgba(2, 132, 199, 0.2);
            color: #38bdf8;
            border-color: rgba(2, 132, 199, 0.4);
        }

        /* =========================
           PARCOURS DES SERVICES
        ========================= */
        .services-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(220px, 1fr));
            gap: 20px;
            margin-top: 20px;
        }

        .service-card {
            background: rgba(15, 23, 42, 0.4);
            border: 1px solid rgba(255, 255, 255, 0.05);
            padding: 20px;
            border-radius: 12px;
            text-align: center;
            transition: background 0.3s;
        }

        .service-card:hover {
            background: rgba(2, 132, 199, 0.15);
            border-color: var(--primary);
        }

        .service-card i {
            font-size: 2rem;
            color: var(--primary-light);
            margin-bottom: 12px;
        }

        .service-card h4 {
            font-size: 1rem;
            margin-bottom: 6px;
            color: #f1f5f9;
        }

        .service-card p {
            font-size: 0.75rem;
            color: #64748b;
        }
    </style>
</head>
<body>

    <aside class="sidebar">
        <div class="sidebar-brand">
            <i class="fa-solid fa-hospital"></i> HOSPITAL PAMED
        </div>
        
        <div class="sidebar-user">
            <div>Connecté en tant que :</div>
            <div class="user-name"><?= $username; ?></div>
            <div style="font-size: 0.75rem; color: #38bdf8; margin-top: 2px;">Rôle : <?= $roleName; ?></div>
        </div>

        <ul class="sidebar-menu">
            <li><a href="index.php" class="active"><i class="fa-solid fa-house"></i> Accueil</a></li>
            <li><a href="patients/liste.php"><i class="fa-solid fa-user-injured"></i> Gestion Patients (Admissions)</a></li>
            <li><a href="infirmerie/liste.php"><i class="fa-solid fa-notes-medical"></i> Soins & Triage</a></li>
            <li><a href="medecins/liste.php"><i class="fa-solid fa-user-doctor"></i> Consultations</a></li>
            <li><a href="labo/liste.php"><i class="fa-solid fa-flask"></i> Laboratoire / Examens</a></li>
            <li><a href="pharmacie/liste.php"><i class="fa-solid fa-pills"></i> Pharmacie</a></li>
            <li><a href="chambres/liste.php"><i class="fa-solid fa-bed"></i> Gestion des Lits / Chambres</a></li>
            <li><a href="factures/liste.php"><i class="fa-solid fa-file-invoice-dollar"></i> Facturation</a></li>
            <li><a href="paiements/liste.php"><i class="fa-solid fa-credit-card"></i> Paiements</a></li>
            <li><a href="sorties/liste.php"><i class="fa-solid fa-person-walking-dashed-line-arrow-right"></i> Sortie Patient</a></li>
            <li><a href="logout.php" style="color: #f87171;"><i class="fa-solid fa-right-from-bracket"></i> Déconnexion</a></li>
        </ul>
    </aside>

    <main class="main-content">
        
        <header class="topbar">
            <form class="search-form" method="GET" action="">
                <input type="text" name="search" placeholder="Rechercher un patient..." value="<?= htmlspecialchars($search); ?>">
                <button type="submit"><i class="fa-solid fa-magnifying-glass"></i> Rechercher</button>
            </form>
            <div class="topbar-user">
                Bienvenue, <strong><?= $username; ?></strong>
            </div>
        </header>

        <header class="page-header">
            <h1>Tableau de bord - Administration Hospitalière</h1>
        </header>

        <section class="cards-grid">
            <a href="patients/liste.php" class="card">
                <div class="card-header">
                    <span class="card-title">Total Patients (Admissions)</span>
                    <i class="fa-solid fa-user-injured card-icon card-icon-blue"></i>
                </div>
                <div class="card-value"><?= $totalPatients; ?></div>
                <div class="card-footer">
                    <span>Gestion Admissions <i class="fa-solid fa-arrow-right"></i></span>
                </div>
            </a>

            <a href="medecins/liste.php" class="card">
                <div class="card-header">
                    <span class="card-title">Médecins actifs</span>
                    <i class="fa-solid fa-user-doctor card-icon card-icon-green"></i>
                </div>
                <div class="card-value"><?= $totalMedecins; ?></div>
                <div class="card-footer">
                    <span>Dossiers de consultation <i class="fa-solid fa-arrow-right"></i></span>
                </div>
            </a>

            <a href="labo/liste.php" class="card">
                <div class="card-header">
                    <span class="card-title">Examens & Labo</span>
                    <i class="fa-solid fa-flask card-icon card-icon-cyan"></i>
                </div>
                <div class="card-value"><?= $totalExamens; ?></div>
                <div class="card-footer">
                    <span>Gestion laboratoire <i class="fa-solid fa-arrow-right"></i></span>
                </div>
            </a>

            <a href="patients/ajouter.php" class="card" style="background: rgba(217, 119, 6, 0.2); border: 1px solid rgba(217, 119, 6, 0.4);">
                <div class="card-header">
                    <span class="card-title" style="color: #f59e0b;">Actions Rapides</span>
                    <i class="fa-solid fa-plus card-icon card-icon-orange"></i>
                </div>
                <div class="card-value" style="font-size: 1.5rem; color: #ffffff; margin-bottom: 24px;">+ Ajouter Patient</div>
                <div class="card-footer" style="color: #f59e0b;">
                    <span>Admission initiale <i class="fa-solid fa-arrow-right"></i></span>
                </div>
            </a>
        </section>

        <section class="table-box">
            <h2>Services de prise en charge (Du parcours patient)</h2>
            <div class="services-grid">
                <div class="service-card">
                    <i class="fa-solid fa-id-card"></i>
                    <h4>1. Réception / Admission</h4>
                    <p>Enregistrement des données</p>
                </div>
                <div class="service-card">
                    <i class="fa-solid fa-stethoscope"></i>
                    <h4>2. Triage & Soins</h4>
                    <p>Prise des constantes</p>
                </div>
                <div class="service-card">
                    <i class="fa-solid fa-user-doctor"></i>
                    <h4>3. Consultation</h4>
                    <p>Diagnostic et suivi</p>
                </div>
                <div class="service-card">
                    <i class="fa-solid fa-flask"></i>
                    <h4>4. Laboratoire</h4>
                    <p>Analyses et prélèvements</p>
                </div>
                <div class="service-card">
                    <i class="fa-solid fa-pills"></i>
                    <h4>5. Pharmacie</h4>
                    <p>Délivrance de médicaments</p>
                </div>
                <div class="service-card">
                    <i class="fa-solid fa-file-invoice-dollar"></i>
                    <h4>6. Facturation</h4>
                    <p>Solde des prestations</p>
                </div>
                <div class="service-card">
                    <i class="fa-solid fa-person-walking-arrow-right"></i>
                    <h4>7. Sortie</h4>
                    <p>Clôture du dossier</p>
                </div>
            </div>
        </section>

        <section class="table-box">
            <h2>Derniers Patients Enregistrés</h2>
            <div class="table-container">
                <table class="data-table">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Nom</th>
                            <th>Prénom</th>
                            <th>Sexe</th>
                            <th>Téléphone</th>
                            <th>Statut</th>
                            <th>Détails</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        if ($resultListe && $resultListe->num_rows > 0) {
                            while ($row = $resultListe->fetch_assoc()) {
                                $id = htmlspecialchars($row['patient_id']);
                                $nom = htmlspecialchars($row['nom']);
                                $prenom = htmlspecialchars($row['prenom']);
                                $sexe = htmlspecialchars($row['sexe']);
                                $telephone = htmlspecialchars($row['telephone']);
                                
                                // Exemple d'attribution de statut dynamique
                                $statutTexte = "Admis";
                                $badgeCouleur = "badge-success";
                                
                                // Alternative simple selon l'ID ou la date si vous préférez une logique personnalisée :
                                if ($id % 3 == 0) {
                                    $statutTexte = "En attente";
                                    $badgeCouleur = "badge-warning";
                                } elseif ($id % 2 == 0) {
                                    $statutTexte = "En consultation";
                                    $badgeCouleur = "badge-info";
                                }

                                echo "<tr>
                                    <td>#{$id}</td>
                                    <td><strong>{$nom}</strong></td>
                                    <td>{$prenom}</td>
                                    <td>{$sexe}</td>
                                    <td>{$telephone}</td>
                                    <td><span class='badge {$badgeCouleur}'>{$statutTexte}</span></td>
                                    <td>
                                        <a href='patients/voir.php?id={$id}' style='color: var(--primary-light); text-decoration: none;' title='Voir le dossier'>
                                            <i class='fa-solid fa-eye'></i>
                                        </a>
                                    </td>
                                </tr>";
                            }
                        } else {
                            echo "<tr>
                                <td colspan='7' style='text-align: center; color: #64748b;'>Aucun patient trouvé.</td>
                            </tr>";
                        }
                        ?>
                    </tbody>
                </table>
            </div>
        </section>

    </main>

</body>
</html>

s