<?php
include_once("/var/www/html/bitbox/libs/maLibSQL.pdo.php");
$SQL ="UPDATE Liste_Musique SET Isproposed = 0";
SQLUpdate($SQL);
$SQL ="UPDATE Liste_Musique SET Nb_Vote = 0";
SQLUpdate($SQL);
$SQL ="UPDATE Liste_Musique SET IsProposed = 1 ORDER BY RAND() LIMIT 5";
SQLUpdate($SQL);
 ?>