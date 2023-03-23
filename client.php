<?php
		// Récupérer le jeton JWT à partir du cookie
		$jwt = $_COOKIE['jwt'];

		// Clé secrète partagée entre le serveur et le client
		$secret_key = 'secret';

		// Extraire la partie "claims" du jeton JWT
		$jwt_parts = explode('.', $jwt);
		$jwt_claims = $jwt_parts[1];
	
		// Décoder la partie "claims" du jeton JWT
		$decoded_jwt_claims = json_decode(base64_decode($jwt_claims), true);
		
		// Récupérer le nom d'utilisateur à partir des informations du jeton
		$login = $decoded_jwt_claims['username'];

?>

<!DOCTYPE html>
<html lang="fr">
<head>
	<meta charset="UTF-8">
	<title>API pour la gestion des articles</title>
	<link rel="stylesheet" type="text/css" href="style.css">
	<link rel="stylesheet" href="fontawesome-free-6.3.0-web/css/all.css">
	<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
</head>

<header>
	<h1>API pour la gestion des articles, bienvenue <?php echo $login; ?> !</h1>
</header>



<body>
	<div id="background"></div>
	<canvas id="background-canvas"></canvas>
	<main>
			<section id="articles">
				<h2>Liste des articles</h2>
				<form action="serveur.php" id="get-form" method="POST">
					<label for="id_articles">ID :</label>
					<input type="text" id="id_articles" name="id_articles">
					<button type="submit">Rechercher</button>
				</form>
				<div id="get-result">
					<form action="serveur.php" id="getAll-form" method="POST">
						<table id="get-result">
						<thead>
							<tr>
							<th>Date de publication</th>
							<th>Auteur</th>
							<th>Contenu</th>
							<th>Dernière modification</th>
							<th></th>
							<th></th>
							</tr>
						</thead>
						<tbody>
						</tbody>
						</table>
						<button id="refresh-btn" type="button">Rafraîchir la liste</button>
					</form>
				</div>
				
			</section>

		<section id="nouvel-article">
			<h2>Ajouter un nouvel article</h2>
			<form action="serveur.php" id="post-form" method="POST">
				<label for="auteur">Auteur :</label>
				<input type="text" id="auteur" name="auteur">
				<label for="contenu">Contenu :</label>
				<textarea id="contenu" name="contenu"></textarea>
				<button type="submit">Ajouter</button>
			</form>
			<div id="post-result"></div>
		</section>

		<section id="modifier-article">
			<h2>Modifier un article</h2>
			<form action="serveur.php" id ="put-form" method="POST">
				<label for="id-modification">Identifiant de l'article :</label>
				<input type="number" id="id-modification" name="id-modification">
				<label for="nouveau-contenu">Nouveau contenu :</label>
				<textarea id="nouveau-contenu" name="nouveau-contenu"></textarea>
				<button type="submit">Modifier</button>
			</form>
			<div id="put-result"><p></p></div>
		</section>

		<section id="supprimer-article">
			<h2>Supprimer un article</h2>
			<form action="serveur.php" id ="delete-form" method="POST">
				<label for="id-suppression">Identifiant de l'article :</label>
				<input type="number" id="id-suppression" name="id-suppression">
				<button type="submit">Supprimer</button>
			</form>
			<div id="delete-result"><p></p></div>
		</section>
	</main>

</body>

<footer>
	<p>© 2023 - API pour la gestion des articles</p>
</footer>

<script> // Partie javascript pour la gestion des requêtes AJAX

	// Traitement de la méthode GET pour afficher tous les articles
	$(document).ready(function() {
		$.ajax({
			url: 'serveur.php?select_all=true',
			method: 'GET',
			success: function(result) {
				var data = result.data;
				var tableBody = $('#get-result tbody');
				tableBody.empty(); // Vider le corps du tableau avant d'ajouter les nouvelles données
				for (var i = 0; i < data.length; i++) {
					var article = data[i];
					var row = 
						"<tr>" +
							"<td>" + article.date_publi + "</td>" +
							"<td>" + article.auteur + "</td>" +
							"<td>" + article.contenu + "</td>" +
							"<td>" + (article.DerniereModification ? article.DerniereModification : "") + "</td>" +
							"<td><a href='#' class='like-article' data-id='" + article.id_articles + "'><i class='far fa-thumbs-up'></i><span>J'aime</span></a></td>" +
							"<td><a href='#' class='dislike-article' data-id='" + article.id_articles + "'><i class='far fa-thumbs-down'></i> <span>Je n'aime pas</span></a></td>" +
						"</tr>"
					tableBody.append(row);
				}
			},
			error: function(xhr, status, error) {
				alert("Une erreur s'est produite lors de la récupération des articles.");
			}
		});
	});

	// Ajout d'un événement de clic sur le bouton de rafraîchissement
	document.getElementById("refresh-btn").addEventListener("click", function() {
		// Recharger la page pour afficher les nouveaux articles
		location.reload();
	});


	$(document).ready(function() {
		// Ecouteur d'événement de clic sur le bouton "Rechercher"
		$('#get-form button[type="submit"]').click(function(event) {
			event.preventDefault(); // Empêcher le comportement par défaut du formulaire
			var id = $('input[name="id_articles"]').val();
			$.ajax({
				url: 'http://localhost/R4.01/PROJET_API/src/serveur.php?id=' + id,
				method: 'GET',
				success: function(result) {
					var data = result.data;
					var tableBody = $('#get-result tbody');
					tableBody.empty(); // Vider le corps du tableau avant d'ajouter les nouvelles données
					for (var i = 0; i < data.length; i++) {
						var article = data[i];
						var row = 
							"<tr>" +
								"<td>" + article.date_publi + "</td>" +
								"<td>" + article.auteur + "</td>" +
								"<td>" + article.contenu + "</td>" +
								"<td>" + (article.DerniereModification ? article.DerniereModification : "") + "</td>" +
								"<td><a href='#' class='like-article' data-id='" + article.id_articles + "'><i class='far fa-thumbs-up'></i><span>J'aime</span></a></td>" +
								"<td><a href='#' class='dislike-article' data-id='" + article.id_articles + "'><i class='far fa-thumbs-down'></i> <span>Je n'aime pas</span></a></td>" +
							"</tr>"
						tableBody.append(row);
					}
				},
				error: function(xhr, textStatus, errorThrown) {
					$('#get-result tbody').empty(); // Vider le corps du tableau en cas d'erreur
					$('#get-result').html('<p>Erreur ' + xhr.status + ' : ' + errorThrown + '</p>');
				}
			});
		});
	});

		// Traitement du formulaire de la méthode POST
		$('#post-form').submit(function(event) {
			event.preventDefault();
			var auteur = $('input[name="auteur"]').val();
			var contenu = $('textarea[name="contenu"]').val();
			$.ajax({
				url: 'http://localhost/R4.01/PROJET_API/src/serveur.php',
				method: 'POST',
				data: JSON.stringify({'auteur': auteur, 'contenu': contenu}),
				contentType: 'application/json',
				success: function(result) {
					$('#post-result').html('<p>L\'article a bien été ajouté !</p>');
				},
				error: function(xhr, textStatus, errorThrown) {
					$('#post-result').html('<p>Erreur ' + xhr.status + ' : ' + errorThrown + '</p>');
				}
			});
		});

		// Traitement du formulaire de la méthode PUT
		$('#put-form').submit(function(event) {
			event.preventDefault();
			id = $('#put-form #id-modification').val();
			var data = {
				id: id,
				contenu: $('#put-form #nouveau-contenu').val()
			};
			$.ajax({url: 'serveur.php?id=' + id,
				type: 'PUT',
				dataType: 'json',
				data: JSON.stringify(data),
				contentType: 'application/json',
				success: function(result) {
					var data = result.data; // supposons que les données renvoyées sont stockées dans la propriété "data" de l'objet résultat
					var tbody = $('#get-result tbody');
					// Pour chaque article renvoyé, créer une nouvelle ligne avec les données correspondantes
					for (var i = 0; i < data.length; i++) {
						var article = data[i];
						var row = '<tr>' +
						'<td>' + article.id_articles + '</td>' +
						'<td>' + article.date_publi + '</td>' +
						'<td>' + article.auteur + '</td>' +
						'<td>' + article.contenu + '</td>' +
						'<td>' + article.date_modification + '</td>' +
						'</tr>';
						tbody.append(row); // Ajouter la nouvelle ligne dans le corps du tableau
					}
					$('#put-result').html('<p>L\'article a bien été modifié !</p>');
				},
				error: function(jqXHR, textStatus, errorThrown) {
					// Affichage de l'erreur
					console.log('Réponse reçue :', response);
					$('#put-result').text('Erreur: ' + textStatus + ' ' + errorThrown);
				}
			});
		});

	// Traitement du formulaire de la méthode DELETE
	$('#delete-form').submit(function(event) {
		event.preventDefault();
		var id = $('#delete-form #id-suppression').val();
		// Envoi de la requête DELETE au serveur
		$.ajax({
			url: 'serveur.php?id=' + id,
			type: 'DELETE',
			dataType: 'json',
			success: function(response) {
				// Affichage de la réponse du serveur
				$('#delete-result').text(JSON.stringify(response));
				$('#delete-result').html('<p>L\'article a bien été supprimé !</p>');
			},
			error: function(jqXHR, textStatus, errorThrown) {
				// Affichage de l'erreur
				$('#delete-result').text('Erreur: ' + textStatus + ' ' + errorThrown);
			}
		});
	});

		// gestion des like et dislike
		$(document).on('click', '.like-article', function(e) {
			e.preventDefault();
			var id_articles = $(this).data('id');
			$.ajax({
				url: 'serveur.php',
				method: 'POST',
				contentType: 'application/json',
				data: JSON.stringify({
					id_articles: id_articles,
					login: "<?php echo $login; ?>",
					has_liked: 1,
				}),
				success: function(result) {
					alert("Le like a été enregistré avec succès.");
				},
				error: function(xhr, status, error) {
					alert("Vous avez déja liké cette article .");
				}
			});
		});

		$(document).on('click', '.dislike-article', function(e) {
		e.preventDefault();
			var id_articles = $(this).data('id');
			$.ajax({
				url: 'serveur.php',
				method: 'POST',
				contentType: 'application/json',
				data: JSON.stringify({
					id_articles: id_articles,
					login: "<?php echo $login; ?>",
					has_disliked: 1,
				}),
				success: function(result) {
					alert("Le dislike a été enregistré avec succès.");
				},
				error: function(xhr, status, error) {
					alert("Une erreur s'est produite lors de l'envoi du dislike.");
				}
			});
		});

		// background interactif

		// couleur qui change de manière aléatoire
		window.onload = function() {
			var background = document.getElementById('background');
			var gradients = [
				'gradient-1',
				'gradient-2',
				'gradient-3'
			];
				
			function changeBackground() {
				var index = Math.floor(Math.random() * gradients.length);
				var gradient = gradients[index];
				background.className = 'background ' + gradient;
			}
				
			setInterval(changeBackground, 5000);

		// Feuille interactive lors du survol

			// Récupérer le canvas et son contexte
			var canvas = document.getElementById('background-canvas');
			var context = canvas.getContext('2d');

			// Configuration des feuilles
			var leafImage = new Image();
			leafImage.src = 'img/cloud.png';
			var leafs = [];
			var numLeafs = 20;
			var windSpeed = 2;
			var maxLeafSize = 60;
			var minLeafSize = 20;

			// Dessiner des feuilles aléatoires
			function drawLeaves() {
				for (var i = 0; i < numLeafs; i++) {
					var x = Math.random() * canvas.width;
					var y = Math.random() * canvas.height;
					var size = Math.random() * (maxLeafSize - minLeafSize) + minLeafSize;
					var speed = Math.random() * windSpeed + 1;
					leafs.push({ x: x, y: y, size: size, speed: speed });
				}
			}

			// Dessiner une feuille sur le canvas
			function drawLeaf(x, y, size) {
				context.drawImage(leafImage, x, y, size, size);
			}

			// Animer les feuilles
			function animateLeaves() {
				
				// Effacer le canvas
				context.clearRect(0, 0, canvas.width, canvas.height);
				
				// Dessiner les feuilles à leur nouvelle position
				for (var i = 0; i < leafs.length; i++) {
					var leaf = leafs[i];
					leaf.x -= leaf.speed;
					if (leaf.x < -leaf.size) {
					leaf.x = canvas.width + leaf.size;
					}
					drawLeaf(leaf.x, leaf.y, leaf.size);
				}
				
				// Répéter l'animation
				requestAnimationFrame(animateLeaves);
			}

			// Charger l'image et dessiner les feuilles
			leafImage.onload = function() {
				drawLeaves();
				animateLeaves();
			};

		}

	</script>

</html>
