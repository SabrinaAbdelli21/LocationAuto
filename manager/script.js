// Récupération des boutons 
const btnGestionVoitures = document.getElementById('btnGestionVoitures');
const btnGestionContrats = document.getElementById('btnGestionContrats');
const btnGestionPaiements = document.getElementById('btnGestionPaiements');
const btnGestionChauffeurs = document.getElementById('btnGestionChauffeurs');

// Récupération des sections
const sectionGestionVoitures = document.getElementById('sectionGestionVoitures');
const sectionGestionContrats = document.getElementById('sectionGestionContrats');
const sectionGestionPaiements = document.getElementById('sectionGestionPaiements');
const sectionGestionChauffeurs = document.getElementById('sectionGestionChauffeurs');

// Fonctions pour afficher les sections
function showGestionVoitures() {
    sectionGestionVoitures.style.display = 'block';
    sectionGestionContrats.style.display = 'none';
    sectionGestionPaiements.style.display = 'none';
    sectionGestionChauffeurs.style.display = 'none';
}

function showGestionContrats() {
    sectionGestionVoitures.style.display = 'none';
    sectionGestionContrats.style.display = 'block';
    sectionGestionPaiements.style.display = 'none';
    sectionGestionChauffeurs.style.display = 'none';
}

function showGestionPaiements() {
    sectionGestionVoitures.style.display = 'none';
    sectionGestionContrats.style.display = 'none';
    sectionGestionPaiements.style.display = 'block';
    sectionGestionChauffeurs.style.display = 'none';
}

function showGestionChauffeurs() {
    sectionGestionVoitures.style.display = 'none';
    sectionGestionContrats.style.display = 'none';
    sectionGestionPaiements.style.display = 'none';
    sectionGestionChauffeurs.style.display = 'block';
}

// Ajout des écouteurs d'événements aux boutons
btnGestionVoitures.addEventListener('click', showGestionVoitures);
btnGestionContrats.addEventListener('click', showGestionContrats);
btnGestionPaiements.addEventListener('click', showGestionPaiements);
btnGestionChauffeurs.addEventListener('click', showGestionChauffeurs);

// Affichage initial
showGestionVoitures(); 

// Récupérer les boutons de basculement de formulaire par leur ID
const toggleButtonVoiture = document.getElementById('toggle-form-voiture');
const toggleButtonChauffeur = document.getElementById('toggle-form');

// Ajouter un gestionnaire d'événements pour le clic sur chaque bouton de basculement de formulaire
toggleButtonVoiture.addEventListener('click', function() {
    // Récupérer le formulaire de voiture par son ID
    const formDivVoiture = document.getElementById('ajouter-formulaire-voiture');
    // Inverser la visibilité du formulaire
    formDivVoiture.style.display = (formDivVoiture.style.display === 'none') ? 'block' : 'none';
});

toggleButtonChauffeur.addEventListener('click', function() {
    // Récupérer le formulaire de chauffeur par son ID
    const formDivChauffeur = document.getElementById('ajouter-formulaire-chauffeur');
    // Inverser la visibilité du formulaire
    formDivChauffeur.style.display = (formDivChauffeur.style.display === 'none') ? 'block' : 'none';
});

