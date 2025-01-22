<?php

require_once __DIR__ . '/../db/DBConnection.php';


use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\UploadedFileInterface as UploadFile;
use GuzzleHttp\Client;
use GuzzleHttp\Psr7;
use Slim\Factory\AppFactory;

$app= AppFactory::create();

$app->get('/api/depots', function (Request $request, Response $response) {
    try {
        // Connexion à la base de données
        $dbconn = new DB\DBConnection();
        $db = $dbconn->connect();

        // Requête SQL pour récupérer tous les dépôts
        $sql = "SELECT * FROM Points_depot";
        $stmt = $db->prepare($sql);
        $stmt->execute();

        // Récupérer les résultats
        $depots = $stmt->fetchAll(PDO::FETCH_ASSOC);
        
        // Fermer la connexion à la base de données
        $db = null;

        // Vérifier si des dépôts ont été trouvés
        if (count($depots) > 0) {
            // Si des dépôts existent, les retourner sous forme JSON
            $response->getBody()->write(json_encode(['success' => true, 'depots' => $depots]));
            return $response->withHeader('Content-Type', 'application/json')->withStatus(200);
        } else {
            // Si aucun dépôt n'a été trouvé
            $response->getBody()->write(json_encode(['success' => false, 'message' => 'No depots found']));
            return $response->withHeader('Content-Type', 'application/json')->withStatus(404);
        }
    } catch (PDOException $e) {
        // Gestion des erreurs
        $response->getBody()->write('{"success": false , "message": "' . $e->getMessage() . '"}');
        return $response->withHeader('Content-Type', 'application/json')->withStatus(500);
    }
});

// Route pour ajouter un dépôt
$app->post('/api/depot', function (Request $request, Response $response) {
    $data = $request->getParsedBody();

    if (!isset($data['adresse']) || !isset($data['couleur_associee']) || !isset($data['nom']) || !isset($data['nom_gerant']) || !isset($data['telephone'])|| !isset($data['lng'])|| !isset($data['lat'])) {
        $response->getBody()->write('{"success": false, "message": "Missing required fields"}');
        return $response->withHeader('Content-Type', 'application/json')->withStatus(400);
    }

    try {
        $sql = "INSERT INTO Points_depot (adresse, couleur_associee, nom, nom_gerant, telephone, lng, lat) VALUES (:adresse, :couleur_associee, :nom, :nom_gerant, :telephone, :lng, :lat)";
        $dbconn = new DB\DBConnection();
        $db = $dbconn->connect();
        $stmt = $db->prepare($sql);
        $stmt->bindParam(':adresse', $data['adresse']);
        $stmt->bindParam(':couleur_associee', $data['couleur_associee']);
        $stmt->bindParam(':nom', $data['nom']);
        $stmt->bindParam(':nom_gerant', $data['nom_gerant']);
        $stmt->bindParam(':telephone', $data['telephone']);
        $stmt->bindParam(':lng', $data['lng']);
        $stmt->bindParam(':lat', $data['lat']);
        $stmt->execute();
        $db = null;

        $responsebody = array("success" => true, "message" => "Depot added successfully");
        $response->getBody()->write(json_encode($responsebody));
        return $response->withHeader('Content-Type', 'application/json')->withStatus(201);

    } catch (PDOException $e) {
        $response->getBody()->write('{"success": false , "message": "' . $e->getMessage() . '"}');
        return $response->withHeader('Content-Type', 'application/json')->withStatus(500);
    }
});

// Route pour modifier un dépôt
$app->put('/api/depot/{id}', function (Request $request, Response $response, $args) {
    $id = $args['id'];
    $data = $request->getParsedBody();
    if (!isset($data['adresse']) || !isset($data['couleur_associee']) || !isset($data['nom']) || !isset($data['nom_gerant']) || !isset($data['telephone'])|| !isset($data['lng'])|| !isset($data['lat'])) {
        $response->getBody()->write('{"success": false, "message": "Missing required fields"}');
        return $response->withHeader('Content-Type', 'application/json')->withStatus(400);
    }
    try {
        $sql = "UPDATE Points_depot SET adresse = :adresse, couleur_associee = :couleur_associee, nom = :nom, nom_gerant = :nom_gerant, telephone = :telephone WHERE id_point_depot = :id";
        $dbconn = new DB\DBConnection();
        $db = $dbconn->connect();
        $stmt = $db->prepare($sql);
        $stmt->bindParam(':id', $id);
        $stmt->bindParam(':adresse', $data['adresse']);
        $stmt->bindParam(':couleur_associee', $data['couleur_associee']);
        $stmt->bindParam(':nom', $data['nom']);
        $stmt->bindParam(':nom_gerant', $data['nom_gerant']);
        $stmt->bindParam(':telephone', $data['telephone']);
        $stmt->bindParam(':lng', $data['lng']);
        $stmt->bindParam(':lat', $data['lat']);
        $stmt->execute();
        $db = null;

        $responsebody = array("success" => true, "message" => "Depot updated successfully");
        $response->getBody()->write(json_encode($responsebody));
        return $response->withHeader('Content-Type', 'application/json')->withStatus(200);

    } catch (PDOException $e) {
        $response->getBody()->write('{"success": false , "message": "' . $e->getMessage() . '"}');
        return $response->withHeader('Content-Type', 'application/json')->withStatus(500);
    }
});

// Route pour supprimer un dépôt
$app->delete('/api/depot/{id}', function (Request $request, Response $response, $args) {
    $id = $args['id'];

    try {
        $sql = "DELETE FROM Points_depot WHERE id_point_depot = :id";
        $dbconn = new DB\DBConnection();
        $db = $dbconn->connect();
        $stmt = $db->prepare($sql);
        $stmt->bindParam(':id', $id);
        $stmt->execute();
        $db = null;

        $responsebody = array("success" => true, "message" => "Depot deleted successfully");
        $response->getBody()->write(json_encode($responsebody));
        return $response->withHeader('Content-Type', 'application/json')->withStatus(200);

    } catch (PDOException $e) {
        $response->getBody()->write('{"success": false , "message": "' . $e->getMessage() . '"}');
        return $response->withHeader('Content-Type', 'application/json')->withStatus(500);
    }
});
$app->get('/api/itineraire/{date}', function (Request $request, Response $response, $args) {
    $date = $args['date'];
    try {
        $sql = "SELECT * FROM Tournees_livraison WHERE jour_livraison = :jour_livraison";
        $dbconn = new DB\DBConnection();
        $db = $dbconn->connect();
        $stmt = $db->prepare($sql);
        $stmt->bindParam(':jour_livraison', $date);
        $stmt->execute();
        $results = $stmt->fetchAll(PDO::FETCH_ASSOC);
        $db = null;

        $response->getBody()->write(json_encode(["success" => true, "data" => $results]));
        return $response->withHeader('Content-Type', 'application/json')->withStatus(200);

    } catch (PDOException $e) {
        $response->getBody()->write('{"success": false, "message": "' . $e->getMessage() . '"}');
        return $response->withHeader('Content-Type', 'application/json')->withStatus(500);
    }
});


$app->get('/api/tournee/{id}/depots', function (Request $request, Response $response, array $args) {
    $id = $args['id'];

    try {
        // Requête SQL mise à jour pour correspondre à votre base de données
        $sql = "SELECT pd.* 
                FROM Points_depot pd
                INNER JOIN Tournees_PointsDepot tpd ON pd.id_point_depot = tpd.id_Points_depot
                WHERE tpd.id_Tournees_livraison = :id
                ORDER BY tpd.ordre ASC"; // Ajout de l'ordre si pertinent

        $dbconn = new DB\DBConnection();
        $db = $dbconn->connect();
        $stmt = $db->prepare($sql);
        $stmt->bindParam(':id', $id, PDO::PARAM_INT);
        $stmt->execute();
        $results = $stmt->fetchAll(PDO::FETCH_ASSOC);
        $db = null;

        // Retourne les résultats au format JSON
        $response->getBody()->write(json_encode(["success" => true, "data" => $results]));
        return $response->withHeader('Content-Type', 'application/json')->withStatus(200);

    } catch (PDOException $e) {
        // Gestion des erreurs
        $response->getBody()->write(json_encode([
            "success" => false,
            "message" => $e->getMessage()
        ]));
        return $response->withHeader('Content-Type', 'application/json')->withStatus(500);
    }
});


$app->post('/api/tournee', function (Request $request, Response $response) {
    $data = $request->getParsedBody();

    // Vérification des champs requis
    if (!isset($data['couleur_associee'])) {
        $response->getBody()->write('{"success": false, "message": "Missing required fields", "fields": "couleur_associee"}');
        return $response->withHeader('Content-Type', 'application/json')->withStatus(400);
    }
    if (!isset($data['jour_livraison'])) {
        $response->getBody()->write('{"success": false, "message": "Missing required fields", "fields": "jour_livraison"}');
        return $response->withHeader('Content-Type', 'application/json')->withStatus(400);
    }
    if (!isset($data['jour_preparation'])) {
        $response->getBody()->write('{"success": false, "message": "Missing required fields", "fields": "jour_preparation"}');
        return $response->withHeader('Content-Type', 'application/json')->withStatus(400);
    }
    if (!isset($data['numero_dordre'])) {
        $response->getBody()->write('{"success": false, "message": "Missing required fields", "fields": "numero_dordre"}');
        return $response->withHeader('Content-Type', 'application/json')->withStatus(400);
    }
    if (!isset( $data['depots'])) {
        $response->getBody()->write('{"success": false, "message": "Missing required fields", "fields": "depots"}');
        return $response->withHeader('Content-Type', 'application/json')->withStatus(400);
    }
    if (isset($data['depots']) && is_string($data['depots'])) {
        $decodedDepots = json_decode($data['depots'], true); // Force JSON decoding
        if (json_last_error() === JSON_ERROR_NONE) {
            $data['depots'] = $decodedDepots; // Remplace par le tableau décodé
        } else {
            $response->getBody()->write('{"success": false, "message": "Invalid JSON in depots"}');
            return $response->withHeader('Content-Type', 'application/json')->withStatus(400);
        }
    }
    


    try {
        // Connexion à la base de données
        $dbconn = new DB\DBConnection();
        $db = $dbconn->connect();
        $db->beginTransaction();

        // Insertion dans la table `Tournees`
        $sqlTournee = "INSERT INTO Tournees_livraison (couleur_associee, jour_livraison, jour_preparation, numero_dordre) 
                       VALUES (:couleur_associee, :jour_livraison, :jour_preparation, :numero_dordre)";
        $stmt = $db->prepare($sqlTournee);
        $stmt->bindParam(':couleur_associee', $data['couleur_associee']);
        $stmt->bindParam(':jour_livraison', $data['jour_livraison']);
        $stmt->bindParam(':jour_preparation', $data['jour_preparation']);
        $stmt->bindParam(':numero_dordre', $data['numero_dordre']);
        $stmt->execute();
        $tourneeId = $db->lastInsertId();

        $sqlDepot = "INSERT INTO Tournees_PointsDepot (id_Tournees_livraison, id_Points_depot, ordre) 
             VALUES (:id_Tournees, :id_Points_depot, :ordre)";
$stmtDepot = $db->prepare($sqlDepot);

foreach ($data['depots'] as $index => $depotId) {
    $idTournees = $tourneeId; // Variable temporaire pour id_Tournees
    $idDepot = $depotId; // Variable temporaire pour id_Points_depot
    $ordre = $index + 1; // Variable temporaire pour ordre

    $stmtDepot->bindParam(':id_Tournees', $idTournees);
    $stmtDepot->bindParam(':id_Points_depot', $idDepot);
    $stmtDepot->bindParam(':ordre', $ordre);
    $stmtDepot->execute();
}

        // Commit de la transaction
        $db->commit();
        $db = null;

        // Réponse en cas de succès
        $responseBody = [
            "success" => true,
            "message" => "Tournée créée avec succès",
            "tournee_id" => $tourneeId
        ];
        $response->getBody()->write(json_encode($responseBody));
        return $response->withHeader('Content-Type', 'application/json')->withStatus(201);

    } catch (PDOException $e) {
        // Rollback en cas d'erreur
        if ($db->inTransaction()) {
            $db->rollBack();
        }
        $response->getBody()->write('{"success": false, "message": "' . $e->getMessage() . '"}');
        return $response->withHeader('Content-Type', 'application/json')->withStatus(500);
    }
});


$app->post('/api/legumes/repartition', function (Request $request, Response $response) {
    try {
        $dbconn = new DB\DBConnection();
        $db = $dbconn->connect();

        $data = $request->getParsedBody();
        $semaine = $data['semaine'];

        // Récupérer les paniers pour la semaine donnée
        $sql = "SELECT * FROM Paniers WHERE semaine = :semaine";
        $stmt = $db->prepare($sql);
        $stmt->execute([':semaine' => $semaine]);
        $paniers = $stmt->fetchAll(PDO::FETCH_ASSOC);

        $date = new DateTime();
        $date->setISODate(date('Y'), $semaine);

        $moisActuel = $date->format('n');

        // Récupérer les légumes disponibles en stock et de saison
        $sql = "SELECT * FROM Legume WHERE stock > 0";
        $stmt = $db->prepare($sql);
        $stmt->execute();
        $legumes = $stmt->fetchAll(PDO::FETCH_ASSOC);

        $legumesDeSaison = [];
        foreach ($legumes as $legume) {
            // Vérifier si le légume est de saison pour le mois actuel
            if (isset($legume['periode'][$moisActuel - 1]) && $legume['periode'][$moisActuel - 1] == 'o') {
                $legumesDeSaison[] = $legume;
            }
        }

        // Calculer le total des paniers et vérifier si on a assez de stock pour remplir tous les paniers
        $totalPoidsNecessaire = 0;
        foreach ($paniers as $panier) {
            $totalPoidsNecessaire += $panier['prix']; // Prix représente la capacité de chaque panier
        }

        $totalStockDisponible = 0;
        foreach ($legumesDeSaison as $legume) {
            $totalStockDisponible += $legume['stock']; // Stock total des légumes de saison
        }

        // Si le stock total est insuffisant pour remplir tous les paniers
        if ($totalStockDisponible < $totalPoidsNecessaire) {
            $response->getBody()->write(json_encode(['success' => false, 'message' => 'Pas assez de légumes pour remplir tous les paniers'])); 
            return $response->withHeader('Content-Type', 'application/json')->withStatus(400);
        }

        // Répartition des légumes dans les paniers de manière uniforme
        $legumesRestants = $legumesDeSaison;
        foreach ($paniers as $panier) {
            $panierLegumes = [];
            $prixRestant = $panier['prix']; // Capacité du panier à remplir
            $contenuPanier = [];

            // Déterminer combien de légumes ajouter en fonction du type de panier
            switch ($panier['type']) {
                case 'Petit':
                    $nbLegumes = 5; // 5 légumes pour les paniers petits
                    break;
                case 'Moyen':
                    $nbLegumes = 7; // 7 légumes pour les paniers moyens
                    break;
                case 'Gros':
                    $nbLegumes = 10; // 10 légumes pour les paniers gros
                    break;
                default:
                    $nbLegumes = 5; // Par défaut, 5 légumes
            }

            // Répartition des légumes dans ce panier
            $legumesChoisis = array_rand($legumesRestants, $nbLegumes); 
            if ($nbLegumes == 1) {
                $legumesChoisis = [$legumesChoisis];
            }

            $totalPrixPanier = 0;
            foreach ($legumesChoisis as $index) {
                $legume = $legumesRestants[$index];
                $quantite = ($prixRestant / $nbLegumes) / $legume['prix']; 
                $nbLegumes = $nbLegumes - 1;
                $prixRestant -= $quantite * $legume['prix'];

                if ($quantite > 0) {
                    // Ajouter le légume au panier
                    $sql = "INSERT INTO ContenuPanier (id_Panier, id_Legume, poids) VALUES (:id_Panier, :id_Legume, :poids)";
                    $stmt = $db->prepare($sql);
                    $stmt->execute([ 
                        ':id_Panier' => $panier['id_Panier'],
                        ':id_Legume' => $legume['id_Legume'],
                        ':poids' => $quantite
                    ]);

                    // Réduire le stock du légume
                    $legume['stock'] -= $quantite;
                    $db->prepare("UPDATE Legume SET stock = :stock WHERE id_Legume = :id_Legume")
                        ->execute([':stock' => $legume['stock'], ':id_Legume' => $legume['id_Legume']]);

                    // Ajouter ce légume au contenu du panier
                    $contenuPanier[] = [
                        'nom' => $legume['nom'],
                        'poids' => $quantite,
                        'prix' => $quantite * $legume['prix']
                    ];

                    // Calcul du prix total du panier
                    $totalPrixPanier += $quantite * $legume['prix'];

                    // Si le légume est épuisé, on le retire des légumes restants
                    if ($legume['stock'] <= 0) {
                        unset($legumesRestants[$index]);
                    }
                }
            }

            
        }
// Réponse avec le contenu du panier
        $responseData = ['success' => true, 'message' => 'Répartition réussi avec succès'];
        $response->getBody()->write(json_encode($responseData));
        $db = null;
        return $response->withHeader('Content-Type', 'application/json')->withStatus(200);
    } catch (PDOException $e) {
        // En cas d'erreur avec la base de données
        $response->getBody()->write(json_encode([
            'success' => false,
            'message' => $e->getMessage()
        ]));
        return $response->withHeader('Content-Type', 'application/json')->withStatus(500);
    }
});






$app->post('/api/paniers', function (Request $request, Response $response) {
    $data = $request->getParsedBody();
    $semaine = $data['semaine'];
    $quantitePetit = $data['quantitePetit'];
    $quantiteMoyen = $data['quantiteMoyen'];
    $quantiteGros = $data['quantiteGros'];
    $types = ['Petit', 'Moyen', 'Gros'];

    try {
        $dbconn = new DB\DBConnection();
        $db = $dbconn->connect();

        // Traitement des paniers pour chaque type
        foreach (['Petit' => $quantitePetit, 'Moyen' => $quantiteMoyen, 'Gros' => $quantiteGros] as $type => $quantite) {
            for ($i = 0; $i < $quantite; $i++) {
                $sql = "INSERT INTO Paniers (type, prix, semaine) VALUES (:type, :prix, :semaine)";
                $stmt = $db->prepare($sql);
                $stmt->execute([
                    ':type' => $type, 
                    ':prix' => ($type === 'Petit' ? 7 : ($type === 'Moyen' ? 15 : 25)), 
                    ':semaine' => $semaine
                ]);
            }
        }

        $db = null;
        
        // Réponse JSON
        $responseData = ['success' => true, 'message' => 'Paniers créés avec succès'];
        $response->getBody()->write(json_encode($responseData));
        return $response->withHeader('Content-Type', 'application/json')->withStatus(201);
        
    } catch (PDOException $e) {
        // Erreur JSON
        $responseData = ['success' => false, 'message' => $e->getMessage()];
        $response->getBody()->write(json_encode($responseData));
        return $response->withHeader('Content-Type', 'application/json')->withStatus(500);
    }
});




$app->get('/api/paniers', function (Request $request, Response $response) {
    try {
        $dbconn = new DB\DBConnection();
        $db = $dbconn->connect();

        // Récupérer tous les paniers avec leurs informations
        $sql = "SELECT id_Panier, type, prix, semaine FROM Paniers";
        $stmt = $db->prepare($sql);
        $stmt->execute();
        $paniers = $stmt->fetchAll(PDO::FETCH_ASSOC);

        // Ajouter le contenu de chaque panier et le prix total
        foreach ($paniers as &$panier) {
            $idPanier = $panier['id_Panier'];

            // Récupérer le contenu du panier (légumes et poids)
            $sqlContenu = "SELECT L.nom, C.poids, L.prix 
                          FROM ContenuPanier C 
                          JOIN Legume L ON C.id_Legume = L.id_Legume 
                          WHERE C.id_Panier = :id_Panier";
            $stmtContenu = $db->prepare($sqlContenu);
            $stmtContenu->execute([':id_Panier' => $idPanier]);
            $contenu = $stmtContenu->fetchAll(PDO::FETCH_ASSOC);

            // Calculer le prix total du contenu
            $prixTotal = 0;
            foreach ($contenu as $legume) {
                $prixTotal += $legume['prix'] * $legume['poids'];
            }

            // Ajouter le contenu et le prix total au panier
            $panier['contenu'] = $contenu;
            $panier['prixTotal'] = $prixTotal;
        }

        $db = null;

        // Retourner la réponse au format JSON
        $response->getBody()->write(json_encode([
            'success' => true,
            'paniers' => $paniers
        ]));

        return $response->withHeader('Content-Type', 'application/json')->withStatus(200);
    } catch (PDOException $e) {
        // Gérer les erreurs et retourner un message d'erreur
        $response->getBody()->write(json_encode([
            'success' => false,
            'message' => $e->getMessage()
        ]));

        return $response->withHeader('Content-Type', 'application/json')->withStatus(500);
    }
});

$app->get('/api/paniers/count/{semaine}', function (Request $request, Response $response, $args) {
    $semaine = $args['semaine'];

    try {
        $dbconn = new DB\DBConnection();
        $db = $dbconn->connect();

        // Requête pour compter les paniers par type pour une semaine donnée
        $sql = "
            SELECT type, COUNT(*) as count
            FROM Paniers
            WHERE semaine = :semaine
            GROUP BY type
        ";
        $stmt = $db->prepare($sql);
        $stmt->execute([':semaine' => $semaine]);
        $counts = $stmt->fetchAll(PDO::FETCH_ASSOC);

        // Formater la réponse pour inclure les types manquants avec un compteur à 0
        $defaultCounts = [
            'Petit' => 0,
            'Moyen' => 0,
            'Gros' => 0
        ];
        foreach ($counts as $row) {
            $defaultCounts[$row['type']] = (int)$row['count'];
        }

        $db = null;

        // Retourner les résultats
        $response->getBody()->write(json_encode([
            'success' => true,
            'semaine' => $semaine,
            'counts' => $defaultCounts
        ]));
        return $response->withHeader('Content-Type', 'application/json')->withStatus(200);

    } catch (PDOException $e) {
        $response->getBody()->write(json_encode([
            'success' => false,
            'message' => $e->getMessage()
        ]));
        return $response->withHeader('Content-Type', 'application/json')->withStatus(500);
    }
});
