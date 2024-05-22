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

if (isset($_POST['ajouter_voiture'])) {
    // Récupérer les données du formulaire
    $immatriculation = $_POST["immatriculation"];
    $type = $_POST["type"];
    $marque = $_POST["marque"];
    $modele = $_POST["modele"];
    $kilometrage = $_POST["kilometrage"];
    $prix_loc_jours = $_POST["prix_loc_jours"];
    $chauffeur = $_POST["chauffeur"];
    $nom_fichier = $_FILES["photo_voiture"]["name"];

    if (!empty($_FILES["photo_voiture"]["tmp_name"][$i])) {
    #Si la photo est soumise
    $donnees_image = file_get_contents($_FILES["photo_voiture"]["tmp_name"]);
    $contentFileBase64 = base64_encode($donnees_image);
    // Préparer la requête SQL avec l'image
    $sql = "INSERT INTO Voitures (immatriculation, type, marque, modele, kilometrage, prix_loc_jours, chauffeur, photo) 
            VALUES ('$immatriculation', '$type', '$marque', '$modele', '$kilometrage', '$prix_loc_jours', '$chauffeur', '$contentFileBase64')";

    // Exécuter la requête SQL
    if ($conn->query($sql) === TRUE) {
        echo "La voiture a été ajoutée avec succès.";
        header("Location: gestion.php");
    } else {
        echo "Erreur lors de l'ajout de la voiture: " . $conn->error;
    }
}else {
    #Pas de photo
    $sql = "INSERT INTO Voitures (immatriculation, type, marque, modele, kilometrage, prix_loc_jours, chauffeur) 
            VALUES ('$immatriculation', '$type', '$marque', '$modele', '$kilometrage', '$prix_loc_jours', '$chauffeur')";
    // Exécuter la requête SQL
    if ($conn->query($sql) === TRUE) {
        echo "La voiture a été ajoutée avec succès.";
        header("Location: gestion.php");
    } else {
        echo "Erreur lors de l'ajout de la voiture: " . $conn->error;
    }
}
    // Fermer la connexion à la base de données
    $conn->close();
}