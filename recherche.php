<?php
include 'connexion.php';

// Récupérer les critères de recherche du formulaire
$marque = $_POST['marque'] ?? '';
$modele = $_POST['modele'] ?? '';
$date_debut = $_POST['date_debut'] ?? '';
$date_fin = $_POST['date_fin'] ?? '';
$avec_chauffeur = isset($_POST['avec_chauffeur']) ? 1 : 0;
$sql = "SELECT * FROM Voitures WHERE 1=1"; // 1=1 pour permettre l'ajout de conditions sans vérifier si c'est la première condition

if ($marque !== '') {
    $sql .= " AND marque LIKE '%$marque%'";
}
if ($modele !== '') {
    $sql .= " AND modele LIKE '%$modele%'";
}
// Ajouter d'autres conditions selon vos besoins, par exemple pour la disponibilité des voitures selon les dates

// Exécuter la requête SQL
$result = $conn->query($sql);


// Générez le HTML pour les résultats de la recherche
$html = '';
if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $html .= '<div class="voiture">';
        $html .= '<img src="data:image/jpeg;base64,' . base64_encode($row['photo']) . '" alt="Image de ' . $row['marque'] . ' ' . $row['modele'] . '" class="voiture-img">';
        $html .= '<h3>' . $row['marque'] . ' ' . $row['modele'] . '</h3>';
        $html .= '<a href="reservation.php?immatriculation=' . $row['immatriculation'] . '">Réserver</a>';
        $html .= '</div>';
    }
} else {
    $html = 'Aucune voiture correspondante trouvée.';
}

// Renvoyer les résultats au format HTML
echo $html;

// Fermez la connexion à la base de données
$conn->close();
?>

