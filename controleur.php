<?php
session_start();

	include_once "libs/maLibUtils.php";
	include_once "libs/maLibSQL.pdo.php";
	include_once "libs/maLibSecurisation.php";
	include_once "libs/modele.php";

	$addArgs = "";

	if ($action = valider("action"))
	{
		ob_start ();
		echo "Action = '$action' <br />";
		// ATTENTION : le codage des caract�res peut poser PB si on utilise des actions comportant des accents...
		// A EVITER si on ne maitrise pas ce type de probl�matiques

		/* TODO: A REVOIR !!
		// Dans tous les cas, il faut etre logue...
		// Sauf si on veut se connecter (action == Connexion)

		if ($action != "Connexion")
			securiser("login");
		*/

		// Un param�tre action a �t� soumis, on fait le boulot...
		switch($action)
		{


			// Connexion //////////////////////////////////////////////////
			case 'Connexion' :
				// On verifie la presence des champs login et passe
				if ($login = valider("login"))
				if ($passe = valider("passe"))
				{
					// On verifie l'utilisateur,
					// et on cr�e des variables de session si tout est OK
					// Cf. maLibSecurisation
					if (verifUser($login,$passe)) {
						// tout s'est bien pass�, doit-on se souvenir de la personne ?
						if (valider("remember")) {
							setcookie("login",$login , time()+60*60*24*30);
							setcookie("passe",$password, time()+60*60*24*30);
							setcookie("remember",true, time()+60*60*24*30);
						} else {
							setcookie("login","", time()-3600);
							setcookie("passe","", time()-3600);
							setcookie("remember",false, time()-3600);
						}

					}
				}

				// On redirigera vers la page index automatiquement
			break;

			case 'Logout' :
				session_destroy();
			break;

			case 'Inscription':
			{

			}
			break;

			case 'Voter':
			if (($id=valider("vote")) && (!isset($_COOKIE["Voted"])))
			{			echo '<pre>'.print_r($id,true)."test".'</pre>';

				voterMusique($id);
				$duree=getDureeMusique($id);
				setcookie("Voted",$id,time()+$duree);
			}
			$addArgs = "?view=accueil";
			break;

			case 'Recharger':
			{
				$res =exec('/var/www/html/bitbox/bin/list.sh ');
				echo $res;
				$addArgs = "?view=adminpage";
			}
			break;
			case 'Demarrer':
			{
				$res =exec("nohup /var/www/html/bitbox/master.sh &");
				$addArgs = "?view=adminpage";
			}
			break;
			case 'Eteindre':
			{
				exec('sudo poweroff');
				$addArgs = "?view=adminpage";
			}
			break;
			case 'Redémarrer':
			{
				exec('sudo reboot');
			}
			break;
		}

	}

	// On redirige toujours vers la page index, mais on ne connait pas le r�pertoire de base
	// On l'extrait donc du chemin du script courant : $_SERVER["PHP_SELF"]
	// Par exemple, si $_SERVER["PHP_SELF"] vaut /chat/data.php, dirname($_SERVER["PHP_SELF"]) contient /chat

	$urlBase = dirname($_SERVER["PHP_SELF"]) . "/index.php";
	// On redirige vers la page index avec les bons arguments

	header("Location:" . $urlBase . $addArgs);

	// On �crit seulement apr�s cette ent�te
	ob_end_flush();

?>
