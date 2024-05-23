<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Agence de location de voitures</title>
    <link rel="stylesheet" href="acceuil.css">
</head>
<body>
    <header>
        <div class="logo">
            <img src="images/logo2.png" alt="Logo de l'agence de location de voitures">
        </div>
        <nav>
            <ul>
                <li><a href="#accueil">Accueil</a></li>
                <li><a href="#voitures">Voitures</a></li>
                <li><a href="#a-propos">À propos de nous</a></li>
                <li><a href="mes_reservations.php">Voir mes réservations</a></li> 
                <li><a href="#commentaires">Commentaires</a></li>
                <li><a href="#contact">Contactez-nous</a></li>
                <li><a href="deconnexion.php">Déconnexion</a></li>
            </ul>
        </nav>
    </header>

    <section id="accueil" class="section">
        <h2>Accueil</h2>
        <form id="search-form" action="recherche.php" method="post" >
            <h3>Recherchez votre voiture</h3>
            <input type="text" name="marque" placeholder="Marque">
            <input type="text" name="modele" placeholder="Modèle">
            <input type="date" name="date_debut" placeholder="Date de début">
            <input type="date" name="date_fin" placeholder="Date de fin">
            <label for="avec_chauffeur">Avec chauffeur :</label>
            <input type="checkbox" name="avec_chauffeur" id="avec_chauffeur">
            <button type="submit">Rechercher</button>
        </form>
    </section>

    <section id="voitures" class="section">
        <h2>Voitures disponibles</h2>
        <div class="voiture-container">
            <?php
            include 'connexion.php';

            $sql = "SELECT immatriculation, marque, modele, photo FROM Voitures";
            $result = $conn->query($sql);

            if ($result->num_rows > 0) {
                while ($row = $result->fetch_assoc()) {
                    echo '<div class="voiture">';
                    echo '<img src="data:image/jpeg;base64,' . base64_encode($row['photo']) . '" alt="Image de ' . $row['marque'] . ' ' . $row['modele'] . '" class="voiture-img">';
                    echo '<h3>' . $row['marque'] . ' ' . $row['modele'] . '</h3>';
                    echo '<a href="reservation.php?immatriculation=' . $row['immatriculation'] . '">Réserver</a>';
                    echo '</div>';
                }
            } else {
                echo 'Aucune voiture disponible.';
            }

            $conn->close();
            ?>
        </div>
        <script>
document.addEventListener('DOMContentLoaded', function() {
    document.getElementById('search-form').addEventListener('submit', function(event) {
        event.preventDefault(); // Empêche le formulaire de se soumettre normalement

        // Récupère les données du formulaire
        var formData = new FormData(this);

        // Envoie les données du formulaire à recherche.php via AJAX
        var xhr = new XMLHttpRequest();
        xhr.open('POST', 'recherche.php', true);
        xhr.onload = function() {
            if (xhr.status === 200) {
                // Met à jour la section des voitures disponibles avec les résultats de la recherche
                document.getElementById('voiture-container').innerHTML = xhr.responseText;
            } else {
                console.error('Une erreur s\'est produite lors de la recherche.');
            }
        };
        xhr.send(formData);
    });
});
</script>
    </section>

    <section id="a-propos" class="section">
        <h2>À propos de nous</h2>
        <div class="propos-container">
            <div class="image-text">
                <img src="images/damage.png" alt="Image 1">
                <p>En cas de dégât, le client payera la réparation du véhicule. Il sera remboursé une fois l'agence remboursée.</p>
            </div>
            <div class="image-text">
                <img src="images/car.png" alt="Image 2">
                <p>Notre agence offre les meilleures voitures de différents types afin de satisfaire tous vos besoins.</p>
            </div>
            <div class="image-text">
                <img src="images/100-percent.png" alt="Image 3">
                <p>Vos locations seront 100 % assurées, en toute sécurité et confidentialité.</p>
            </div>
        </div>
    </section>

    <section id="commentaires" class="section">
        <h2>Commentaires</h2>
        <div class="commentaire-container">
            <!-- Contenu des commentaires -->
        </div>
    </section>

    <section id="contact" class="section">
    <h2>Contactez-nous</h2>
    <form action="envoyer_message.php" method="post" class="contact-form">
        <label for="nom">Nom:</label>
        <input type="text" name="nom" id="nom" required>

        <label for="email">Email:</label>
        <input type="email" name="email" id="email" required>

        <label for="message">Message:</label>
        <textarea name="message" id="message" required></textarea>

        <button type="submit">Envoyer</button>
    </form>
</section>


    <footer>
        <p>&copy; 2024 Agence de location</p>
    </footer>

    <script src="script.js"></script>
</body>
</html>
