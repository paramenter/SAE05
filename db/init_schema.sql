CREATE TABLE Abonnements (
    id_abonnement INT PRIMARY KEY,
    date_demarrage DATE,
    duree INT,
    frequence INT,
    mode_reglement VARCHAR(50),
    options_choisies TEXT,
    statut VARCHAR(50),
    type_produit VARCHAR(50),
    id_adhérent INT,
    id_panier INT
);

CREATE TABLE Actions_adhérent (
    id_action_client INT PRIMARY KEY,
    declaration_absence BOOLEAN,
    modification_coordonnees_bancaires BOOLEAN,
    modification_coordonnees_personnelles BOOLEAN,
    modification_jour_livraison BOOLEAN,
    modification_point_depot BOOLEAN,
    paiements_regularisation BOOLEAN,
    report_annulation BOOLEAN,
    telechargement_factures_documents BOOLEAN
);

CREATE TABLE Adhesions (
    id_adhesion INT PRIMARY KEY,
    cotisation DECIMAL(10, 2),
    date_adhesion DATE,
    periodicite VARCHAR(50),
    statut VARCHAR(50),
    type_adhesion VARCHAR(50),
    id_adhérent INT,
    id_jardin INT
);

CREATE TABLE Calendrier (
    id_calendrier INT PRIMARY KEY,
    impact_dates_livraisons TEXT,
    impact_parcours_commande TEXT,
    jour_livraison_possible VARCHAR(50),
    semaine_non_livrable TEXT
);

CREATE TABLE Adhérent (
    id_client INT PRIMARY KEY,
    email VARCHAR(255),
    bons_livraison TEXT,
    coordonnees TEXT,
    date_naissance DATE,
    factures TEXT,
    historique_commandes TEXT,
    mot_de_passe VARCHAR(255),
    nationalite VARCHAR(50),
    nom VARCHAR(100),
    telephone VARCHAR(20),
    point_livraison VARCHAR(255),
    prenom VARCHAR(100),
    statut VARCHAR(50),
    id_point_depot INT
);

CREATE TABLE Documents (
    id_document INT PRIMARY KEY,
    etiquettes_paniers TEXT,
    feuille_preparation_commandes TEXT,
    feuille_route TEXT
);

CREATE TABLE Espace_client (
    id_espace_client INT PRIMARY KEY,
    id_client INT,
    commandes_en_cours TEXT,
    composition_prochain_panier TEXT,
    coordonnees_bancaires TEXT,
    coordonnees_personnelles TEXT,
    historique_commandes TEXT,
    livraisons TEXT,
    factures TEXT
);

CREATE TABLE Jardins_cocagne (
    id_jardin INT PRIMARY KEY,
    localisation TEXT,
    nom VARCHAR(100),
    relation_partenaires TEXT,
    statut VARCHAR(50)
);

CREATE TABLE Livraisons (
    id_livraison INT PRIMARY KEY,
    jour_livraison DATE,
    jour_preparation DATE,
    points_depot_associes TEXT,
    validation_livraisons BOOLEAN,
    id_calendrier INT,
    id_tournee INT
);

CREATE TABLE Période (
    id_periode INT PRIMARY KEY,
    date DATE
);

CREATE TABLE Points_depot (
    id_point_depot INT PRIMARY KEY,
    adresse TEXT,
    couleur_associee VARCHAR(50),
    nom VARCHAR(100),
    nom_gerant VARCHAR(100),
    ordre_livraison INT,
    telephone VARCHAR(20),
    id_tournee INT
);

CREATE TABLE Paniers (
    id_panier INT PRIMARY KEY,
    description TEXT,
    image TEXT,
    nom VARCHAR(100),
    type VARCHAR(50),
    unite VARCHAR(50)
);

CREATE TABLE Tournees_livraison (
    id_tournee INT PRIMARY KEY,
    couleur_associee VARCHAR(50),
    jour_livraison DATE,
    jour_preparation DATE,
    numéro_dordre INT,
    synthese_paniers_livres TEXT
);

CREATE TABLE Adhérent_Paniers (
    id_client INT,
    id_panier INT,
    PRIMARY KEY (id_client, id_panier)
);

CREATE TABLE PointsDepot_Paniers (
    id_point_depot INT,
    id_panier INT,
    PRIMARY KEY (id_point_depot, id_panier)
);

CREATE TABLE Abonnements_Livraisons (
    id_abonnement INT,
    id_livraison INT,
    PRIMARY KEY (id_abonnement, id_livraison)
);

CREATE TABLE Livraisons_Documents (
    id_livraison INT,
    id_document INT,
    PRIMARY KEY (id_livraison, id_document)
);
