<?php
session_start();

/*
Cette page g�n�re les diff�rentes vues de l'application en utilisant des templates situ�s dans le r�pertoire "templates". Un template ou 'gabarit' est un fichier php qui g�n�re une partie de la structure XHTML d'une page.

La vue � afficher dans la page index est d�finie par le param�tre "view" qui doit �tre plac� dans la cha�ne de requ�te. En fonction de la valeur de ce param�tre, on doit v�rifier que l'on a suffisamment de donn�es pour inclure le template n�cessaire, puis on appelle le template � l'aide de la fonction include

Les formulaires de toutes les vues g�n�r�es enverront leurs donn�es vers la page data.php pour traitement. La page data.php redirigera alors vers la page index pour r�afficher la vue pertinente, g�n�ralement la vue dans laquelle se trouvait le formulaire.
*/


	include_once "libs/maLibUtils.php";
	include_once "libs/maLibBootstrap.php";
	include_once "libs/modele.php";
	include_once "libs/maLibForms.php";




	// on r�cup�re le param�tre view �ventuel
	$view = valider("view");
	/* valider automatise le code suivant :
	if (isset($_GET["view"]) && $_GET["view"]!="")
	{
		$view = $_GET["view"]
	}*/

	// S'il est vide, on charge la vue accueil par d�faut
	if (!$view) $view = "accueil";

	// NB : il faut que view soit d�fini avant d'appeler l'ent�te
	// Dans tous les cas, on affiche l'entete,
	// qui contient les balises de structure de la page, le logo, etc.
	// Le formulaire de recherche ainsi que le lien de connexion
	// si l'utilisateur n'est pas connect�

	include("templates/header.php");

	// En fonction de la vue � afficher, on appelle tel ou tel template
	switch($view)
	{
		case "accueil" :
			include("templates/accueil.php");
		break;
		default : // si le template correspondant � l'argument existe, on l'affiche
			if (file_exists("templates/$view.php"))
				include("templates/$view.php");
	}
	// Dans tous les cas, on affiche le pied de page
	// Qui contient les coordonn�es de la personne si elle est connect�e
	include("templates/footer.php");
?>