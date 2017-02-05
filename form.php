<?php
include_once "libs/modele.php";
 include_once "libs/maLibForms.php";
{
	$Musique_Sorted_Name=listerMusique("name");
	mkForm("controleur.php");
	mkSelect("vote",$Musique_Sorted_Name,"Title","Title");
	mkInput("submit","action","Voter");
	endForm();
}
?>
