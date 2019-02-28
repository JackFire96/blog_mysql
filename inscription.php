<?php
if(isset($_SESSION['membre']))
{
	header('location:' . URL);
}
$pseudo = (isset($_POST['pseudo'])) ? $_POST['pseudo'] : null;
$mdp = (isset($_POST['mdp'])) ? $_POST['mdp'] : null;
$email = (isset($_POST['email'])) ? $_POST['email'] : null;
$avatar = (isset($_POST['avatar'])) ? $_POST['avatar'] : null;




if(isset($_POST['inscription'])){
	// debug($_POST);
	$erreur = "";//je déclare $erreur pour y stocker les erreurs
	extract($_POST);
    
    //verif avatar:
    $verifiExtension = array('jpg','png','gif','jpeg','JPG','PNG','GIF','JPEG');
    //$extension substr(strrchr($_FILES['avatar']['type'],'/'));
	//vérification :
	verifPseudo($pseudo,5,25,'pseudo'); //ok

//EMAIL :
	if(filter_var($email,FILTER_VALIDATE_EMAIL) === false)
	{
		$erreur .= "<div class='alert alert-danger'>Email incorrect</div>";
	}

$content .= $erreur;


//s'il n'y a aucune erreur on peut envoyer les données en BDD :
if(empty($erreur))
{
        $renomPhoto = ($_FILES['avatar']['name'] = rand(111111,999999));
		//crypter le mot de passe :
		$mdp = password_hash($mdp,PASSWORD_DEFAULT);

		$requeteInscription = $pdo->prepare('INSERT INTO users(nickname,email,pswd,avatar) VALUES(
					:nickname,
					:email,
					:pswd,
					:avatar
				)');
			$requeteInscription->execute([
				//htmlspecialchars() => faille XSS
				':nickname'				=>	htmlspecialchars($pseudo),
				':email'				=>	htmlspecialchars($email),
				':pswd'					=>	htmlspecialchars($mdp),
				':avatar'				=>	htmlspecialchars($avatar),
				]);
	}
}

$content .= '<h2 class="text-center">Page d\'inscription</h2>
			<form method="post" action="" enctype="multipart/form-data">
				<div class="form-group">
					<label for="pseudo">Pseudo</label>
					<input type="text" id="pseudo" name="pseudo" placeholder="Ici votre pseudo" class="form-control" value="">
				</div>

				<div class="form-group">
					<label for="mdp">Mot de passe</label>
					<input type="password" id="mdp" placeholder="Ici votre mot de passe" name="mdp" class="form-control" value="">
				</div>

				<div class="form-group">
					<label for="email">Email</label>
					<input type="email" id="email" placeholder="Ici votre email" name="email" class="form-control" value="">
				</div>

				<div class="form-group">
					<label for="avatar">Avatar</label>
					<input type="file" id="avatar" name="avatar" class="form-control" value="">
				</div>

				<div class="form-group">
					<input type="submit" name="inscription" class="form-control btn-primary" value="Inscription">
				</div>
			</form>';
