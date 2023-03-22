<?php

require_once('connexion.php');
require_once('jwt_utils.php');


$http_method = $_SERVER['REQUEST_METHOD'];
// Vérification des identifiants
if($http_method=='POST'){
    if (isset($_POST['username']) && isset($_POST['password'])) {
        $username = $_POST['username'];
        $password = $_POST['password'];
        // Connexion à la base de données
        $conn = connexion();
        // Préparation de la requête SQL pour récupérer l'utilisateur ayant le même login et mot de passe
        $stmt = $conn->prepare('SELECT * FROM users WHERE login = :username AND password = :password');
        $stmt->bindParam(':username', $username);
        $stmt->bindParam(':password', $password);
        $stmt->execute();
        // Vérification si l'utilisateur a été trouvé
        $userFound = false;
        // Récupération de la première ligne du résultat de la requête
        $row = $stmt->fetchAll();
        if ($row) {
            // L'utilisateur a été trouvé, connexion réussie
            $userFound = true;
        }
        // Vérification si l'utilisateur a été trouvé
        if ($userFound) {
            $role = $row[0]['role'];
            if($role == 'publisher'){
                $publisher = true;
                $moderator = false;
            }else{
                $publisher = false;
                $moderator = true;
            }
            $headers = array('alg' => 'HS256', 'typ' => 'JWT');
            $payload = array('username' => $username, 'role' => $role, 'exp' => (time() + 60));
            // Génération du jeton JWT
            $jwt = generate_jwt($headers, $payload);
            $retour["jwt"] = $jwt;
            echo json_encode($retour);
        } else {
            // Echec de connexion
            // Afficher un message d'erreur
            echo "Echec de connexion. Vérifiez vos identifiants.";
        }

    } else {
        // Envoi d'une erreur au client si les identifiants sont invalides
        header('HTTP/1.0 401 Unauthorized');
        header('Content-Type: application/json');
        echo json_encode(array('error' => 'Identifiants invalides'));
    }
}


?>
