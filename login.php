<?php
        include 'jwt_utils.php';
            if(isset($_POST["username"]) && isset($_POST["password"])){
                $username = $_POST["username"];
                $password = $_POST["password"];
                $url = "http://localhost/R4.01/PROJET_API/src/ServeurAuthent.php";
                $data = array('username' => $username, 'password' => $password);
                $options = array(
                    'http' => array(
                    'header'  => "Content-type: application/x-www-form-urlencoded\r\n",
                    'method'  => 'POST',
                    'content' => http_build_query($data),
                    ),
                );
                $context = stream_context_create($options);
                $result = file_get_contents($url, false, $context);
                $retour = json_decode($result, true);
                $error = json_last_error_msg();
                print_r($error);
                print_r($retour);
                    
                if (isset($retour["jwt"])) {
                    $jwt = $retour["jwt"];
                    if (is_jwt_valid($jwt)) { // vérifie la validité du JWT
                        setcookie("jwt", $jwt, time() + 3600, null, null, false, true); // durée de validité : 1 heure (3600 secondes)
                        header("Location: client.php");
                        exit();
                    } else {
                        echo "<div class='error'>";
                        echo "<span>jeton invalide</span>";
                        echo "<i class='fa-solid fa-circle-xmark'></i>";
                        echo "</div>"; 
                    }
                } else {
                    echo "<div class='error'>";
                    echo "<span>retour vide</span>";
                    echo "<i class='fa-solid fa-circle-xmark'></i>";
                    echo "</div>";
                }
                }
?>
        


