<?php
// Connexion à la base de données
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "locationauto";
$conn = new mysqli($servername, $username, $password, $dbname);

// Vérifier la connexion
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Récupérer les données du formulaire
$nom = $_POST["nom"];
$prenom = $_POST["prenom"];
$telephone = $_POST["telephone"];
$voiture = $_POST["voiture"];

// Préparer la requête SQL
$sql = "INSERT INTO Chauffeurs (nom, prenom, telephone, voiture) 
        VALUES ('$nom', '$prenom', '$telephone', '$voiture')";

// Exécuter la requête SQL
if ($conn->query($sql) === TRUE) {
    echo "Chauffeur ajouté avec succès.";
    header("Location: gestion.php");
} else {
    echo "Erreur lors de l'ajout du chauffeur: " . $conn->error;
}

// Fermer la connexion à la base de données
$conn->close();
?>
