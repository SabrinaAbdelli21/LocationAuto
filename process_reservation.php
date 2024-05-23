<?php
session_start();
include 'connexion.php';

if (!isset($_SESSION['N_client'])) {
    header('Location: login.php');
    exit;
}

// Récupération des données du formulaire
$immatriculation = $_POST['immatriculation'] ?? '';
$date_debut = $_POST['date_debut'] ?? '';
$date_fin = $_POST['date_fin'] ?? '';

// Vérifier si la voiture est déjà réservée pour la période spécifiée
$sql_check_reservation = "SELECT * FROM Contrats WHERE immatriculation = ? AND (date_debut BETWEEN ? AND ? OR date_fin BETWEEN ? AND ?)";
$stmt_check_reservation = $conn->prepare($sql_check_reservation);
$stmt_check_reservation->bind_param('sssss', $immatriculation, $date_debut, $date_fin, $date_debut, $date_fin);
$stmt_check_reservation->execute();
$result_check_reservation = $stmt_check_reservation->get_result();

if ($result_check_reservation->num_rows > 0) {
    echo 'Cette voiture est déjà réservée pour la période spécifiée. Veuillez choisir une autre période ou une autre voiture.';
} else {
    // Récupération des informations de l'utilisateur connecté
    $user_id = $_SESSION['user_id'];

    // Calcul du prix total (à remplacer par votre propre logique de calcul)
    // Supposons que le prix est de 1000 DZD par jour
    $prix_par_jour = 1000;
    $datetime1 = new DateTime($date_debut);
    $datetime2 = new DateTime($date_fin);
    $interval = $datetime1->diff($datetime2);
    $jours = $interval->days + 1;
    $prix_total = $prix_par_jour * $jours;

    // Insertion de la réservation dans la base de données
    $sql_insert_reservation = "INSERT INTO Contrats (N_client, immatriculation, date_debut, date_fin, prix_total) VALUES (?, ?, ?, ?, ?)";
    $stmt_insert_reservation = $conn->prepare($sql_insert_reservation);
    $stmt_insert_reservation->bind_param('isssd', $user_id, $immatriculation, $date_debut, $date_fin, $prix_total);
    if ($stmt_insert_reservation->execute()) {
        echo 'Réservation effectuée avec succès!';
    } else {
        echo 'Erreur lors de la réservation.';
    }
}

$conn->close();
header('Location: confirmation.php');
exit;
?>
?>
