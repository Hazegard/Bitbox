<?php
include "/var/www/html/bitbox/getid3/getid3.php";
include "/var/www/html/bitbox/libs/modele.php";
$BDD_host     ="localhost";
$BDD_base     ="bitbox";
$BDD_user     ="root";
$BDD_password ="mysql";
$dir          = "/home/pi/Music/Troll/";
  if (is_dir($dir)) {
    mysql_connect($BDD_host,$BDD_user,$BDD_password) or die("<font color=\"red\">SQLInsert: Erreur de connexion : " . mysql_error() . "</font>");
    mysql_set_charset('utf8');
    mysql_select_db($BDD_base) or die ("<font color=\"red\">SQLInsert: Erreur select db : " . mysql_error() . "</font>");
    mysql_query("TRUNCATE TABLE Liste_Musique") or die("SQLInsert: Erreur sur la requete : <font color=\"red\">$sql" . "|". mysql_error() . "</font>");
     // si il contient quelque chose
     if ($dh = opendir($dir)) {
         						 $getid3 = new getid3;
         // boucler tant que quelque chose est trouve
         while (($file = readdir($dh)) !== false) {
             // affiche le nom et le type si ce n'est pas un element du systeme
             if( $file != '.' && $file != '..' && preg_match('#\.(mp3)$#i', $file)) {
              $details         = $getid3->analyze($dir.$file);
              $title           = $details['tags']['id3v2']['title'][0];
              $artist          = $details['tags']['id3v2']['artist'][0];
              $playtime        = ($details['playtime_seconds']);
              $playtime_string = $details['playtime_string'];
              $album           = $details['tags']['id3v2']['album'][0];
              $path            = $dir.$file;
              addMusic("'".$title."'","'".$artist."'","'".$album."'","'".$playtime."'","'".$path."'","'".$playtime_string."'");
             }
         }
         closedir($dh);
     }
  }
?>