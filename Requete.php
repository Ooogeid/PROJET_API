<?php

require_once('connexion.php');

class Requete {
    
    private $db;
    public function __construct() {
        $this->db = connexion();
        $this->db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    }
    
    public function select($id_articles) {
        $query = "SELECT * FROM articles";
        if ($id_articles != "") {
            $query .= " WHERE id_articles = $id_articles";
        }

        $stmt = $this->db->prepare($query);
        $stmt->execute();
        
        $resultat = $stmt->fetchAll(PDO::FETCH_ASSOC);
        return $resultat;
    }

    public function selectAll() {
        $query = "SELECT * FROM articles";
        $stmt = $this->db->prepare($query);
        $stmt->execute();
        
        $resultat = $stmt->fetchAll(PDO::FETCH_ASSOC);
        return $resultat;
    }
    
    public function update($id_articles, $data) {
        $query = "UPDATE articles SET contenu = :contenu WHERE id_articles = :id_articles";
        $stmt = $this->db->prepare($query);
        $result = $stmt->execute(array(
            'id_articles' => $id_articles,
            'contenu' => $data['contenu']
        ));
        return $result;
    }

    public function updateDateModif($id_articles, $data){
        $query = "UPDATE articles SET DerniereModification = :DerniereModification WHERE id_articles = :id_articles";
        $stmt = $this->db->prepare($query);
        $result = $stmt->execute(array(
            'id_articles' => $id_articles,
            'DerniereModification' => $data['DerniereModification']
        ));
        return $result;
    }

    public function delete($id_articles) {
        $query = "DELETE FROM articles";
        if ($id_articles != "") {
            $query .= " WHERE id_articles = :id_articles";
        }
        $stmt = $this->db->prepare($query);
        $stmt->execute(array(
            ':id_articles' => $id_articles
        ));
    }

    public function insertLike($id_articles, $login, $has_liked) {
        try {
            $query = "INSERT INTO liked (id_articles, login, has_liked) VALUES (:id_articles, :login, :has_liked)";
            $stmt = $this->db->prepare($query);
            $stmt->bindParam(":id_articles", $id_articles);
            $stmt->bindParam(":login", $login);
            $stmt->bindParam(":has_liked", $has_liked);
            $stmt->execute();
            return true;
        } catch (PDOException $e) {
            echo "Insertion failed: " . $e->getMessage();
            return false;
        }
    }

    public function insertDislike($id_articles, $login, $has_disliked) {
        try {
            $query = "INSERT INTO disliked (id_articles, login, has_disliked) VALUES (:id_articles, :login, :has_disliked)";
            $stmt = $this->db->prepare($query);
            $stmt->bindParam(":id_articles", $id_articles);
            $stmt->bindParam(":login", $login);
            $stmt->bindParam(":has_disliked", $has_disliked);
            $stmt->execute();
            return true;
        } catch (PDOException $e) {
            echo "Insertion failed: " . $e->getMessage();
            return false;
        }
    }
    

    public function insert($data){
        $query = "INSERT INTO articles(date_publi, auteur, contenu) 
        VALUES (:date_publi, :auteur, :contenu)";
        $stmt = $this->db->prepare($query);
        $result = $stmt->execute(array(
            ':date_publi' => $data['date_publi'],
            ':auteur' => $data['auteur'],
            ':contenu' => $data['contenu']
        ));

        $response = array(); // initialisation de la réponse

        if(!$result){
            http_response_code(500);
            $response['message'] = "Erreur d'execution : " . print_r($stmt->errorInfo(), true);
        }
        else{
            $id = $this->db->lastInsertId();
            http_response_code(200);
            $response['id'] = $id;
            $response['status'] = 200;
            $response['status_message'] = "Votre article a été ajouté avec succès.";
            $response['data'] = array(
                "auteur" => $data['auteur'],
                "contenu" => $data['contenu'],
                "date_publi" => $data['date_publi']
            );
        }
        echo json_encode($response); // envoi de la réponse au format JSON
    }


    
}

?>
