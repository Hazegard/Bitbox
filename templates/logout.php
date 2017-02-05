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
?>
<div class="page-header">
      <h1>Déconnexion</h1>
</div>
<p class="lead">
	Vous êtes maintenant déconnecté !
</p>