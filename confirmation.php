<?php
session_start();
include 'connexion.php';

// Vérifiez si l'utilisateur est connecté
if (!isset($_SESSION['N_client'])) {
    header('Location: login.php');
    exit;
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Confirmation de Réservation</title>
    <link rel="stylesheet" href="acceuil.css">
    <style>
        body {
            display: flex;
            justify-content: center;
            align-items: center;
            flex-direction: column;
            height: 100vh;
            margin: 0;
            font-family: Arial, sans-serif;
            background-color: #f4f4f4;
        }
        .confirmation-message {
            text-align: center;
            margin-bottom: 20px;
        }
        .confirmation-image img {
            max-width: 200px; /* Réduire la taille de l'image */
            height: auto;
        }
    </style>
</head>
<body>
    <div class="confirmation-message">
        <h2>Votre réservation a été confirmée avec succès !</h2>
        <p>Merci d'avoir choisi notre service de location de voitures.</p>
    </div>
    <div class="confirmation-image">
        <img src="images/confirmation.png" alt="Image de confirmation">
    </div>
</body>
</html>

