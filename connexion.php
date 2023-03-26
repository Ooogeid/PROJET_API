<?php
function connexion() {
  // Connexion à la base de données
  try {
    $host = 'localhost'; 
    $dbname = 'api_articles'; 
    $username = 'root'; 
    $password = ''; 
    $pdo = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8", $username, $password);
    // Configurer l'option d'erreur pour que les erreurs soient renvoyées en tant qu'exceptions
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    // Configurer le mode d'émulation pour éviter les problèmes de compatibilité
    $pdo->setAttribute(PDO::ATTR_EMULATE_PREPARES, false);
    return $pdo;
  } catch (PDOException $e) {
    // En cas d'échec de la connexion, afficher l'erreur correspondante
    echo 'Erreur de connexion : ' . $e->getMessage();
  }
}
?>