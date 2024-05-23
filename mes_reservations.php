<?php
session_start();
include 'connexion.php';

// Vérifiez si l'utilisateur est connecté
if (!isset($_SESSION['N_client'])) {
    header('Location: login.php');
    exit;
}

// Récupération de l'ID utilisateur
$N_client = $_SESSION['N_client'];

// Récupération des réservations de l'utilisateur connecté
$sql_reservations = "SELECT Contrats.*, Voitures.photo AS voiture_photo FROM Contrats INNER JOIN Voitures ON Contrats.immatriculation = Voitures.immatriculation WHERE Contrats.N_client = ?";
$stmt_reservations = $conn->prepare($sql_reservations);
$stmt_reservations->bind_param('i', $N_client);
$stmt_reservations->execute();
$result_reservations = $stmt_reservations->get_result();

// Fermez la connexion
$stmt_reservations->close();
$conn->close();
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Mes Réservations</title>
    <link rel="stylesheet" href="mesreservations.css">
</head>
<body>
    <header>
        <div class="logo">
            <img src="images/logo2.png" alt="Logo de l'agence de location de voitures">
        </div>
        <nav>
            <ul>
                <li><a href="acceuil.php">Accueil</a></li>
                <li><a href="#">Mes Réservations</a></li>
            </ul>
        </nav>
    </header>

    <section id="reservations" class="section">
        <h2>Mes Réservations</h2>
        <div class="reservation-container">
            <?php
            if ($result_reservations->num_rows > 0) {
                while ($row = $result_reservations->fetch_assoc()) {
                    echo '<div class="reservation">';
                    echo '<img src="data:image/jpeg;base64,' . base64_encode($row['voiture_photo']) . '" alt="' . $row['immatriculation'] . '">';
                    echo '<p>Immatriculation: ' . $row['immatriculation'] . '</p>';
                    echo '<p>Date de début: ' . $row['date_debut'] . '</p>';
                    echo '<p>Date de fin: ' . $row['date_fin'] . '</p>';
                    echo '<p>Prix total: ' . $row['prix_total'] . ' DZD</p>';
                    echo '<button class="prolonger-btn">Demander une prolongation</button>';
                    echo '</div>';
                }
            } else {
                echo '<p>Aucune réservation trouvée.</p>';
            }
            ?>
        </div>
    </section>

    <footer>
        <p>&copy; 2024 Agence de location</p>
    </footer>
</body>
</html>
