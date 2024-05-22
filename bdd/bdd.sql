--Création de la base
CREATE database LocationAuto;
use LocationAuto;
-- Clients
CREATE table Clients(
    N_client int AUTO_INCREMENT PRIMARY KEY,
    nom VARCHAR(255),
    prenom VARCHAR(255),
    date_naiss date,
    telephone varchar(10),
    date_op_permis date,
    adresse varchar(255),
    nom_utilisateur varchar(90),
    mdp varchar(90) not null
)ENGINE=InnoDB;
--Managers
CREATE table Managers(
    nom_utilisateur varchar(90) PRIMARY KEY,
    mdp varchar(90) not null
)ENGINE=InnoDB;
-- Voitures
CREATE TABLE Voitures(
    immatriculation VARCHAR(255) PRIMARY KEY,
    type VARCHAR(255) NOT NULL,
    marque VARCHAR(255) NOT NULL,
    modele VARCHAR(255) NOT NULL,
    kilometrage float NOT NULL,
    prix_loc_jours DOUBLE NOT NULL,
    photo longblob DEFAULT NULL
)ENGINE=InnoDB;
-- Chauffeurs
CREATE TABLE Chauffeurs (
    Id_chauffeur INT PRIMARY KEY AUTO_INCREMENT,
    nom VARCHAR(255) NOT NULL,
    prenom VARCHAR(255) NOT NULL,
    telephone BIGINT NOT NULL,
    voiture varchar(255),
    FOREIGN KEY (voiture) REFERENCES Voitures(immatriculation) ON DELETE CASCADE
)ENGINE=InnoDB;
--Ajout de la clé du chauffeur à la table voiture
ALTER TABLE Voitures ADD chauffeur INT REFERENCES Chauffeurs(Id_chauffeur) ON DELETE CASCADE;
-- Contrats
CREATE TABLE Contrats (
    N_contrat INT PRIMARY KEY AUTO_INCREMENT,
    N_client INT,
    Id_chauffeur INT,
    immatriculation VARCHAR(255),
    date_debut DATE NOT NULL,
    date_fin DATE NOT NULL,
    kilometrage INT NOT NULL,
    paye BOOLEAN DEFAULT False,
    prix_total DOUBLE NOT NULL,
    FOREIGN KEY (N_client) REFERENCES Clients(N_client) ON DELETE CASCADE,
    FOREIGN KEY (immatriculation) REFERENCES Voitures(immatriculation) ON DELETE CASCADE,
    FOREIGN KEY (Id_chauffeur) REFERENCES Chauffeurs(Id_chauffeur) ON DELETE CASCADE
);
