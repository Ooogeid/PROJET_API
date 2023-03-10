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
            $query .= " WHERE id = $id_articles";
        }

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

        public function delete($id_articles) {
            $query = "DELETE FROM articles";
            if ($id_articles != "") {
                $query .= " WHERE  = :id_articles";
            }
            $stmt = $this->db->prepare($query);
            $stmt->execute(array(
                ':id_articles' => $id_articles
            ));
        }

    public function insert($data){
        $query = "INSERT INTO articles(phrase) VALUES (:phrase)";
        $stmt = $this->db->prepare($query);
        $stmt->execute(array(
            ':phrase' => $data['phrase']
        ));
        if(!$stmt){
            echo "Erreur d'execution : " . print_r($stmt->errorInfo(), true);
        }
    } 
}

?>
