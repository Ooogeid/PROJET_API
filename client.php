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
			<form id="get-form">
				<label for="auteur">Auteur :</label>
				<input type="text" id="auteur" name="auteur">
				<button type="submit">Rechercher</button>
			</form>
		</section>
		<section id="nouvel-article">
			<h2>Ajouter un nouvel article</h2>
			<form>
				<label for="auteur">Auteur :</label>
				<input type="text" id="auteur" name="auteur">
				<label for="contenu">Contenu :</label>
				<textarea id="contenu" name="contenu"></textarea>
				<button type="submit">Ajouter</button>
			</form>
		</section>
		<section id="modifier-article">
			<h2>Modifier un article</h2>
			<form>
				<label for="id-modification">Identifiant de l'article :</label>
				<input type="number" id="id-modification" name="id-modification">
				<label for="nouveau-contenu">Nouveau contenu :</label>
				<textarea id="nouveau-contenu" name="nouveau-contenu"></textarea>
				<button type="submit">Modifier</button>
			</form>
		</section>
		<section id="supprimer-article">
			<h2>Supprimer un article</h2>
			<form>
				<label for="id-suppression">Identifiant de l'article :</label>
				<input type="number" id="id-suppression" name="id-suppression">
				<button type="submit">Supprimer</button>
			</form>
		</section>
	</main>

	<script>

		// Traitement du formulaire de la méthode GET
		$('#get-form').submit(function(event) {
		event.preventDefault();
		var author = $('input[name="auteur"]').val();
		$.ajax({
			url: 'http://localhost/R4.01/PROJET_API/src/serveur.php?author=' + author,
			method: 'GET',
			success: function(result) {
			$('#articles tbody').empty();
				result.forEach(function(article) {
					var row = $('<tr>');
					row.append($('<td>').text(article.id));
					row.append($('<td>').text(article.date_publication));
					row.append($('<td>').text(article.auteur));
					row.append($('<td>').text(article.contenu));
					row.append($('<td>').text(article.date_modification));
					$('#articles tbody').append(row);
				});
			},
			error: function(xhr, textStatus, errorThrown) {
			$('#articles tbody').empty();
			$('#articles tbody').append($('<tr>').append($('<td>').attr('colspan', 5).text('Erreur ' + xhr.status + ' : ' + errorThrown)));
			}
		});
		});

	</script>

</body>
</html>
