<?php
include 'dbaccess.php';
    // Récupération du login et du mdp du formulaire
    $username = $_POST["username"];
    $password = $_POST["password"];
    
    // Hachage du mot de passe
    $hashedPassword = hash("sha256", $password);

    $stmt = $conn->query('SELECT login, password FROM users');

    // Initialisation de la variable pour vérifier si l'utilisateur a été trouvé   
    $userFound = false;
    
    // itère sur toute les lignes de la table de la base de données
    for ($row = $stmt->fetch(); !empty($row); $row = $stmt->fetch())
    {
        // vérifie si le mot de passe haché et le login sont les mêmes que ceux de la base
        if ($username == $row['login'] && strcmp($hashedPassword, $row['password']) == 0)
        {
            // Connexion réussie
            $userFound = true;
            break;
        }
    }
    
    if ($userFound) {
        // Permet l'accès à la page d'accueil
        header('Location: client.php');
        setcookie("username", $username, time()+3600);
    } else {
        // echec de connexion
        // Afficher un message d'erreur
        echo "Echec de connexion. Vérifiez vos identifiants.";
    }
?>