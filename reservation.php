<?php
session_start();
include 'connexion.php';

// Vérifiez si l'utilisateur est connecté
if (!isset($_SESSION['N_client'])) {
    header('Location: login.php');
    exit;
}

// Récupération de l'immatriculation de la voiture depuis l'URL
$immatriculation = $_GET['immatriculation'] ?? '';

// Requête pour récupérer les informations de la voiture
$sql = "SELECT immatriculation, marque, modele, type, prix_loc_jours FROM Voitures WHERE immatriculation = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param('s', $immatriculation);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows > 0) {
    $voiture = $result->fetch_assoc();
} else {
    echo 'Voiture non trouvée.';
    exit;
}

// Récupération des informations de l'utilisateur connecté
$N_client = $_SESSION['N_client'];
$sql_user = "SELECT nom FROM Clients WHERE N_client = ?";
$stmt_user = $conn->prepare($sql_user);
$stmt_user->bind_param('i', $N_client);
$stmt_user->execute();
$result_user = $stmt_user->get_result();
$user = $result_user->fetch_assoc();

$conn->close();
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Réservation de voiture</title>
    <link rel="stylesheet" href="acceuil.css">
    <link rel="stylesheet" href="reservation.css">
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const dateDebutInput = document.getElementById('date_debut');
            const dateFinInput = document.getElementById('date_fin');
            const prixTotalElem = document.getElementById('prix_total');
            const prixParJour = <?php echo $voiture['prix_loc_jours']; ?>;

            function calculateTotalPrice() {
                const dateDebut = new Date(dateDebutInput.value);
                const dateFin = new Date(dateFinInput.value);

                if (dateDebut && dateFin && dateFin > dateDebut) {
                    const timeDiff = dateFin - dateDebut;
                    const daysDiff = timeDiff / (1000 * 3600 * 24) + 1; // Inclure le dernier jour
                    const totalPrice = daysDiff * prixParJour;
                    prixTotalElem.textContent = totalPrice + ' DZD';
                } else {
                    prixTotalElem.textContent = '0 DZD';
                }
            }

            dateDebutInput.addEventListener('change', calculateTotalPrice);
            dateFinInput.addEventListener('change', calculateTotalPrice);
        });
    </script>
</head>
<body>
    <header>
        <div class="logo">
            <img src="images/logo2.png" alt="Logo de l'agence de location de voitures">
        </div>
        <nav>
            <ul>
                <li><a href="acceuil.php">Accueil</a></li>
                <li><a href="acceuil.php#voitures">Voitures</a></li>
                <li><a href="acceuil.php#a-propos">À propos de nous</a></li>
                <li><a href="acceuil.php#commentaires">Commentaires</a></li>
                <li><a href="acceuil.php#contact">Contactez-nous</a></li>
                <li><a href="logout.php">Déconnexion</a></li>
            </ul>
        </nav>
    </header>

    <section id="reservation" class="section">
        <h2>Réservation de la voiture</h2>
        <div class="voiture-details">
            <h3><?php echo htmlspecialchars($voiture['marque'] . ' ' . $voiture['modele'], ENT_QUOTES, 'UTF-8'); ?></h3>
            <p>Type: <?php echo htmlspecialchars($voiture['type'], ENT_QUOTES, 'UTF-8'); ?></p>
            <p>Prix par jour: <?php echo htmlspecialchars($voiture['prix_loc_jours'], ENT_QUOTES, 'UTF-8'); ?> DZD</p>
        </div>
        <form action="process_reservation.php" method="POST">
            <input type="hidden" name="immatriculation" value="<?php echo htmlspecialchars($voiture['immatriculation'], ENT_QUOTES, 'UTF-8'); ?>">
            <label for="nom">Nom:</label>
            <input type="text" name="nom" id="nom" value="<?php echo htmlspecialchars($user['nom'], ENT_QUOTES, 'UTF-8'); ?>" readonly>
            <label for="date_debut">Date de début:</label>
            <input type="date" name="date_debut" id="date_debut" required>
            <label for="date_fin">Date de fin:</label>
            <input type="date" name="date_fin" id="date_fin" required>
            <label for="kilometrage">Kilométrage prévu:</label>
            <input type="number" name="kilometrage" id="kilometrage" required>
            <p>Prix total: <span id="prix_total">0 DZD</span></p>
            <button type="submit">Réserver</button>
        </form>
    </section>

    <footer>
        <p>&copy; 2024 Agence de location</p>
    </footer>
</body>
</html>
