<?php
include("../config.php");

/* =========================================
   SUPPRESSION D'UN PATIENT
   Correction avec gestion des contraintes
   ========================================= */

if (isset($_GET['id'])) {

    $id = intval($_GET['id']);

    // Supprimer d'abord les rendez-vous liés
    $conn->query("DELETE FROM rendezvous WHERE patient_id = $id");

    // Ensuite supprimer le patient
    $sql = "DELETE FROM Patients WHERE patient_id = $id";

    if ($conn->query($sql) === TRUE) {
        header("Location: liste.php");
        exit();
    } else {
        echo "Erreur lors de la suppression : " . $conn->error;
    }

} else {
    echo "ID du patient introuvable.";
}
?>