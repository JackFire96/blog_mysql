<?php
	require 'inc/init.inc.php';
	if($_GET)
	{
		if(isset($_GET['page']) AND ($_GET['page'] == 'index'))
		{
			header('location:' . URL);
		}


		//si l'indice page est passé en paramètre dans l'url et que le fichier correspondant à la valeur de l'indice existe
		if(isset($_GET['page']) AND file_exists($_GET['page'] . '.php'))
		{
			require ($_GET['page'] . '.php');
		}
		else if(isset($_GET['pageAdmin']) AND file_exists('admin/gestion_' . $_GET['pageAdmin'] . '.php'))
		{
			require ('admin/gestion_' . $_GET['pageAdmin'] . '.php');
		}
		else
		{
			$content .= "<div class='alert alert-danger'>La demande n'a pas pu aboutir</div>";
		}
	}

//déconnexion :
	if(isset($_GET['action']) AND ($_GET['action'] == "deconnexion"))
	{
		session_destroy();
		header('location:' . URL);
	}



	//formulaire de connexion :
	if(isset($_POST['connexion'])){

		$recupDonnees = $pdo->query("SELECT * FROM users WHERE email = '$_POST[email]'");

		if($recupDonnees->rowCount() != 0) //email existe
		{
			//je récupère les données sous forme de tableau associatif :
			$membre = $recupDonnees->fetch();

			//vérification du mot de passe :
			if(password_verify($_POST['mdp'],$membre['pswd']))
			{
				//debug($membre);
				$_SESSION['membre']['id_membre'] 			= $membre['id_user'];
				$_SESSION['membre']['pseudo'] 				= $membre['nickname'];
				$_SESSION['membre']['email'] 				= $membre['email'];
				$_SESSION['membre']['date_inscription']     = $membre['date_inscription'];
				$_SESSION['membre']['avatar'] 				= $membre['avatar'];
				$_SESSION['membre']['statut'] 				= $membre['status'];
				

				//redirection vers la page profil si les données sont correctes
				header('location:' . URL . '?page=profil');
			}
			else //mot de passe incorrect :
			{
				$content .= "<div class='alert alert-danger'>Votre mot de passe est incorrect</div>";
			}
		}
		else //l'email n'existe pas
		{
			$content .= "<div class='alert alert-danger'>Email n'existe pas</div>";
		}
	}

	//debug($_SESSION);

?>
<!DOCTYPE html>
<html lang="fr">
	<head>
		<title>Mon joli blog</title>
		<meta charset="utf-8">
		<meta name="author" content="">
		<meta name="description" content="">
		<meta name="viewport" content="width=device-width, initial-scale=1">
		<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css">
		<link rel="stylesheet" href="<?= URL;?>asset/css/style.css">

		<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script>
		<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>
		<script src="//cdn.ckeditor.com/4.8.0/full/ckeditor.js"></script>

	</head>
	<body>
		<!-- Modal -->
		<div id="myModal" class="modal fade" role="dialog">
			<div class="modal-dialog">

				<!-- Modal content-->
				<div class="modal-content">
					<div class="modal-header">
						<button type="button" class="close" data-dismiss="modal">&times;</button>
						<h4 class="modal-title text-center">Connectez-vous</h4>
					</div>
					<div class="modal-body">
						<div class="alert alert-danger">Incorrect</div>
						<form method="post" action="">
							<div class="form-group">
								<label for="email">Email</label>
								<input type="text" name="email" id="email" placeholder="Ici votre email" class="form-control">
							</div>
							<div class="form-group">
								<label for="mdp">Mot de passe</label>
								<input type="password" name="mdp" id="mdp" placeholder="Ici votre mot de passe" class="form-control">
							</div>
							<div class="row">
								<div class="checkbox col-xs-6">
									<label for="checkMemo">
										<input type="checkbox" id="checkMemo" name="checkMemo" value="">
										Se souvenir de moi
									</label>
								</div>
								<div class="col-xs-6 text-right">
									<a href="?page=mdp_oublie" class="btn btn-warning">Mot de passe oublié</a>
								</div>
							</div>
							<input type="submit" class="btn btn-primary btn-block" name="connexion" value="Se connecter">
						</form>
					</div>
					<div class="modal-footer">
						<button type="button" class="btn btn-default" data-dismiss="modal">Fermer</button>
					</div>
				</div>
			</div>
		</div>
		<nav class="navbar navbar-inverse">
			<div class="container-fluid">
				<div class="navbar-header">
					<a class="navbar-brand" href="<?= URL;?>">Mon joli blog</a>
				</div>
				<ul class="nav navbar-nav">
					<li><a href="?page=article">Article</a></li>
					<?php
					//s'il s'agit d'un admin
					if(isset($_SESSION['membre']) AND ($_SESSION['membre']['statut'] == 1)) : ?>
					<li><a href="?pageAdmin=membre">Gestion membres</a></li>
					<li><a href="?pageAdmin=article">Gestion articles</a></li>
					<li><a href="?pageAdmin=newsletter">Gestion newsletter</a></li>
					
					<?php endif; ?>
				</ul>
				<ul class="nav navbar-nav navbar-right">

					<?php
					//s'il s'agit d'un membre connecté, j'affiche le lien vers la page profil
					if(isset($_SESSION['membre'])) : ?>

					<li><a href="?page=profil"><span class="glyphicon glyphicon-user"></span>Profil</a></li>

					<?php endif;
					//s'il s'agit d'un simple visiteur on affiche le bouton se connecter. Dans le cas contraire, on affiche le bouton se déconnecter
					if(!isset($_SESSION['membre'])) : ?>
					<li><a href="?page=inscription"><span class="glyphicon glyphicon-thumbs-up"></span>S'inscrire</a></li>
					<li><a href="#" id="lienConnexion" data-toggle="modal" data-target="#myModal"><span class="glyphicon glyphicon-log-in"></span>Se connecter</a></li>
					<?php else :  ?>
					<li><a href="?action=deconnexion"><span class="glyphicon glyphicon-log-out"></span>Se déconnecter</a></li>
					<?php endif ; ?>
				</ul>
			</div>
		</nav>
		<div class="container-fluid">
			<?= $content;?>
		</div>
	<script src="<?= URL;?>asset/js/script.js"></script>
	<script src="https://use.fontawesome.com/81eb82c3aa.js"></script>
	</body>
</html>
