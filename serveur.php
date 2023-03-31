<?php

require_once('Requete.php');
require_once('connexion.php');
require_once('jwt_utils.php');

/// Paramétrage de l'entête HTTP (pour la réponse au Client)
header('content-Type: application/json');

/// Identification du type de méthode HTTP envoyée par le client
$http_method = $_SERVER['REQUEST_METHOD'];

switch ($http_method){

  case "GET" :
    
    $id = "";
    /// Vérification si l'utilisateur veut récupérer tous les articles
    if (!empty($_GET['select_all'])) {
      /// Traitement
      $requete = new Requete();
      $matchingData = $requete->selectAll();

      // Récupération du nombre de likes et dislikes pour chaque article et ajout à la réponse JSON
      foreach ($matchingData as &$article) {
        $nb_likes = $requete->nbLike($article['id_articles']);
        $article['nb_likes'] = $nb_likes;
        $nb_dislikes = $requete->nbDislike($article['id_articles']);
        $article['nb_dislikes'] = $nb_dislikes;
      }
      
      /// Envoi de la réponse au Client
      deliver_response(200, "Tous les articles ont été récupérés avec succès.", $matchingData);

    // Sinon si l'utilisateur veut récupérer un article en particulier
    } elseif (!empty($_GET['id'])) {
        $id = $_GET['id'];
        /// Traitement
        $requete = new Requete(); 
        $matchingData = $requete->select($id);
        // Récupération du nombre de likes pour l'article et ajout à la réponse JSON
        $nb_likes = $requete->nbLike($id);
        $matchingData['nb_likes'] = $nb_likes;
        if (!$matchingData) {
          deliver_response(404, "Aucune donnée ne correspond à l'identifiant spécifié.", NULL);
        } else {
          /// Envoi de la réponse au Client
          deliver_response(200, "L'article a été récupéré avec succès.", $matchingData);
        }
    }
    // Sinon si l'utilisateur veut choisir le contenu à modifier dans le menu déroulant
    elseif(!empty($_GET['select_content'])) {
      /// Traitement
      $requete = new Requete();
      $matchingData = $requete->select_content();

      /// Envoi de la réponse au Client
      deliver_response(200, "Contenu récupérés avec succes.", $matchingData);
    }

    // Sinon erreur
    else{
      deliver_response(404, "L'identifiant de la ressource doit être spécifié pour la méthode GET", NULL);
    }
    break;

  /// Cas de la méthode POST
  case "POST":
    // Récupération des données envoyées par le Client
    $postedData = file_get_contents('php://input');
    // Traitement
    $data = json_decode($postedData, true);

    // Vérification de la validité du JSON
    $error = json_last_error();
    if ($error != "No error") {
        deliver_response(400, "Erreur de décodage JSON : $error", NULL);
    }
    // Ajout d'un article
    if (isset($data['auteur'], $data['contenu'])) {
        $data['date_publi'] = date('Y-m-d H:i:s');
        // Si l'utilisateur ajoute un article, on ajoute l'article dans la table "articles"
        $requete = new Requete();
        $requete->insert($data);
    }
    // Sinon si l'utilisateur like
    elseif(isset($data['id_articles'], $data['login'], $data['has_liked'])) {
        // Si l'utilisateur like un article, on ajoute l'information dans la table "likes"
        $requete = new Requete();
        $requete->insertLike($data['id_articles'], $data['login'], $data['has_liked']);
        // Envoi de la réponse au Client
        deliver_response(200, "Votre like a été pris en compte.", array());
    }
    // Sinon si l'utilisateur dislike
    elseif(isset($data['id_articles'], $data['login'], $data['has_disliked'])) {
        // Si l'utilisateur dislike un article, on ajoute l'information dans la table "dislikes"
        $requete = new Requete();
        $requete->insertDislike($data['id_articles'], $data['login'], $data['has_disliked']);
        // Envoi de la réponse au Client
        deliver_response(200, "Votre dislike a été pris en compte.", array());
    }
    // Sinon
    else {
        // Si les paramètres nécessaires ne sont pas présents, on renvoie une erreur
        deliver_response(400, "Paramètres incomplets.", array());
    }
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