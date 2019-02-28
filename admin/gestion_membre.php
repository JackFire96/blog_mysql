<?php
if(!isset($_SESSION['membre']) || (!$_SESSION['membre']['status'] == 1))
{
	header('location:' . URL);
}
$content .= "<h3 class='text-center'>Page gestion membres</h3><hr>"; 






//debug($_SESSION);
//MODIFICATION : 

if(isset($_GET['action']) AND $_GET['action'] == "modif")
{
	//requete pour récupérer les données utilisateurs : 
	$recupInfosMembre = $pdo->query("SELECT * FROM users WHERE id_user= $_GET[id_membre]");
	
	//je récupère les données sous forme de tableau associatif
	$affichageInfosMembre = $recupInfosMembre->fetch(); 

	//conditions ternaires : 
	$statutAdmin 	= ($affichageInfosMembre['status'] == 1) ? 'selected' : null;
	$statutMembre 	= ($affichageInfosMembre['status'] == 0) ? 'selected' : null;
	
	$content .='<div class="panel panel-info">
					<div class="panel-heading">
					<h4>Modification des informations relatives à ' . ucfirst($affichageInfosMembre['nickname']) . '</h4>
					</div>
					<div class="panel-body">
						<form method="POST" action="">
						
						<label for="statut">Votre statut</label>
						<div class="form-group">'; 
						
						if($_SESSION['membre']['id_user'] == $affichageInfosMembre['id_user'])
						{
							$content .= '<p>Vous êtes administrateur</p>'; 						
						}
						else
						{
							$content .= '
								<select name="statut" id="statut" class="form-control">
									<option value="1"' . $statutAdmin . '>Administrateur</option>
									<option value="0"' . $statutMembre . '>Simple membre</option>
								</select>';
						}
					$content.='
						</div>
						<div class="form-group">
							<label for="pseudo">Pseudo</label>
							<input type="text" id="pseudo" name="pseudo" class="form-control" value="' . $affichageInfosMembre['nickname'] . '">
						</div>
						
						<div class="form-group">
							<label for="email">Email</label>
							<input type="text" id="email" name="email" class="form-control" value="' . $affichageInfosMembre['email'] . '">
						</div>						
						<div class="form-group">
							<label for="avatar">Avatar</label>
							<input type="file" id="avatar" name="avatar" class="form-control" value="' . $affichageInfosMembre['avatar'] . '">
						</div>	
						<input type="submit" value="Modification" name="update" class="btn btn-large btn-info form-control">


						
					</form>
					</div>';
		$date1 = 	date('Y-m-d H:i:s'); 
		$date2 = date($affichageInfosMembre['date_inscription']);
		$content .=$date2;
		$content .=$date1;
$nbjours = round((strtotime($date2) - strtotime($date1))/(60*60*24)-1); 
$content .= $nbjours;
}
/*
afficher le temps écoulé depuis la date d'inscription de l'utilisateur ! (année, mois, heures, minutes et secondes)
*/








//SUPPRESSION : 
if(isset($_GET['action']) AND $_GET['action'] == "suppr")
{
	if($_SESSION['membre']['id_user'] == $_GET['id_membre'])
	{
		$content .= '<div class="alert alert-danger">Vous ne pouvez pas vous auto-supprimer</div>';		
	}
	else
	{
		$pdo->query('DELETE FROM `membre` WHERE is_user = "' . $_GET['id_membre'] . '"');
		header('location:' . URL . '?pageAdmin=membre&suppr=ok&id_membre=' . $_GET['id_membre']);//redirection + stockage infos dans l'url pour gérer l'affichage de la suppression
	}
}
if(isset($_GET['suppr']) AND $_GET['suppr'] == "ok") //message suppression membre
{
	$content .= '<div class="alert alert-success">Le membre '. $_GET['id_membre'] . ' a bien été supprimé !</div>';	
}









//si dans l'url affichage == true : affichage du tableau
if(isset($_GET['affichage']) AND $_GET['affichage'] == 'true')
{
	$recupDonnees = $pdo->query('SELECT id_user AS "Identifiant",email,statut,avatar,DATE_FORMAT(date_inscription,"%d-%m-%Y") AS "Date d\'inscription" FROM users'); 

	$nbCol = $recupDonnees->columnCount(); //je récupère le nombre de champs en BDD
	$content .= "<table class='table table-striped table-bordered table-responsive'><tr>"; 
	for($i = 0; $i < $nbCol; $i++)
	{
		$nomCol = $recupDonnees->getColumnMeta($i);//A chaque tour de boucle je récupère les intitulés de mes champs
		
		$content .= '<th>' . ucfirst($nomCol['name']) . '</th>';
	}
	$content .="<th>Modif.</th><th>Suppr.</th></tr>"; 

	while($recupInfoMembre = $recupDonnees->fetch())
	{
		$content .= '<tr class="text-center">
		<td>' . $recupInfoMembre['Identifiant'] . '</td>
		<td>' . $recupInfoMembre['nickname'] . '</td>
		<td>' . $recupInfoMembre['email'] . '</td>
		<td>' . statut($recupInfoMembre['statut']) . '</td>
		<td>' . $recupInfoMembre['avatar'] . '</td>
		<td>' . $recupInfoMembre['Date d\'inscription'] . '</td>
		<td class="text-center"><a class="glyphicon glyphicon-pencil" href="?pageAdmin=membre&action=modif&id_membre=' . $recupInfoMembre['Identifiant'] . '"></a></td>'; 
		
		
		//l'admin connecté ne peut pas s'auto-supprimer !!
		if($_SESSION['membre']['id_membre'] == $recupInfoMembre['Identifiant'])
		{
			$content .= '<td class="text-center "><i class="fa fa-ban interditionSuppr" aria-hidden="true"></i></td>';  
		}
		else
		{
			$content .= '<td class="text-center"><a onclick="return(confirm(\'Êtes-vous sûr de vouloir supprimer le profil de ' . $recupInfoMembre['nickname'] . '?\' ))" class="glyphicon glyphicon-remove" href="?pageAdmin=membre&action=suppr&id_membre=' . $recupInfoMembre['Identifiant'] . '"></a></td>'; 
		}
	}
	$content .="</tr></table>"; 
}
else
{
	$content .="<h2>Bienvenue dans l'espace gestion des membres<small>Sélectionnez une action</small></h2><div class='row text-center'><a href='?pageAdmin=membre&affichage=true' class='btn btn-primary'>Afficher les membres</a><a href='?pageAdmin=membre&affichage=ajout' class='btn btn-primary'>Ajouter un membre</a></div><hr>"; 
}
	
	
	
	
	
	
	
	




