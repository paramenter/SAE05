<?php

require_once __DIR__ . '/../db/DBConnection.php';


use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\UploadedFileInterface as UploadFile;
use GuzzleHttp\Client;
use GuzzleHttp\Psr7;



$app->post('/api/guesses', function (Request $request, Response $response) {
    $directory = $this->get('upload_directory');
    $uploadedFiles = $request->getUploadedFiles();
    if (array_key_exists("guessimage", $uploadedFiles)){
        $uploadedFile = $uploadedFiles['guessimage'];
    }
    else{
        $uploadedFile = null;
    }
    
    if ( $uploadedFile!=null && ($uploadedFile->getError() === UPLOAD_ERR_OK)) {

        if ($uploadedFile->getClientMediaType() == 'image/jpeg' || $uploadedFile->getClientMediaType() == 'image/jpg' || $uploadedFile->getClientMediaType() == 'image/png'  )
        {
            $id = (int) round(microtime(true) * 1000);
            $date = date(DATE_RFC2822, $id/1000);
            $extension = pathinfo($uploadedFile->getClientFilename(), PATHINFO_EXTENSION);
            $filename = sprintf('%d.%0.8s', $id, $extension);
            $imagepath = $directory . DIRECTORY_SEPARATOR . $filename;
            $uploadedFile->moveTo($imagepath);
            $client = new GuzzleHttp\Client(['base_uri' => 'http://node:3000/api/']);
            $response2 = $client->request('POST', 'guesses', [
                'multipart' => [
                    [
                        'name'     => 'guessimage',
                        'contents' => Psr7\Utils::tryFopen($imagepath, 'r')
                    ]
                ]
            ]);
            if ($response2->getStatusCode()==201 || $response2->getStatusCode()==200)
            {
                $ia_answer = json_decode($response2->getBody());
                $guess = $ia_answer->{"guess"};
                try {
                    $sql = "INSERT INTO guesses (`id`, `imagepath`, `guess`) VALUES(:id, :imagepath, :guess )";
                    $dbconn = new DB\DBConnection();
                    $db = $dbconn->connect();    
                    $stmt = $db->prepare( $sql );
                    $stmt->bindParam(':id', $id);  
                    $stmt->bindParam(':imagepath', $imagepath );
                    $stmt->bindParam(':guess', $guess );
                    $stmt->execute();
                    $db = null;
                } catch( PDOException $e ) {
                    $response->getBody()->write('{"success": false , "message": "' . $e->getMessage() . '"}');
                    return $response->withHeader('Content-Type', 'application/json')->withStatus(500);
        
                }


                $responsebody = array("id" => $id, "date" => $date, "imagepath" => $imagepath, "guess" => $guess);

                $response->getBody()->write(json_encode($responsebody));
                return $response->withHeader('Content-Type', 'application/json')->withStatus(201);
            }
            else{
                $ia_answer = json_decode($response2->getBody());
                $error = $ia_answer->{"error"};

                $response->getBody()->write('{"success": false , "message": "unable to predict from node image : '.$error . '"}');
                return $response->withHeader('Content-Type', 'application/json')->withStatus(500);
            }

        }
        else{
            $response->getBody()->write('{"success": false , "message": "Unsupported Media Type : Image must be \'jpg / jpeg / png\'"}');
            return $response->withHeader('Content-Type', 'application/json')->withStatus(415);
        }
        
    }
    else{
        $response->getBody()->write('{"success": false , "message": "Bad Request : Missing \'guessimage\' formdata field"}');
        return $response->withHeader('Content-Type', 'application/json')->withStatus(400);
    }

    
    return $response;
});





/* Put Guess Feedback (if AI Win or Not)*/
$app->put('/api/guesses/{guessid}', function (Request $request, Response $response) {
    // retrieve path param : guessid
    $guessid = $request->getAttribute('guessid');

    //prepar query to check if guess already done
    $sql = "select * from guesses where id = :guessid";

    //connect to DB
    $dbconn = new DB\DBConnection();
    $db = $dbconn->connect();    
    $stmt = $db->prepare( $sql );
    $stmt->bindParam(':guessid', $guessid);

    // query
    $stmt->execute();
    $guesses = $stmt->fetch( PDO::FETCH_OBJ );

    if (!$guesses){
        $db = null; // clear db object

        // if guess not already exists : Response : 404 : Not Found
        $response->getBody()->write('{"success": false , "message" : "Guess n° ' . $guessid . ' not found !"}');
        return $response->withHeader('Content-Type', 'application/json')->withStatus(404);
    }
    else{
        //check not required params 
        $reqbody = json_decode($request->getBody(), true);
        if (array_key_exists("win", $reqbody))
        {
            try {
                //update
                $sql = "UPDATE guesses SET `win` = :win WHERE id = :guessid";

                $stmt = $db->prepare( $sql );
                $stmt->bindParam(':win', $reqbody["win"]);  
                $stmt->bindParam(':guessid', $guessid);
            
                // execute update sql
                $stmt->execute();

                //get global scores
                $sql = "SELECT count(*) FROM guesses WHERE win <> 0 and win IS NOT NULL";
                $res = $db->query($sql);
                $totalguesses = $res->fetchColumn();

                $sql = "SELECT count(*) FROM guesses WHERE win > 0 and win IS NOT NULL";
                $res = $db->query($sql);
                $totalwins = $res->fetchColumn();

                //close connection
                $db=null;

                $responsebody = array("total" => $totalguesses, "win" => $totalwins);

                $response->getBody()->write(json_encode($responsebody));
                return $response->withHeader('Content-Type', 'application/json')->withStatus(200);

            } catch( PDOException $e ) {
                // response : 500 : PDO Error (DB)
                $response->getBody()->write('{"success": false , "message": "' . $e->getMessage() . '"}');
                return $response->withHeader('Content-Type', 'application/json')->withStatus(500);
    
            }
        }
        else{
            $response->getBody()->write('{"success": false , "message": "Bad Request : Missing \'win\' json field"}');
            return $response->withHeader('Content-Type', 'application/json')->withStatus(400);
        }
    }


    

    

    
    return $response;
});
