<!DOCTYPE html>
<html lang="fr">
<head>
	<meta charset="UTF-8">
	<title>API pour la gestion des articles</title>
	<link rel="stylesheet" type="text/css" href="style.css">
	<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
</head>
<body>
	<header>
		<h1>API pour la gestion des articles</h1>
	</header>
	<main>
			<section id="articles">
				<h2>Liste des articles</h2>
				<form action="serveur.php" id="get-form" method="POST">
					<label for="id_articles">ID :</label>
					<input type="text" id="id_articles" name="id_articles">
					<button type="submit">Rechercher</button>
				</form>
				<div id="get-result">
					<table>
						<thead>
						<tr>
							<th>ID</th>
							<th>Date de création</th>
							<th>Auteur</th>
							<th>Contenu</th>
							<th>Dernière modification</th>
						</tr>
						</thead>
						<tbody>
						</tbody>
					</table>
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

	<script>

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
				var tbody = $('#get-result tbody');
				tbody.empty(); // Vider le corps du tableau avant d'ajouter les nouvelles données
				for (var i = 0; i < data.length; i++) {
				var article = data[i];
				var row = '<tr>' +
					'<td>' + article.id_articles + '</td>' +
					'<td>' + article.date_publi + '</td>' +
					'<td>' + article.auteur + '</td>' +
					'<td>' + article.contenu + '</td>' +
					'<td>' + article.date_modification + '</td>' +
					'</tr>';
				tbody.append(row);
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

	</script>

</body>
</html>
