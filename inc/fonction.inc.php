<?php

function debug($param,$mode = 1)
{
	if($mode == 1)
	{
		echo '<pre>';
			print_r($param);
		echo '</pre>';
	}
	else
	{
		echo '<pre>';
			var_dump($param);
		echo '</pre>';
	}
}


//fonction affichage civilité :
function civilite($arg)
{
	if($arg == "h")
	{
		return '<i class="fa fa-male" aria-hidden="true"></i>';
	}
	else
	{
		return '<i class="fa fa-female" aria-hidden="true"></i>';
	}
}

//fonction affichage statut :
function statut($arg)
{
	if($arg == 1)
	{
		return '<i class="fa fa-user-secret adminIcon" aria-hidden="true"></i>';
	}
	else
	{
		return '<i class="fa fa-user" aria-hidden="true"></i>';
	}
}


//vérification des champs :
function verifChamp($saisieUtilisateur,$pregMatch,$nomChamp,$caractAutor){
	global $erreur;
	if(!preg_match("$pregMatch",$saisieUtilisateur))
	{
		$erreur .= "<div class='alert alert-danger'>Erreur de format sur le champ $nomChamp. Les caractères suivants sont autorisés : $caractAutor</div>";
	}
	return $erreur;
}

//vérification code postal & téléphone :
function verifNum($saisie,$long,$intituleChamp){
	global $erreur;
	if(!preg_match('#^[0-9]{' . $long . '}$#',$saisie))
	{
		$erreur .= "<div class='alert alert-danger'>Le champ $intituleChamp n'est pas au bon format</div>";
	}
	return $erreur;
}

//vérif pseudo :
function verifPseudo($saisie,$min,$max,$intitule){
	global $erreur;
	global $pdo;

	if((iconv_strlen($saisie) >= $min) AND (iconv_strlen($saisie) <= $max))
	{
			if(!preg_match('#^[a-zA-Z0-9\'._-]+$#',$saisie))
			{
				$erreur .= "<div class='alert alert-danger'>Erreur de format au niveau du champ $intitule. Caractères autorisés : [a-zA-Z0-9'._-]</div>";
			}
			else
			{
				$verifPseudoBdd = $pdo->query("SELECT $intitule FROM membre WHERE $intitule='$saisie'");
				if($verifPseudoBdd->rowCount() > 0) //si le pseudo existe déjà en BDD
				{
					$erreur .= "<div class='alert alert-danger'>Le $intitule <b><i> $saisie </i></b> est indisponible</div>";
				}
			}
	}
	else //si longueur est incorrecte, on affiche un message d'erreur
	{
		$erreur .= "<div class='alert alert-danger'>Le champ $intitule doit être compris entre $min et $max caractères...</div>";
	}
	return $erreur;
}



//fonction token() :
function token(){
	global $content;
	$token = "";

	$maj = range('A','Z');
	$min = range('a','z');
	$num = range(0,50);
	$fusion = array_merge($maj,$min,$num);  //fusion cf. DBZ
	shuffle($fusion);
	foreach($fusion as $valeurs)
	{
			$token .= $valeurs;
	}
	return $token;
}
