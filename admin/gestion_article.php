<?php
//si le visiteur n'est pas connecté ou que le membre connecté n'est pas un admin : redirection !
if(!isset($_SESSION['membre']) || (!$_SESSION['membre']['status'] == 1))
{
	//aucun caractère html ou texte au-dessus du header()
	header('location:' . URL);
}
$content .= "<h3>Page gestion articles</h3>"; 


if(isset($_GET['action']) AND $_GET['action'] == "modif")
{
	//requete pour récupérer les données utilisateurs : 
	$recupArticles = $pdo->query("SELECT * FROM articles WHERE id_article= $_GET[id_article]");
	
	//je récupère les données sous forme de tableau associatif
	$affichageArticles = $recupArticles->fetch(); 

	//conditions ternaires : 
	
	$content .='<div class="panel panel-info">
					<div class="panel-heading">
					<h4>Modification de l\'article' . ucfirst($affichageArticles['title']) . '</h4>
					</div>';
					
					$content.='
						</div>
						<div class="form-group">
							<label for="titre">Titre</label>
							<input type="text" id="titre" name="titre" class="form-control" value="' . $affichageArticles['title'] . '">
						</div>
							
						<div class="form-group">
							<label for="image1">Image1</label>
							<input type="file" id="image1" name="image1" class="form-control" value="' . $affichageArticles['image'] . '">
						</div>	
						<div class="form-group">
							<label for="contenu">Contenu</label>
                            <textarea name="contenu" id="contenu">' . $affichageArticles['content'] . '</textarea>

						<input type="submit" value="Modification" name="update" class="btn btn-large btn-info form-control">


						
					</form>
					</div>';
		
}
//SUPPRESSION : 
if(isset($_GET['action']) AND $_GET['action'] == "suppr")
{
		$pdo->query('DELETE FROM `articles` WHERE id_article = "' . $_GET['id_article'] . '"');
		header('location:' . URL . '?pageAdmin=article&suppr=ok&id_article=' . $_GET['id_article']);//redirection + stockage infos dans l'url pour gérer l'affichage de la suppression

}
if(isset($_GET['suppr']) AND $_GET['suppr'] == "ok") //message suppression membre
{
	$content .= '<div class="alert alert-success">L\'article ' . $_GET['id_article'] . ' a bien été supprimé !</div>';	
}

//si dans l'url affichage == true : affichage du tableau
if(isset($_GET['affichage']) AND $_GET['affichage'] == 'true')
{
	$recupDonnees = $pdo->query('SELECT id_article AS "Identifiant",user_id as "auteur",title,image,content,date_publication FROM article'); 

	$nbCol = $recupDonnees->columnCount(); //je récupère le nombre de champs en BDD
	$content .= "<table class='table table-striped table-bordered table-responsive'><tr>"; 
	for($i = 0; $i < $nbCol; $i++)
	{
		$nomCol = $recupDonnees->getColumnMeta($i);//A chaque tour de boucle je récupère les intitulés de mes champs
		
		$content .= '<th>' . ucfirst($nomCol['name']) . '</th>';
	}
	$content .="<th>Modif.</th><th>Suppr.</th></tr>"; 

	while($recupArticles = $recupDonnees->fetch())
	{
		$content .= '<tr class="text-center">
		<td>' . $recupArticles['Identifiant'] . '</td>
		<td>' . $recupArticles['auteur'] . '</td>
		<td>' . $recupArticles['title'] . '</td>
		<td>' . $recupArticles['image'] . '</td>
		<td>' . $recupArticles['content'] . '</td>
		<td>' . $recupArticles['date_publication'] . '</td>
		<td class="text-center"><a class="glyphicon glyphicon-pencil" href="?pageAdmin=article&action=modif&id_article=' . $recupArticles['Identifiant'] . '"></a></td>'; 
		

			$content .= '<td class="text-center"><a onclick="return(confirm(\'Êtes-vous sûr de vouloir supprimer l\'article ' . $recupArticles['title'] . '?\' ))" class="glyphicon glyphicon-remove" href="?pageAdmin=article&action=suppr&aticle=' . $recupArticles['Identifiant'] . '"></a></td>'; 
		}

	$content .="</tr></table>";
}
else
{
	$content .="<h2>Bienvenue dans l'espace gestion des articles<small>Sélectionnez une action</small></h2><div class='row text-center'><a href='?pageAdmin=article&affichage=true' class='btn btn-primary'>Afficher les articles</a><a href='?pageAdmin=article&affichage=ajout' class='btn btn-primary'>Ajouter un article</a></div><hr>"; 
}