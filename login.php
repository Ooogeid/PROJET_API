<?php

include 'connexion.php';

// Récupération du login et du mdp du formulaire
$username = $_POST["username"];
$password = $_POST["password"];

$conn = connexion();

// Préparation de la requête SQL pour récupérer l'utilisateur ayant le même login et mot de passe
$stmt = $conn->prepare('SELECT * FROM users WHERE login = :username AND password = :password');
$stmt->bindParam(':username', $username);
$stmt->bindParam(':password', $password);
$stmt->execute();

// Vérification si l'utilisateur a été trouvé
$userFound = false;

// Récupération de la première ligne du résultat de la requête
$row = $stmt->fetch();

if ($row) {
    // L'utilisateur a été trouvé, connexion réussie
    $userFound = true;
}

if ($userFound) {
    // Permet l'accès à la page d'accueil
    header('Location: client.php');
    setcookie("username", $username, time()+3600);
} else {
    // Echec de connexion
    // Afficher un message d'erreur
    echo "Echec de connexion. Vérifiez vos identifiants.";
}

?>

