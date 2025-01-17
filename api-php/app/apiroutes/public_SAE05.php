<?php

require_once __DIR__ . '/../db/DBConnection.php';


use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\UploadedFileInterface as UploadFile;
use GuzzleHttp\Client;
use GuzzleHttp\Psr7;


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

    // Vérification des champs nécessaires
    if (!isset($data['adresse']) || !isset($data['couleur_associee']) || !isset($data['nom']) || !isset($data['nom_gerant']) || !isset($data['telephone'])) {
        $response->getBody()->write('{"success": false, "message": "Missing required fields"}');
        return $response->withHeader('Content-Type', 'application/json')->withStatus(400);
    }

    try {
        $sql = "INSERT INTO Points_depot (adresse, couleur_associee, nom, nom_gerant, telephone) VALUES (:adresse, :couleur_associee, :nom, :nom_gerant, :telephone)";
        $dbconn = new DB\DBConnection();
        $db = $dbconn->connect();
        $stmt = $db->prepare($sql);
        $stmt->bindParam(':adresse', $data['adresse']);
        $stmt->bindParam(':couleur_associee', $data['couleur_associee']);
        $stmt->bindParam(':nom', $data['nom']);
        $stmt->bindParam(':nom_gerant', $data['nom_gerant']);
        $stmt->bindParam(':telephone', $data['telephone']);
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
