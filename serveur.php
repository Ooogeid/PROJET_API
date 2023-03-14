<?php

require_once('Requete.php');
require_once('connexion.php');
require_once('jwt_utils.php');

/// Paramétrage de l'entête HTTP (pour la réponse au Client)
header("Content-Type:application/json");

/// Identification du type de méthode HTTP envoyée par le client
$http_method = $_SERVER['REQUEST_METHOD'];
switch ($http_method){

/// Cas de la méthode GET
case "GET" :
  $id = "";
  /// Récupération des critères de recherche envoyés par le Client
  if (!empty($_GET['id'])){
    $id = $_GET['id'];
    /// Traitement
    $requete = new Requete(); 
    $matchingData = $requete->select($id);
    if (!$matchingData) {
      deliver_response(404, "Aucune donnée ne correspond à l'identifiant spécifié.", NULL);
    } else {
      /// Envoi de la réponse au Client
      deliver_response(200, "Votre message", $matchingData);
    }
  }
  else{
    deliver_response(404, "L'identifiant de la ressource doit être spécifié pour la méthode GET", NULL);
  }
  break;


/// Cas de la méthode POST
case "POST" :
    /// Récupération des données envoyées par le Client
    $postedData = file_get_contents('php://input');
    /// Traitement
    $requete = new Requete();
    $data = json_decode($postedData, true);
    $requete->insert($data);
    /// Envoi de la réponse au Client
    deliver_response(201, "Votre phrase : ", $data);
    $id = $_GET['id'];
    $requete->select($id);  
    break;

/// Cas de la méthode PUT
case "PUT" :
  /// Récupération des données envoyées par le Client
  $postedData = file_get_contents('php://input');
  /// Traitement
  $data = json_decode($postedData, true);
  $requete = new Requete();
  if(isset($data['id'])) {
    $id = $data['id'];
    $requete->update($id, $data);
    $matchingData = $requete->select($id);
    if (!$matchingData) {
      deliver_response(404, "Aucune donnée ne correspond à l'identifiant spécifié.", NULL);
    } else {
      /// Envoi de la réponse au Client
      $data['DerniereModification'] = date('Y-m-d H:i:s');
      $requete->updateDateModif($id, $data);
      deliver_response(200, "Votre phrases modifié : ", $data);
    }
    } else {
      // Envoi de la réponse au Client avec un code 400 si l'élément "id" n'est pas présent
      deliver_response(400, "L'élément 'id' est manquant dans les données envoyées.");
    }
  break;


/// Cas de la méthode DELETE
case "DELETE" :
  $id = "";
  /// Récupération de l'identifiant de la ressource envoyé par le Client
  if (!empty($_GET['id'])){
    /// Traitement
    $id = $_GET['id'];
    $requete = new Requete();
    $matchingData = $requete->delete($id);
    /// Envoi de la réponse au Client
    deliver_response(200, "l'id est maintenant supprimé.", NULL);
  } else {
    deliver_response(400, "L'identifiant de la ressource doit être spécifié pour la méthode DELETE", NULL);
  }
  break;

default:
  /// Envoi de la réponse au Client
  deliver_response(405, "Méthode non autorisée", NULL);
  break;

}
/// Fonction d'envoi de la réponse au Client
function deliver_response($status, $status_message, $data){
  /// Paramétrage de l'entête HTTP
  header("HTTP/1.1 $status $status_message");
  /// Paramétrage du type de contenu de la réponse
  $response['status'] = $status;
  $response['status_message'] = $status_message;
  $response['data'] = $data;
  /// Envoi de la réponse au Client
  $json_response = json_encode($response);
  echo $json_response;
}



?>