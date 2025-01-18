

CREATE TABLE `Abonnements` (
  `id_abonnement` int(11) NOT NULL,
  `date_demarrage` date DEFAULT NULL,
  `duree` int(11) DEFAULT NULL,
  `frequence` int(11) DEFAULT NULL,
  `mode_reglement` varchar(50) DEFAULT NULL,
  `options_choisies` text DEFAULT NULL,
  `statut` varchar(50) DEFAULT NULL,
  `type_produit` varchar(50) DEFAULT NULL,
  `id_adhérent` int(11) DEFAULT NULL,
  `id_panier` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;



CREATE TABLE `Abonnements_Livraisons` (
  `id_abonnement` int(11) NOT NULL,
  `id_livraison` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;



CREATE TABLE `Actions_adhérent` (
  `id_action_client` int(11) NOT NULL,
  `declaration_absence` tinyint(1) DEFAULT NULL,
  `modification_coordonnees_bancaires` tinyint(1) DEFAULT NULL,
  `modification_coordonnees_personnelles` tinyint(1) DEFAULT NULL,
  `modification_jour_livraison` tinyint(1) DEFAULT NULL,
  `modification_point_depot` tinyint(1) DEFAULT NULL,
  `paiements_regularisation` tinyint(1) DEFAULT NULL,
  `report_annulation` tinyint(1) DEFAULT NULL,
  `telechargement_factures_documents` tinyint(1) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;



CREATE TABLE `Adhesions` (
  `id_adhesion` int(11) NOT NULL,
  `cotisation` decimal(10,2) DEFAULT NULL,
  `date_adhesion` date DEFAULT NULL,
  `periodicite` varchar(50) DEFAULT NULL,
  `statut` varchar(50) DEFAULT NULL,
  `type_adhesion` varchar(50) DEFAULT NULL,
  `id_adhérent` int(11) DEFAULT NULL,
  `id_jardin` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;



CREATE TABLE `Adhérent` (
  `id_client` int(11) NOT NULL,
  `email` varchar(255) DEFAULT NULL,
  `bons_livraison` text DEFAULT NULL,
  `coordonnees` text DEFAULT NULL,
  `date_naissance` date DEFAULT NULL,
  `factures` text DEFAULT NULL,
  `historique_commandes` text DEFAULT NULL,
  `mot_de_passe` varchar(255) DEFAULT NULL,
  `nationalite` varchar(50) DEFAULT NULL,
  `nom` varchar(100) DEFAULT NULL,
  `telephone` varchar(20) DEFAULT NULL,
  `point_livraison` varchar(255) DEFAULT NULL,
  `prenom` varchar(100) DEFAULT NULL,
  `statut` varchar(50) DEFAULT NULL,
  `id_point_depot` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;



CREATE TABLE `Adhérent_Paniers` (
  `id_client` int(11) NOT NULL,
  `id_panier` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;



CREATE TABLE `Calendrier` (
  `id_calendrier` int(11) NOT NULL,
  `impact_dates_livraisons` text DEFAULT NULL,
  `impact_parcours_commande` text DEFAULT NULL,
  `jour_livraison_possible` varchar(50) DEFAULT NULL,
  `semaine_non_livrable` text DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;



CREATE TABLE `Documents` (
  `id_document` int(11) NOT NULL,
  `etiquettes_paniers` text DEFAULT NULL,
  `feuille_preparation_commandes` text DEFAULT NULL,
  `feuille_route` text DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;



CREATE TABLE `Espace_client` (
  `id_espace_client` int(11) NOT NULL,
  `id_client` int(11) DEFAULT NULL,
  `commandes_en_cours` text DEFAULT NULL,
  `composition_prochain_panier` text DEFAULT NULL,
  `coordonnees_bancaires` text DEFAULT NULL,
  `coordonnees_personnelles` text DEFAULT NULL,
  `historique_commandes` text DEFAULT NULL,
  `livraisons` text DEFAULT NULL,
  `factures` text DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;



CREATE TABLE `Jardins_cocagne` (
  `id_jardin` int(11) NOT NULL,
  `localisation` text DEFAULT NULL,
  `nom` varchar(100) DEFAULT NULL,
  `relation_partenaires` text DEFAULT NULL,
  `statut` varchar(50) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;



CREATE TABLE `Livraisons` (
  `id_livraison` int(11) NOT NULL,
  `jour_livraison` date DEFAULT NULL,
  `jour_preparation` date DEFAULT NULL,
  `points_depot_associes` text DEFAULT NULL,
  `validation_livraisons` tinyint(1) DEFAULT NULL,
  `id_calendrier` int(11) DEFAULT NULL,
  `id_tournee` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;


CREATE TABLE `Livraisons_Documents` (
  `id_livraison` int(11) NOT NULL,
  `id_document` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;



CREATE TABLE `Paniers` (
  `id_panier` int(11) NOT NULL,
  `description` text DEFAULT NULL,
  `image` text DEFAULT NULL,
  `nom` varchar(100) DEFAULT NULL,
  `type` varchar(50) DEFAULT NULL,
  `unite` varchar(50) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;



CREATE TABLE `PointsDepot_Paniers` (
  `id_point_depot` int(11) NOT NULL,
  `id_panier` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;



CREATE TABLE `Points_depot` (
  `id_point_depot` int(11) NOT NULL,
  `adresse` text DEFAULT NULL,
  `couleur_associee` varchar(50) DEFAULT NULL,
  `nom` varchar(100) DEFAULT NULL,
  `nom_gerant` varchar(100) DEFAULT NULL,
  `telephone` varchar(20) DEFAULT NULL,
  `lng` double NOT NULL,
  `lat` double NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;


INSERT INTO `Points_depot` (`id_point_depot`, `adresse`, `couleur_associee`, `nom`, `nom_gerant`, `telephone`, `lng`, `lat`) VALUES
(10, '11 rue de l\'univercite', '#a10c0c', 'sdfsf', 'sdfds', 'sdf', 6.942607, 48.289991),
(12, '9 allée des erables', '#15a231', 'wsddsq', 'qdsdq', 'qsd', 2.326772, 48.630309),
(13, '6 rue charpentier', '#e01aa1', 'qsd', 'qsdqs', 'qsdqsd', 2.328093, 48.632477),
(14, '95 avenue du Marechal Juin', '#34eacb', 'sdqfsd', 'fdsfdsfds', '04.43.98.37.52', 7.192926, 43.659241),
(15, '45 Rue de la Pompe', '#55dbdd', 'edrfg', 'erger', 'ergerg', 1.719465, 48.984275),
(19, '66 Rue Robert Schuman', '#3685ce', 'rthrthyr', 'ytryrt', 'rtyrt', 6.134504, 49.113814);



CREATE TABLE `Période` (
  `id_periode` int(11) NOT NULL,
  `date` date DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;



CREATE TABLE `Tournees_livraison` (
  `id_tournee` int(11) NOT NULL,
  `couleur_associee` varchar(50) DEFAULT NULL,
  `jour_livraison` date DEFAULT NULL,
  `jour_preparation` date DEFAULT NULL,
  `numero_dordre` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;



INSERT INTO `Tournees_livraison` (`id_tournee`, `couleur_associee`, `jour_livraison`, `jour_preparation`, `numero_dordre`) VALUES
(4, '#FF0000', '2025-01-19', '2025-01-18', NULL),
(5, '#991414', '2025-01-31', '2025-01-22', NULL),
(6, '#181d68', '2025-01-18', '2025-01-14', 97394),
(7, '#ff00dd', '2025-01-18', '2025-01-16', 67838492),
(8, '#b12b2b', '2025-01-18', '2025-01-07', 89727892);



CREATE TABLE `Tournees_PointsDepot` (
  `id` int(11) NOT NULL,
  `id_Tournees_livraison` int(11) NOT NULL,
  `id_Points_depot` int(11) NOT NULL,
  `ordre` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;



INSERT INTO `Tournees_PointsDepot` (`id`, `id_Tournees_livraison`, `id_Points_depot`, `ordre`) VALUES
(1, 4, 13, 1),
(2, 4, 19, 2),
(3, 4, 14, 3),
(4, 4, 15, 4),
(5, 5, 13, 1),
(6, 5, 19, 2),
(7, 6, 13, 1),
(8, 6, 14, 2),
(9, 6, 19, 3),
(10, 7, 13, 1),
(11, 7, 14, 2),
(12, 7, 19, 3),
(13, 7, 10, 4),
(14, 8, 10, 1),
(15, 8, 19, 2);


ALTER TABLE `Abonnements`
  ADD PRIMARY KEY (`id_abonnement`);


ALTER TABLE `Abonnements_Livraisons`
  ADD PRIMARY KEY (`id_abonnement`,`id_livraison`);

ALTER TABLE `Actions_adhérent`
  ADD PRIMARY KEY (`id_action_client`);


ALTER TABLE `Adhesions`
  ADD PRIMARY KEY (`id_adhesion`);

ALTER TABLE `Adhérent`
  ADD PRIMARY KEY (`id_client`);

ALTER TABLE `Adhérent_Paniers`
  ADD PRIMARY KEY (`id_client`,`id_panier`);

ALTER TABLE `Calendrier`
  ADD PRIMARY KEY (`id_calendrier`);

ALTER TABLE `Documents`
  ADD PRIMARY KEY (`id_document`);

ALTER TABLE `Espace_client`
  ADD PRIMARY KEY (`id_espace_client`);

ALTER TABLE `Jardins_cocagne`
  ADD PRIMARY KEY (`id_jardin`);

ALTER TABLE `Livraisons`
  ADD PRIMARY KEY (`id_livraison`);

ALTER TABLE `Livraisons_Documents`
  ADD PRIMARY KEY (`id_livraison`,`id_document`);

ALTER TABLE `Paniers`
  ADD PRIMARY KEY (`id_panier`);

ALTER TABLE `PointsDepot_Paniers`
  ADD PRIMARY KEY (`id_point_depot`,`id_panier`);

ALTER TABLE `Points_depot`
  ADD PRIMARY KEY (`id_point_depot`);

ALTER TABLE `Période`
  ADD PRIMARY KEY (`id_periode`);

ALTER TABLE `Tournees_livraison`
  ADD PRIMARY KEY (`id_tournee`);

ALTER TABLE `Tournees_PointsDepot`
  ADD PRIMARY KEY (`id`);

ALTER TABLE `Points_depot`
  MODIFY `id_point_depot` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=20;

ALTER TABLE `Tournees_livraison`
  MODIFY `id_tournee` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

ALTER TABLE `Tournees_PointsDepot`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=16;
COMMIT;

