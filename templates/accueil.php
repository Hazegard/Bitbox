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
<h1>Vote</h1>
<div id="tab">
	<?php include '/var/www/html/bitbox/table.php'; ?>
</div>
<div id="form">
	<?php include 'form.php' ?>
</div>
<script type="text/javascript">
var auto_refresh_table = setInterval(
  function ()
  {
    $('#tab').load('table.php').fadeIn("slow");
  }, 500); // rafraichis toutes les 500 millisecondes

	var auto_refresh_form = setInterval(
		function test_refresh()
	{
		$.get('bin/refresh_form')
			.done(function(){
			$('#form').load('form.php').fadeIn("slow");
			document.cookie = 'Voted'+'=;expires=Thu, 01 Jan 1970 00:00:01 GMT;';
		})
			.fail(function(){})
	}, 500); // rafraichis toutes les 500 millisecondes
</script>