<?php

//C'est la propri�t� php_self qui nous l'indique :
// Quand on vient de index :
// [PHP_SELF] => /chatISIG/index.php
// Quand on vient directement par le r�pertoire templates
// [PHP_SELF] => /chatISIG/templates/accueil.php

// Si la page est appel�e directement par son adresse, on redirige en passant pas la page index
// Pas de soucis de bufferisation, puisque c'est dans le cas o� on appelle directement la page sans son contexte
if (basename($_SERVER["PHP_SELF"]) != "index.php")
{
	header("Location:../index.php?view=accueil");
	die("");
}
echo "<h1>Gestion de BitBox</h1>";
if($_SESSION)
{
	if(verifAdmin($_SESSION['pseudo'])==1)
	{
		mkForm("controleur.php");
		echo "Lancer les votes !"."</br>";
		mkinput("submit","action","Demarrer");
		echo "</br>"."</br>"."Recharge la base de musiques<br>";
		mkInput("submit","action","Recharger");
		echo "</br>"."</br>"."Eteint BitBox<br>";
		mkInput("submit","action","Eteindre");
		echo "</br>"."</br>"."Redémmarre BitBox<br>";
		mkInput("submit","action","Redémarrer");
		endForm();
		}

}
else 
{
  {echo "Zone interdite!";}
}
?>
