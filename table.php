<p class="lead">
<?php
	include_once "/var/www/html/bitbox/libs/modele.php";
	include_once "/var/www/html/bitbox/libs/maLibForms.php";
if(isset($_COOKIE['Voted']))
{
	$Musiques = listerMusique("NbVote");
	mkTable($Musiques);
	echo "Vous avez voté pour :".$_COOKIE['Voted']."!";
}
else 
{
	$Musiques = listerMusique("NbVote");
	mkTable($Musiques);
}
if(file_exists('/var/www/html/bitbox/bin/current_title'))
{
	$current_title=file_get_contents('/var/www/html/bitbox/bin/current_title');
	echo "</br>";
	echo "Vous écoutez : ".$current_title;
}
?>
</p>