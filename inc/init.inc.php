<?php
//session : 
session_start(); 

error_reporting(E_ALL); //affichera toutes les erreurs. A commenter en prod
ini_set('display_errors','On');//forcer l'affichage des erreurs. Important pour contourner la configuration du fichier php.ini

//connexion à la BDD : 
//configuration de base : 
$bddOptions = array(
	PDO::MYSQL_ATTR_INIT_COMMAND	=> "SET NAMES utf8", //on force l'encodage en utf8
	PDO::ATTR_DEFAULT_FETCH_MODE	=> PDO::FETCH_ASSOC, //on récupère les résultats sous forme de tableau associatif
	PDO::ATTR_ERRMODE				=> PDO::ERRMODE_WARNING //on affiche les erreurs de type warning. Cette instruction sera à commenter en prod

);

define('TYPEBDD','mysql'); //type de la BDD
define('HOST','localhost'); //domaine du serveur
define('USER','jack');//nom de l'utilisateur
define('PASSWORD','Ja29co11po96@');//mot de passe
define('DBNAME','blog');//nom de la bdd

try{ //on essaie de se connecter à la bdd
	$pdo = new PDO(TYPEBDD . ':host=' . HOST . ';dbname=' . DBNAME,USER,PASSWORD,$bddOptions);
}
catch(Exception $e){//sinon on fait ce code
	die('Erreur BDD : ' . $e->getMessage());
}	




//constantes : 
define('URL',$_SERVER['REQUEST_SCHEME'] . '://' . $_SERVER['HTTP_HOST']); 
define('RACINE',$_SERVER['DOCUMENT_ROOT']);


//variable d'affichage : 
$content = ""; 

//fonctions : 
require 'fonction.inc.php'; 

