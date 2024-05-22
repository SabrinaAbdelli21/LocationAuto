<!DOCTYPE html>
<?php
session_start();
// Connexion à la base de données
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "locationauto";
$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
function supprimerApresDuree(&$variable, $dureeEnSecondes) {
    sleep($dureeEnSecondes); // Attendre la durée spécifiée
    unset($variable); // Supprimer le contenu de la variable
}
// Vérifier si l'immatriculation de la voiture à supprimer est définie dans la requête POST
if(isset($_POST['immatriculation'])) {
    $immatriculation = $_POST['immatriculation'];

    // Requête SQL pour supprimer la voiture de la table Voitures
    $sqlsupprimervoiture = "DELETE FROM Voitures WHERE immatriculation = '$immatriculation'";

    if ($conn->query($sqlsupprimervoiture) === TRUE) {
        $supprimervoiture = "La voiture a été supprimée avec succès.";
    } else {
        $supprimervoiture ="Erreur lors de la suppression de la voiture: " . $conn->error;
    }
}

// Vérifier si le bouton "Payé" a été cliqué
if(isset($_POST['paye'])) {
    // Récupérer l'identifiant du contrat
    $N_contrat = $_POST['N_contrat'];

    // Requête SQL pour mettre à jour le paiement du contrat
    $sqlUpdatePaiement = "UPDATE Contrats SET paye = 1 WHERE N_contrat = '$N_contrat'";

    // Exécuter la requête SQL
    if ($conn->query($sqlUpdatePaiement) === TRUE) {
       $paye= "Le paiement du contrat a été mis à jour avec succès.";
    } else {
        $paye="Erreur lors de la mise à jour du paiement du contrat: " . $conn->error;
    }
}

// Vérifier si le formulaire de suppression a été soumis
if(isset($_POST['supprimer_chauffeur'])) {
    // Récupérer l'ID du chauffeur à supprimer depuis le formulaire
    $Id_chauffeur = $_POST['Id_chauffeur'];

    // Requête SQL pour supprimer le chauffeur de la table Chauffeurs
    $sql_suppression_chauffeur = "DELETE FROM chauffeurs WHERE Id_chauffeur = '$Id_chauffeur'";

    // Exécuter la requête SQL
    if ($conn->query($sql_suppression_chauffeur) === TRUE) {
        $message_suppression_chauffeur = "Le chauffeur a été supprimé avec succès.";
    } else {
        $message_suppression_chauffeur = "Erreur lors de la suppression du chauffeur: " . $conn->error;
    }
}

?>

<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gestionnaire de Voiture</title>
    <link rel="stylesheet" href="styles.css">
</head>
<body>
    <nav>
        <ul>
            <li><button id="btnGestionVoitures">Gérer Voitures</button></li>
            <li><button id="btnGestionContrats">Gérer Contrats</button></li>
            <li><button id="btnGestionPaiements">Gérer Paiements</button></li>
            <li><button id="btnGestionChauffeurs">Gérer Chauffeurs</button></li>
            <li><a href="logout.php">Logout </a></li>
        </ul>
    </nav>

    <section id="sectionGestionVoitures">
        <h1>Gérer Voitures</h1>
        <button id="toggle-form-voiture">Ajouter une voiture</button>
        <form id="ajouter-formulaire-voiture"  class="ajouter-formulaire"  method="post" action="ajouter_voiture.php" enctype="multipart/form-data">
          <label for="immatriculation">Immatriculation :</label>
          <input type="text" id="immatriculation" name="immatriculation" required><br>
          
          <label for="type">Type :</label>
          <input type="text" id="type" name="type" required><br>
          
          <label for="marque">Marque :</label>
          <input type="text" id="marque" name="marque" required><br>
          
          <label for="modele">Modèle :</label>
          <input type="text" id="modele" name="modele" required><br>
          
          <label for="kilometrage">Kilométrage :</label>
          <input type="number" id="kilometrage" name="kilometrage" required><br>
          
          <label for="prix_loc_jours">Prix de location par jour :</label>
          <input type="number" id="prix_loc_jours" name="prix_loc_jours" required><br>
          
          <label for="chauffeur">Chauffeur (optionnel) :</label>
          <input type="text" id="chauffeur" name="chauffeur"><br>

          <label for="photo_voiture">Photo de la voiture :</label>
          <input type="file" id="photo_voiture" name="photo_voiture" accept="image/*"><br>
          
          <input type="submit" value="Ajouter la voiture" name="ajouter_voiture">
      </form>
      <h2>Liste des voitures</h2>
     <?php
     if(isset($supprimervoiture)) { 
        echo $supprimervoiture; 
    }
    // Récupérer la liste des utilisateurs
    $sql = "SELECT * FROM voitures";
    $voitures = mysqli_query($conn, $sql);
    if (mysqli_num_rows($voitures) > 0) {
        echo "<table>";
        echo "<tr><th>Immatriculation</th><th>Type</th><th>Marque</th><th>Modèle</th><th>Kilométrage</th><th>Prix de location par jours</th><th>Chauffeur</th><th>Disponibilité</th><th>Actions</th></tr>";
        while ($row = mysqli_fetch_assoc($voitures)) {
            echo "<tr>";
            echo "<td>" . $row['immatriculation'] . "</td>";
            echo "<td>" . $row['type'] . "</td>";
            echo "<td>" . $row['marque'] . "</td>";
            echo "<td>" . $row['modele'] . "</td>";
            echo "<td>" . $row['kilometrage'] . "</td>";
            echo "<td>" . $row['prix_loc_jours'] . "</td>";
            echo "<td>" . $row['chauffeur'] . "</td>";
            echo "<td>";     
            $sqldispovoiture = "SELECT immatriculation 
            FROM Contrats 
            WHERE immatriculation = '" . $row['immatriculation'] . "' AND date_fin < NOW()";
            $result = mysqli_query($conn, $sqldispovoiture);
            if (mysqli_num_rows($result) > 0) {
                echo "non disponible";
                } else {
                echo "disponible";
                }
            echo "</td>";
            echo "<td><form method='post' action='gestion.php' ><input type='hidden' name='immatriculation' value='" . $row['immatriculation'] . "'><input type='submit' name='supprimer_user' value='Supprimer'></form></td>";
            echo "</tr>";
        }
        echo "</table>";
    }else{
        echo "Aucune voiture trouvée.";
    }
     ?>
    </section>

    <section id="sectionGestionContrats">
        <h1>Gérer Contrats</h1>
        <h2>Liste des contrats</h2>
        <table>
        <?php
        /* a revoire :(
           if(isset($supprimercontrat)) { 
                echo $supprimercontrat; 
            }*/
    // Récupérer la liste des utilisateurs
    $sql = "SELECT * FROM contrats";
    $contrat = mysqli_query($conn, $sql);
    if (mysqli_num_rows($contrat) > 0) {
        echo "<table>";
        echo "<tr><th>N° Contrat</th><th>Client</th><th>Chauffeur</th><th>Immatriculation</th><th>Date début</th><th>Date fin</th><th>Kilométrage</th><th>Payé</th><th>Prix total</th><th>Actions</th></tr>";
        while ($row = mysqli_fetch_assoc($contrat)) {
            echo "<tr>";
            echo "<td>" . $row["N_contrat"] . "</td>";
            echo "<td>" . $row["N_client"] . "</td>";
            echo "<td>" . $row["Id_chauffeur"] . "</td>";
            echo "<td>" . $row["immatriculation"] . "</td>";
            echo "<td>" . $row["date_debut"] . "</td>";
            echo "<td>" . $row["date_fin"] . "</td>";
            echo "<td>" . $row["kilometrage"] . "</td>";
            echo "<td>" . ($row["paye"] ? "Oui" : "Non") . "</td>";
            echo "<td>" . $row["prix_total"] . "</td>";   
            echo "<td><form method='post' action='gerer_contrat.php' ><input type='hidden' name='N_contrat' value='" . $row['N_contrat'] . "'><input type='submit' name='supprimer_contrat' value='Supprimer'><input type='submit' name='generer_contrat' value='Générer contrat'></form></td>";
            echo "</tr>"; 
        }
        echo "</table>";
    }else{
        echo "Aucun contrat trouvé.";
    }
     ?>
        </table>
</section>

<section id="sectionGestionPaiements">
        <h1>Gérer Paiements</h1>
        <h2>Liste des contarts non payé</h2>
        <table>
        <?php
             if(isset($paye)) { 
                echo $paye; 
            }
    // Récupérer la liste des utilisateurs
    $sql = "SELECT * FROM contrats where paye=false";
    $contrat = mysqli_query($conn, $sql);
    if (mysqli_num_rows($contrat) > 0) {
        echo "<table>";
        echo "<tr><th>N° Contrat</th><th>Client</th><th>Chauffeur</th><th>Immatriculation</th><th>Date début</th><th>Date fin</th><th>Kilométrage</th><th>Payé</th><th>Prix total</th><th>Actions</th></tr>";
        while ($row = mysqli_fetch_assoc($contrat)) {
            echo "<tr>";
            echo "<td>" . $row["N_contrat"] . "</td>";
            echo "<td>" . $row["N_client"] . "</td>";
            echo "<td>" . $row["Id_chauffeur"] . "</td>";
            echo "<td>" . $row["immatriculation"] . "</td>";
            echo "<td>" . $row["date_debut"] . "</td>";
            echo "<td>" . $row["date_fin"] . "</td>";
            echo "<td>" . $row["kilometrage"] . "</td>";
            echo "<td>" . ($row["paye"] ? "Oui" : "Non") . "</td>";
            echo "<td>" . $row["prix_total"] . "</td>";   
            echo "<td><form method='post' action='gestion.php' ><input type='hidden' name='N_contrat' value='" . $row['N_contrat'] . "'><input type='submit' name='paye' value='Payé'></form></td>";
            echo "</tr>"; 
        }
        echo "</table>";
    }else{
        echo "Aucun contrat non payé trouvé.";
    }
     ?>
        </table>
</section>

<section id="sectionGestionChauffeurs">
        <h1>Gérer Chauffeurs</h1>
        <button id="toggle-form">Ajouter un chauffeur</button>
        <form id="ajouter-formulaire-chauffeur" class="ajouter-formulaire" action="ajouter_chauffeur.php" method="post">
        <label for="nom">Nom :</label>
        <input type="text" id="nom" name="nom" required><br>

        <label for="prenom">Prénom :</label>
        <input type="text" id="prenom" name="prenom" required><br>

        <label for="telephone">Téléphone :</label>
        <input type="text" id="telephone" name="telephone" required><br>

        <label for="voiture">Voiture (immatriculation) :</label>
        <input type="text" id="voiture" name="voiture"><br>

        <input type="submit" value="Ajouter le chauffeur">
    </form>
    <h2>Liste des chauffeurs</h2>
        <table>
        <?php
             if(isset($message_suppression_chauffeur)) { 
                echo $message_suppression_chauffeur; 
            }
        // Récupérer la liste des chauffeurs
        $sql = "SELECT * FROM chauffeurs";
        $chauffeurs = mysqli_query($conn, $sql);
        if (mysqli_num_rows($chauffeurs) > 0) {
            echo "<tr><th>Id Chauffeur</th><th>Nom</th><th>Prénom</th><th>Disponibilité</th><th>Actions</th></tr>";
            while ($row = mysqli_fetch_assoc($chauffeurs)) {
                echo "<tr>";
                echo "<td>" . $row["Id_chauffeur"] . "</td>";
                echo "<td>" . $row["nom"] . "</td>";
                echo "<td>" . $row["prenom"] . "</td>";
                echo "<td>";     
                $sqldispochauffeurs = "SELECT immatriculation FROM Contrats   WHERE Id_chauffeur = '" . $row["Id_chauffeur"] . "' AND date_fin < NOW()";
                $resultc = mysqli_query($conn, $sqldispochauffeurs);
                if (mysqli_num_rows($resultc) > 0) {
                  echo "non disponible";
                } else {
                 echo "disponible";
                   }
                echo "</td>";
                echo "<td><form method='post' action='gestion.php' ><input type='hidden' name='Id_chauffeur' value='" . $row['Id_chauffeur'] . "'><input type='submit' name='supprimer_chauffeur' value='Supprimer'></form></td>";
                echo "</tr>"; 
            } 
        }else {
                echo "Aucun chauffeur trouvé.";
        }
    ?>
     </table>
    </section>
    <script src="script.js"></script>
</body>
</html>
