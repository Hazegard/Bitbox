<?php
include_once "/var/www/html/bitbox/libs/maLibSQL.pdo.php";
include_once "/var/www/html/bitbox/libs/modele.php";
$SQL_path  = "SELECT path FROM Liste_Musique WHERE (Nb_Vote= (SELECT MAX(Nb_VOTE) FROM Liste_Musique)) AND Isproposed = 1";
$res_path  =SQLGetChamp($SQL_path);
$SQL_time  = "SELECT Play_Time FROM Liste_Musique WHERE (Nb_Vote= (SELECT MAX(Nb_VOTE) FROM Liste_Musique)) AND Isproposed = 1";
$res_time  =SQLGetChamp($SQL_time);
$SQL_name  = "SELECT Title FROM Liste_Musique WHERE (Nb_Vote = (SELECT MAX(Nb_Vote) FROM Liste_Musique)) AND Isproposed = 1";
$res_title = SQLGetChamp($SQL_name);
echo $res_path;
echo ";";
echo $res_time-30;
echo ";";
echo $res_title;
 ?>