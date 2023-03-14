<?php

require_once('connexion.php');
require_once('jwt_utils.php');

// Identifiants 
$valid_username = $_POST["username"];
$valid_password = $_POST["password"];


$data = (array) json_decode(file_get_contents('php://input'), TRUE);

function isValidUser($id, $mdp) {
    global $valid_username, $valid_password;
    // Vérification des identifiants
    if ($id == $valid_username && $mdp == $valid_password) {
        return true;
    }
    return false;
}

// Vérification des identifiants
if (isValidUser($data['id'], $data['mdp'])) {
    
    $username = $data['id'];

    $headers = array('alg' => 'HS256', 'typ' => 'JWT');
    $payload = array('username' => $username, 'exp' => (time() + 60));

    // Génération du jeton JWT
    $jwt = generate_jwt($headers, $payload);

} else {
    // Envoi d'une erreur au client si les identifiants sont invalides
    header('HTTP/1.0 401 Unauthorized');
    header('Content-Type: application/json');
    echo json_encode(array('error' => 'Identifiants invalides'));
}

?>
