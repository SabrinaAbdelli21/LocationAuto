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

require('fpdf186\fpdf.php');

// Vérifier si le formulaire de suppression de contrat a été soumis
if(isset($_POST['supprimer_contrat'])) {
    $contrat_id = $_POST['N_contrat'];
    // Requête SQL pour supprimer le contrat de la table Contrats
    $sql = "DELETE FROM Contrats WHERE N_contrat = '$contrat_id'";

    if ($conn->query($sql) === TRUE) {
        $supprimercontrat ="Le contrat a été supprimé avec succès.";
        header("Location: gestion.php");
    } else {
        $supprimercontrat = "Erreur lors de la suppression du contrat: " . $conn->error;
    }
}

if(isset($_POST['generer_contrat'])) {
    $contrat_id = $_POST['N_contrat']; 
    $sql = "SELECT c.N_contrat, c.N_client, c.Id_chauffeur, c.immatriculation, c.date_debut, c.date_fin, c.kilometrage, c.prix_total,
    cl.nom AS nom_client, cl.prenom AS prenom_client, cl.date_naiss AS date_naiss_client, cl.telephone AS telephone_client, cl.adresse AS adresse_client,
    v.marque AS marque_voiture, v.modele AS modele_voiture, v. prix_loc_jours, v.kilometrage as kilov
FROM Contrats c
JOIN Clients cl ON c.N_client = cl.N_client
JOIN Voitures v ON c.immatriculation = v.immatriculation
WHERE c.N_contrat = $contrat_id";

$result = $conn->query($sql);
if ($result->num_rows > 0) {
$row = $result->fetch_assoc();

// Créer un objet FPDF
$pdf = new FPDF();

// Ajout d'une nouvelle page
$pdf->AddPage();

$pdf->Image('logo.png', 10, 5, 50, 20);
// Définition de la police
$pdf->SetFont('Arial', 'B', 12);

// Titre du contrat
$pdf->Cell(0, 12, 'Contrat de Location de Voiture', 1, 1, 'C');

// Informations du Contrat
$pdf->Ln();
$pdf->SetFont('Arial', 'B', 10);
$pdf->Cell(60, 10, 'Num Contrat :', 0, 0, 'L');
$pdf->SetFont('Arial', '', 10);
$pdf->Cell(100, 10, $row['N_contrat'], 0, 0, 'L');
$pdf->Ln();
$pdf->Cell(60, 10, 'Date de Debut :', 0, 0, 'L');
$pdf->SetFont('Arial', '', 10);
$pdf->Cell(100, 10, $row['date_debut'], 0, 0, 'L');
$pdf->Ln();
$pdf->Cell(60, 10, 'Date de Fin :', 0, 0, 'L');
$pdf->Cell(100, 10, $row['date_fin'], 0, 0, 'L');
$pdf->Ln();
$pdf->Cell(60, 10, 'Prix Total :',0, 0,  'L');
$pdf->Cell(100, 10, $row['prix_total'].' DA', 0, 0, 'L');
$pdf->Ln();
$pdf->Cell(60, 10, 'Kilometrage contractuelle :', 0, 0, 'L');
$pdf->Cell(100, 10, $row['kilometrage'], 0, 0, 'L');

$pdf->Ln(10);
// Informations du Client
$pdf->SetFont('Arial', 'B', 10);
$pdf->Cell(60, 10, 'Client :', 0, 0, 'L');
$pdf->Ln();
$pdf->SetFont('Arial', '', 10);
$pdf->Cell(60, 10, 'Nom et prenom :', 0, 0, 'L');
$pdf->Cell(100, 10, $row['nom_client'] . ' ' . $row['prenom_client'], 0, 0, 'L');
$pdf->Ln();
$pdf->Cell(60, 10, 'Date de Naissance :', 0, 0, 'L');
$pdf->Cell(100, 10, $row['date_naiss_client'], 0, 0, 'L');
$pdf->Ln();
$pdf->Cell(60, 10, 'Telephone :', 0, 0, 'L');
$pdf->Cell(100, 10, $row['telephone_client'], 0, 0, 'L');
$pdf->Ln();
$pdf->Cell(60, 10, 'Adresse :', 0, 0, 'L');
$pdf->Cell(100, 10, $row['adresse_client'], 0, 0, 'L');


// Informations de la Voiture
$pdf->Ln();
$pdf->SetFont('Arial', 'B', 10);
$pdf->Cell(60, 10, 'Voiture :', 0, 0, 'L');
$pdf->Ln();
$pdf->SetFont('Arial', '', 10);
$pdf->Cell(60, 10, 'Immatriculation :', 0, 0, 'L');
$pdf->Cell(100, 10, $row['immatriculation'], 0, 0, 'L');
$pdf->Ln();
$pdf->Cell(60, 10, 'Marque :', 0, 0, 'L');
$pdf->Cell(100, 10, $row['marque_voiture'] . ' ' . $row['modele_voiture'], 0, 0, 'L');
$pdf->Ln();
$pdf->Cell(60, 10, 'Modele :', 0, 0, 'L');
$pdf->Cell(100, 10, $row['modele_voiture'], 0, 0, 'L');
$pdf->Ln();
$pdf->Cell(60, 10, 'Kilometrage :', 0, 0, 'L');
$pdf->Cell(100, 10, $row['kilov'], 0, 0, 'L');
$pdf->Ln();
$pdf->Cell(60, 10, 'Prix de location par jours :', 0, 0, 'L');
$pdf->Cell(100, 10, $row['prix_loc_jours'].' DA' , 0, 0, 'L');
// Ajout de la clause en cas d'accident
$pdf->Ln();
$pdf->SetFont('Arial', 'B', 10);
$pdf->Cell(0, 10, 'Clause en cas d\'accident :', 0, 1, 'L');
$pdf->SetFont('Arial', '', 10);
$pdf->MultiCell(0, 10, 'En cas d\'accident, le Locataire est responsable des dommages causes au vehicule loue pendant la periode de location. Le Locataire s\'engage a payer les frais lies a la reparation des dommages causes au vehicule. Une fois les frais repares et documentes, le Loueur remboursera le Locataire pour tout montant excedant le depot de garantie.', 0, 'L');

// Clause de signature
$pdf->SetFont('Arial', '', 10);
$pdf->Cell(0, 10, 'En signant ce contrat, le Locataire confirme avoir lu, compris et accepte toutes les conditions et termes mentionnes ci-dessus.', 0, 'L');
$dateActuelle = new DateTime();
$pdf->Cell(0, 10, 'Fait en deux exemplaires, le '.$dateActuelle->format("Y-m-d H:i:s"), 0, 1, 'C');
$pdf->Cell(0, 10, 'Le Locateur                                                                    Le Locataire', 0, 1, 'C');
// Sortie du PDF
$pdf->Output();

// Fermer la connexion à la base de données
$conn->close();
} else {
echo 'Aucun contrat trouvé avec ce numéro.';
}
}

?>