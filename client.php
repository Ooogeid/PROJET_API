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
		var id = $('input[name="id_articles"]').val();
		$.ajax({
			url: 'http://localhost/R4.01/PROJET_API/src/serveur.php?id=' + id,
			method: 'GET',
			success: function(result) {
				// Créer une nouvelle ligne pour le tableau
			var newRow = $('<tr>');
			// Ajouter chaque valeur de l'article dans une cellule du tableau
			newRow.append('<td>' + result.id_articles + '</td>');
			newRow.append('<td>' + result.DateCreation + '</td>');
			newRow.append('<td>' + result.auteur + '</td>');
			newRow.append('<td>' + result.contenu + '</td>');
			newRow.append('<td>' + result.DerniereModification + '</td>');

			// Ajouter la nouvelle ligne au corps du tableau
			$('table tbody').append(newRow);
			},
			error: function(xhr, textStatus, errorThrown) {
				$('#get-result').html('<p>Erreur ' + xhr.status + ' : ' + errorThrown + '</p>');
			}
		});
		});

	</script>

</body>
</html>
