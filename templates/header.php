<?php

// Si la page est appel�e directement par son adresse, on redirige en passant pas la page index
if (basename($_SERVER["PHP_SELF"]) != "index.php")
{
	header("Location:../index.php");
	die("");
}

// On envoie l'ent�te Content-type correcte avec le bon charset
//header('Content-Type: text/html;charset=iso-8859-15');
header('Content-Type: text/html;charset=UTF-8');
// Pose qq soucis avec certains serveurs...
//echo "<?xml version=\"1.0\" encoding=\"iso-8859-15\" ?>";
echo "<?xml version=\"1.0\" encoding=\"UTF-8\" ?>";

?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">

<!-- **** H E A D **** -->
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-15" />
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<title>BitBox</title>
	<!-- <link rel="stylesheet" type="text/css" href="css/style.css"> -->
	<style type=”text/css”>
	table {
	margin: 8px;
	}
	th {
	font-family: Arial, Helvetica, sans-serif;
	font-size: .7em;
	background: #666;
	color: #FFF;
	padding: 2px 6px;
	border-collapse: separate;
	border: 1px solid #000;
	}
	td {
	font-family: Arial, Helvetica, sans-serif;
	font-size: .7em;
	border: 5px solid #DDD;
	}
	</style>
	<!-- Liaisons aux fichiers css de Bootstrap -->
	<link href="bootstrap/css/bootstrap.min.css" rel="stylesheet" />
	<link href="bootstrap/css/bootstrap-theme.min.css" rel="stylesheet" />
	<link href="css/sticky-footer.css" rel="stylesheet" />
	<!--[if lt IE 9]>
	  <script src="js/html5shiv.js"></script>
	  <script src="js/respond.min.js"></script>
	<![endif]-->

	<script src="js/jquery.js"></script>
	<script src="bootstrap/js/bootstrap.min.js"></script>
</head>
<!-- **** F I N **** H E A D **** -->


<!-- **** B O D Y **** -->
<body>

<!-- style inspir� de http://www.bootstrapzero.com/bootstrap-template/sticky-footer -->

<!-- Wrap all page content here -->
<div id="wrap">

  <!-- Fixed navbar -->
  <div class="navbar navbar-default navbar-fixed-top">
    <div class="container">
      <div class="navbar-header">
        <button type="button" class="navbar-toggle" data-toggle="collapse" data-target=".navbar-collapse">
          <span class="icon-bar"></span>
        </button>
	<a class="navbar-brand" href="index.php?view=accueil">BitBox</a>
      </div>
      <div class="collapse navbar-collapse">
        <ul class="nav navbar-nav">
         	<!-- <li class="active"><a href="index.php?view=accueil">Accueil</a></li> -->
	<!--	<?=mkHeadLink("Accueil","accueil",$view)?>
		<?=mkHeadLink("Bitbox","vote",$view)?>-->
		<?php
		// Si l'utilisateur n'est pas connecte, on affiche un lien de connexion
		if (!valider("connecte","SESSION"))
		  {
			echo mkHeadLink("Se connecter","login",$view);
					//	echo mkHeadLink("S'inscrire","signin",$view);

			}
			else {
			echo "<li class=\"\"> <a href=\"controleur.php?action=Logout\">Se Déconnecter</a></li>";
								echo mkHeadLink("Admin page","adminpage",$view);
			}
			//echo "<li><a href=\"index.php?view=login\">Se connecter</a></li>";
		?>
        </ul>
      </div><!--/.nav-collapse -->
    </div>
  </div>
  <!-- Begin page content -->
  <div class="container">