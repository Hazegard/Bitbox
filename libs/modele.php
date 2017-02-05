<?php


// inclure ici la librairie faciliant les requêtes SQL
include_once("/var/www/html/bitbox/libs/maLibSQL.pdo.php");

function listerUtilisateurs($classe = "both")
{
	// NB : la présence du symbole '=' indique la valeur par défaut du paramètre s'il n'est pas fourni
	// Cette fonction liste les utilisateurs de la base de données
	// et renvoie un tableau d'enregistrements.
	// Chaque enregistrement est un tableau associatif contenant les champs
	// id,pseudo,blacklist,connecte,couleur
	// Lorsque la variable $classe vaut "both", elle renvoie tous les utilisateurs
	// Lorsqu'elle vaut "bl", elle ne renvoie que les utilisateurs blacklistés
	// Lorsqu'elle vaut "nbl", elle ne renvoie que les utilisateurs non blacklistés

	$SQL = "select * from users";
	if ($classe == "bl")
		$SQL .= " where blacklist=1";
	if ($classe == "nbl")
		$SQL .= " where blacklist=0";
	// echo $SQL;
	return parcoursRs(SQLSelect($SQL));
}

function listerMusique($sort,$classe = "both")
{
	// NB : la présence du symbole '=' indique la valeur par défaut du paramètre s'il n'est pas fourni
	// Cette fonction liste les utilisateurs de la base de données
	// et renvoie un tableau d'enregistrements.
	// Chaque enregistrement est un tableau associatif contenant les champs
	// id,pseudo,blacklist,connecte,couleur
	// Lorsque la variable $classe vaut "both", elle renvoie tous les utilisateurs
	// Lorsqu'elle vaut "bl", elle ne renvoie que les utilisateurs blacklistés
	// Lorsqu'elle vaut "nbl", elle ne renvoie que les utilisateurs non blacklistés

if($sort=="name")
{
	$SQL = "SELECT Title,Artist,Album,Play_Time_string,Nb_Vote FROM Liste_Musique
	WHERE IsProposed= true ORDER BY Title ASC";
}
else{
	$SQL = "SELECT Title,Artist,Album,Play_Time_string,Nb_Vote FROM Liste_Musique
	WHERE IsProposed= true ORDER BY Nb_Vote DESC";
}
	//if ($classe == "bl")
	//$SQL .= " where IsProposed=false";
	//if ($classe == "nbl")
	//	$SQL .= " where blacklist=0";
	// echo $SQL;
	return parcoursRs(SQLSelect($SQL));
}

function voterMusique($title)
{
	if(!isset($_COOKIE['Voted'])){
	$SQL = "UPDATE Liste_Musique set Nb_Vote = Nb_Vote + 1 WHERE Title = '$title'";
	SQLUpdate($SQL);
}
}

function getDureeMusique($title)
{
	$SQL = "SELECT Play_Time FROM Liste_Musique WHERE Title = '$title'";
	$duree=SQLGetChamp($SQL);
	return $duree;
}
function interdireUtilisateur($idUser)
{
	// cette fonction affecte le booléen "blacklist" à vrai
	$SQL = "UPDATE users SET blacklist=1 WHERE id='$idUser'";
	// les apostrophes font partie de la sécurité !!
	// Il faut utiliser addslashes lors de la récupération
	// des données depuis les formulaires

	SQLUpdate($SQL);
}

function autoriserUtilisateur($idUser)
{
	// cette fonction affecte le booléen "blacklist" à faux
	$SQL = "UPDATE users SET blacklist=0 WHERE id='$idUser'";
	SQLUpdate($SQL);
}

function verifUserBdd($login,$passe)
{
	$SQL="SELECT id FROM users WHERE pseudo='$login' AND passe='$passe'";
	return SQLGetChamp($SQL);
	// si on avait besoin de plus d'un champ
	// on aurait du utiliser SQLSelect
}

function addUserBdd($login,$passe){
	$SQL = "INSERT into user ('id','pseudo','passe','admin','connecte') VALUES  ('',$login, $passe, 0, 1)";
SQLInsert($SQL);
}

function addMusic($title,$artist,$album,$playtime,$path,$playtime_string){
	$SQL = "INSERT into Liste_Musique (Title,Artist,Path,IsProposed,Album,Play_Time,Play_Time_string,Nb_Vote)
	VALUES ($title,$artist,$path,0,$album,$playtime,$playtime_string,0)";
SQLInsertMusique($SQL);
}
function verifAdmin($login){
	$SQL = "SELECT admin FROM users WHERE pseudo = '$login'";
	$test=SQLGetChamp($SQL);
	if ($test==1)
	{
		return 1;
	}
	else
	{
		return 0;
	}
}
?>