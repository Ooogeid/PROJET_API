-- Accès au site client depuis "authentification.html"

# Description du projet

-- Gestion articles est une API permettant la gestion d'un blog d'articles

-- Le dossier src contient premièrement "authentification.html", permettant de se log avec un compte ou sans
-- une fois log, on accès au client "client.php" qui est un fichier contenu du html, php et js

-- "connexion.php" est fichier php contenant une fonction permettant la connexion a la base de données
-- elle retourne la variable $pdo

-- le fichier "login.php" permet de vérifier les identifiants et interagi avec "ServeurAuthent.php"
-- si les identifiants sont correct, un token JWT est généré 

-- le fichier "serveur.php" contient pour chaque cas les méthodes associés de l'API.

-- "Requete.php" est un fichier contenant une classe avec des fonctions gérant chaque requête sur la base de données.

-- style.css est le ficher css pour le client et authent est celui pour la page d'authentification.

# Il y a trois comptes :

    - id : diego
    - password : diego972
    - role : publisher

    - id : baran
    - mdp : baran31
    - role : moderator

    - id : brice
    - mdp : PassProf
    - role : publisher

-- Il y a également la possbilité de se connecter sans compte, les fonctions sont donc limités on peut 
-- seulement acceder au articles.

Il y a également deux dossier, le premier "fontawesome-free-6.3.0-web" qui est une librairie css pour les icones des boutons likes / dislikes.
Le deuxième "img" contient juste l'image en png du nuage.