Introduction
============

Dans les années 70, il était très courant de choisir sa musique dans les
bars avec l’utilisation des Juke-Boxes. Avec l’arrivée du numérique, les
lecteurs physiques ont disparu pour laisser place à de la musique
dématérialisée. De ce fait, les playlists ont partiellement pris la
place des Juke-boxes.

Cependant, avec l’arrivée des objets connectés, qui permettent
d’interconnecter des objets de la vie quotidienne, permettent une
collaboration plus poussée. C’est avec toutes ces idées que nous avons
commencé à réfléchir sur une manière de pouvoir choisir la musique qui
jouée. C’est ainsi qu’est né BitBox, le Jukebox des années 2010, afin de
rendre les fêtes collaboratives, permettant aux gens de voter pour la
musique qu’ils désirent écouter.

Mise en Situation
=================

Les environnements auxquels notre produit peut correspondre sont tous
ceux qui ont peuvent avoir de la musique et du publique. Par exemple, on
peut utiliser BitBox dans une grande fête ou même chez nous quand on est
avec nos amis et que l’on n’arrive pas à avoir un consensus sur les
chansons à jouer.

Matériaux Utilisés et Démarche
==============================

Pour concevoir BitBox, nous avons eu besoin d’un microcontrôleur
Raspberry Pi 3, dont les caractéristiques que nous utiliserons sont :

-   Présence du Wi-Fi intégré

-   Présence d’une prise Jack, qui permet d’y brancher directement des
    enceintes

De plus, notre objectif étant de permettre une utilisation autonome de
BitBox, nous nous sommes concentrés sur la création de deux pages web
principales:

-   La page permettant aux utilisateurs de voter

-   La page permettant au responsable de commander BitBox

Cette page doit donc contenir quelques actions afin de rendre BitBox
autonome:

-   Lister les musiques présentes sur le Raspberry

-   Démarrer les votes

-   Éteindre/redémarrer le Raspberry

Nous nous somme naturellement penchés sur une architecture
client/serveur, le Raspberry faisant office de serveur, et les
smartphones des utilisateurs les clients.

![Modèle de fonctionnement client-serveur](server.png)

Démarche technique
==================

Installation des différents composants
--------------------------------------

### Installation de mySQL

0pt 3pt</span>

~~~~ {style="Bash"}
sudo apt-get install php5 apache2 mysql-server
~~~~

Vous allez être invité à entrer un mot de passe pour gérer la base
MySQL:

![image](mysql.png)

### Installation de phpmyamin

0pt 3pt</span>

~~~~ {style="Bash"}
sudo apt-get install phpmyadmin
~~~~

Sélectionnez apache2:

![image](phpmyadmin.png)

Validez la configuration de la base de données de phpmyadmin par
dbconfig-common:

![image](phpmyadmin2.png)

Entrez le même mot de passe que celui entré lors de l’installation de
MySQL:

![image](phpmyadmin3.png)

### Installation de Bitbox

Décompressez l’archive bitbox.zip dans le dossier /var/html/www/

0pt 3pt</span>

~~~~ {style="PHPtest"}
cp BitBox.zip /var/www/html/
cd /var/www/html/
unzip BitBox.zip
~~~~

Modifier le fichier config.php:

0pt 3pt</span>

~~~~ {style="Bash"}
sudo nano /var/www/html/BitBox/config.php
~~~~

Et modifiez la ligne \$BDD\_password par le mot de passe que vous avez
entré précédemment:

0pt 3pt</span>

~~~~ {style="Bash"}
<?php
$BDD_host     ="localhost";
$BDD_user     ="root";
$BDD_password ="mysql";
$BDD_base     ="bitbox";
?>
~~~~

Le mot de passe par défaut d’administration de BitBox est ’admin’,
cependant il est possible de le modifier via l’interface phpmyadmin.

### Droit de l’user www-data

Afin d’executer les commandes via l’interface web, il est nécessaire
d’accorder certaines permissions à l’user www-data:

-   Le droit d’éteindre le Raspberry

-   le droit de redémarrer le Raspberry

-   le droit d’utiliser les applications média

0pt 3pt</span>

~~~~ {style="Bash"}
sudo adduser www-data audio
~~~~

0pt 3pt</span>

~~~~ {style="Bash"}
sudo echo 'www-data ALL = NOPASSWD: /sbin/reboot' >> /etc/sudoers
sudo echo 'www-data ALL = NOPASSWD: /sbin/poweroff' >> /etc/sudoers
~~~~

Mise en place du Wi-Fi
----------------------

La première étape est de paramétrer le Raspberry pour qu’il se comporte
en émetteur Wi-Fi. Pour se faire, nous devons modifier plusieurs
fichiers de configuration du Raspberry :

0pt 3pt</span>

~~~~ {style="Bash"}
sudo apt-get install hostapd isc-dhcp-server
~~~~

### Le serveur DHCP

Le premier fichier à modifier est le fichier de configuration du serveur
DHCP:

-0pt 5pt</span>

~~~~ {style="Bash"}
sudo nano /etc/dhcp/dhcpd.conf
~~~~

Commentez les lignes suivantes:

-0pt 5pt</span>

    #option domain-name "example.org";
    #option domain-name-servers ns1.example.org, ns2.example.org;

Décommenttez la ligne comme suit:

-0pt 5pt</span>

    # If this DHCP server is the official DHCP server for the local
    # network, the authoritative directive should be uncommented.
    authoritative;

Ajoutez ces lignes à la fin du fichier de configuration:

5pt 3pt</span>

    subnet 192.168.42.0 netmask 255.255.255.0 {
            range 192.168.42.10 192.168.42.50;
            option broadcast-address 192.168.42.255;
            option routers 192.168.42.1;
            default-lease-time 600;
            max-lease-time 7200;
            option domain-name "local";
            option domain-name-servers 8.8.8.8, 8.8.4.4;
    }

Enfin, modifiez le fichier isc-dhcp-server:

5pt 3pt</span>

~~~~ {style="Bash"}
sudo nano /etc/default/isc-dhcp-server
~~~~

Et remplacez :

5pt 3pt</span>

    INTERFACES=""

par:

5pt 3pt</span>

    INTERFACES="wlan0"

Cette première configuration permet au Raspberry de fournir les adresses
IP aux appareils qui vont se connecter à son réseau Wi-Fi

### Mise en place d’une IP statique

Tout d’abord, désactiver wlan0, dans le cas où celle-ci était en
fonctionnement:

5pt 3pt</span>

~~~~ {style="Bash"}
sudo ifdown wlan0
~~~~

Modifiez le fichier interfaces en retirant les configurations actuelles
de wlan0 et ajoutez-y:

5pt 3pt</span>

~~~~ {style="Bash"}
sudo nano /etc/network/interfaces
~~~~

5pt 3pt</span>

    auto lo

    iface lo inet loopback
    iface eth0 inet dhcp

    allow-hotplug wlan0
    iface wlan0 inet static
      address 192.168.42.1
      netmask 255.255.255.0

Assignez une adresse IP static au Wi-Fi par:

5pt 3pt</span>

~~~~ {style="Bash"}
sudo ifconfig wlan0 192.168.42.1
~~~~

### Configuration du point d’accès

Modifiez le fichier hostapd.conf, qui va nous permettre de donner un
ssid et un mot de passe au réseau Wi-Fi:

5pt 3pt</span>

~~~~ {style="Bash"}
sudo nano /etc/hostapd/hostapd.conf
~~~~

5pt 3pt</span>

    interface=wlan0
    driver=nl80211
    ssid=IntelAmbiant
    hw_mode=g
    channel=6
    macaddr_acl=0
    auth_algs=1
    ignore_broadcast_ssid=0
    wpa=2
    wpa_passphrase=Raspberry
    wpa_key_mgmt=WPA-PSK
    wpa_pairwise=TKIP
    rsn_pairwise=CCMP

Le driver nl80211 étant le driver Wi-Fi intégré au Raspberry Pi 3, si
vous utilisez une autre version de Raspberry, par exemple avec un
dongle, le driver à utiliser dépendra du modèle du dongle.Enfin,
modifiez le fichier:

5pt 3pt</span>

~~~~ {style="Bash"}
sudo nano /etc/default/hostapd
~~~~

Trouvez la ligne suivante:

5pt 3pt</span>

    #DAEMON_CONF=""

Et remplacez-la par:

5pt 3pt</span>

    DAEMON_CONF="/etc/hostapd/hostapd.conf"

De même dans /etc/init.d/hostapd:

5pt 3pt</span>

~~~~ {style="Bash"}
sudo nano /etc/init.d/hostapd
~~~~

Trouvez la ligne:

5pt 3pt</span>

    DAEMON_CONF=

Et remplacez-la par:

5pt 3pt</span>

    DAEMON_CONF=/etc/hostapd/hostapd.conf

Enfin, pour lancer le Wi-FI, il suffit d’executer:

8pt 3pt</span>

~~~~ {style="Bash"}
sudo /usr/sbin/hostapd /etc/hostapd/hostapd.conf
~~~~

Conclusion de l’installation
----------------------------

BitBox est ainsi opérationnel:

-   Tout les composants nécessaire au bon fonctionnement de BitBox on
    été installés

-   Les droits nécessaires au fonctionnement ont été ajouté à l’user
    www-data

-   Le réseau Wi-Fi, créé et géré par le Raspberry fonctionne

-   Afin dajouter de la music à BitBox, il faut la mettre dans le
    dossier /home/pi/Music/ du raspberry

Description technique
=====================

La base de données
------------------

La base de donnée est une table mySQL qui comporte les colonnes
suivantes:

   Title   Artist   Path   IsProposed   Album   Play\_Time   Play\_time\_string   Nb\_Vote
  ------- -------- ------ ------------ ------- ------------ -------------------- ----------

Où:

-   Isproposed est un booléen qui est à 1 lorsque le morceau est proposé
    au vote.

-   Nb\_vote est un entier qui correspond au nombre de votes obtenu par
    un morceau proposé

-   Play\_Time correspond à la durée en secondes

-   Play\_Time\_string correspond à la durée en minutes

Le stockage de la durée (Play\_Time et Play\_Time\_string) est
simplement effectué deux fois car la durée en seconde est nécessaire
pour calculer le temps d’attente du script, et le temps en minutes est
utilisé pour l’affichage aux utilisateurs. Cela permet de ne pas avoir à
convertir l’une ou l’autre de ces valeurs.

Le front-end
------------

Nous ne nous somme pas focalisé sur le front-end. Le seul aspect qui
nous importait était que celui-ci soit responsive, afin de permettre un
affichage correcte, tant sur un ordinateur que sur un smartphone (Voir
annexe [appendix:screenshots], page ).Ainsi, la page des votes comporte:

-   Le tableau qui contient les votes

-   Le formulaire qui permet de voter

-   Le titre de la musique en cours

-   Le vote effectué par l’utilisateur

La page d’aministration comporte quatre boutons qui permettent:

-   De recréer la base de données des musiques

-   De démarrer les votes

-   De redémarrer BitBox

-   D’éteindre BitBox

Différentes fonctions utilisées pour BitBox
-------------------------------------------

### La fonction List\_music.php

Voir annexe [appendix:listmusic], page pour le détail du code.

Cette fonction pHp se charge de lister toute les musiques (ici,
uniquement les fichiers mp3) présentes dans un dossier (ici
$/home/pi/Music/$) et, à l’aide du script getid3.php, on extrait les
tags de ces morceaux (Titre, artiste, album, durée). On insère ensuite
les tags, ainsi que le chemin d’accès dans une table mySQL.

### La fonction random\_music.php

Voir annexe [appendix:randommusic], page pour le détail du code.

Cette fonction met à jours différents champs dans la table de la manière
suivante:

-   Mise à 0 de la colonne Isproposed

-   Mise à 0 de la colonne Nb\_vote

-   Mise à 1 de 5 lignes de la colonne IsProposed de manière aléatoire

Cette fonction permet donc de recommencer un nouveau vote en
réinitialisant les variables du vote précédent et en sélectionnant 5
nouvelles musiques.

### La fonction get\_winner.php

Voir annexe [appendix:getwinner], page pour le détail du code.

Cette fonction récupère le titre, la durée, et le chemin d’accès du la
musique vainqueur, et le retourne sous la forme: $chemin;durée;titre$

### Le script master.sh

Voir annexe [appendix:master], page pour le détail du code.Ce script est
une boucle qui permet de gérer l’architecture du fonctionnement, c’est
lui qui est exécuté pour démarrer les votes.

Ce script a plusieurs fonctions:

-   Générer un trigger afin de rafraîchir le formulaire, nous verrons
    l’intérêt de cette fonction dans la partie [dynamiser] , page

-   Il appel la fonction php random\_music.php

-   Il appel la fonction get\_winner.php 30 secondes plus tard (afin de
    laisser du temps aux utilisateurs de voter)

-   Le script stocke les 3 données renvoyées par get\_winner dans
    \$music\_time, \$music\_path et \$music\_name

-   On insère le titre qui va être joué dans un fichier afin de pouvoir
    monitorer les musiques qui ont été.

-   Le script exécute ensuite omxplayer “\$<span>music\_path</span>”,
    qui permet de commencer la lecture du morceau.

-   Le script attend en parallèle la durée \$music\_time (qui correspond
    à la durée du morceau moins 30 secondes), et recommence la boucle.

### Gestion de la triche

Afin d’assurer une équité dans les votes, nous avons ajouté une fonction
qui permet d’éviter qu’une même personne ne vote plusieurs fois pour une
même musique:

10pt 3pt</span>

~~~~ {style="PHPtest"}
if (($id=valider("vote")) && (!isset($_COOKIE["Voted"])))
{   
    voterMusique($id);
    $duree=getDureeMusique($id);
    setcookie("Voted",$id,time()+$duree);
}
~~~~

Ainsi, lorsque l’utilisateur clique sur le bouton voter, dans le cas où
l’utilisateur possède une Cookie, alors il ne se passe rien. Dans le cas
contraire, le vote est enregistré et une cookie de la durée du morceau
est ajoutée, l’empêchant alors de revoter pour ce tour-ci.

D’autres parts, afin de s’assurer que les utilisateurs ont bien 30
secondes pour voter, la Cookie est réinitialisée lorsque le trigger
s’exécute.

Dynamisation du site {#dynamiser}
--------------------

Il faut différencier deux script qui se chargent de dynamiser la page:

-   La fonction qui permet de mettre à jour le tableau des résultats des
    votes: ce tableau est mis à jour toutes les 500 millisecondes.

-   La fonction qui permet de mettre à jour le formulaire de vote.

Cependant, si le premier script est simple à exécuter (toutes les 500
millisecondes), le deuxième est plus complexe car si le formulaire est
mis à jour lorsqu’un utilisateur est en train de voter, celui-ci se
réinitialise, il faut donc éviter ce cas de figure.

L’idée a donc été de réinitialiser le formulaire une seule fois: au
moment de la mise en place d’un nouveau vote. Cette phase étant gérée
par le script master.sh, il a fallut trouver une manière de communiquer
entre master.sh et le script de rafraîchissement: nous avons donc décidé
de réaliser cette communication par la création d’un fichier
($refresh\_form$), qui existe pendant une seconde au moment d’un nouveau
vote, créé par master.sh, et par le test de l’existence de ce fichier
toute les 500 millisecondes par le javascript.

Ce script permet également de supprimer à tous les utilisateurs la
Cookie empêchant de voter plusieurs fois pour un même tour.

Au démarrage
------------

Au démarrage de BitBox, il faut exécuter deux actions:

-   Exécuter le script qui permet d’activer le réseau Wi-Fi

-   Rafraîchir la liste des musiques

Pour cela, nous avons recours au Crontab:

8pt 3pt</span>

~~~~ {style="Bash"}
sudo crontab -e
~~~~

Dans lequel nous ajoutons:

8pt 3pt</span>

~~~~ {style="Bash"}
@reboot sudo /usr/sbin/hostapd /etc/hostapd/hostapd.conf
@reboot /var/www/html/bitbox/bin/listOnBoot.sh
~~~~

Avec $listOnBoot.sh$ qui est:

8pt 3pt</span>

~~~~ {style="Bash"}
#!/bin/bash
sleep 60
sudo rm /var/www/html/bitbox/bin/current_title
omxplayer /var/www/html/bitbox/ressources/start_Listing.mp3
php /var/www/html/bitbox/list.pHp
omxplayer /var/www/html/bitbox/ressources/end_Listing.mp3
~~~~

Ce script va donc:

-   Lancer une voix : “Analyse des musiques en cours”

-   Rafraîchir la liste des musiques

-   Lancer une voix: “Analyse terminée”

Le $sleep~60$ en début de script par d’un constat: au démarrage, sans un
sleep, il est possible que le script dans le crontab s’exécute trop tôt,
c’est à dire avant que mySQL ne soit opérationnel. Ainsi, ce script ne
pouvait pas insérer les musique dans la base de donnée. D’autre part, le
temps de démarrage étant variable, il a fallut prendre une marge de
sécurité afin de s’assurer qu’il n’y allait pas avoir de problème.

Conclusion
==========

BitBox correspond à nos attentes, car il répond au besoin que nous
avions imaginé avant de démarrer ce projet. Il est ainsi entièrement
fonctionnel, et peut surtout être utilisé de manière autonome, sans
avoir besoin de contrôler le Raspberry, que ce soit par l’utilisation
d’un clavier physique branché au Raspberry, ou par la connexion en SSH
d’un appareil externe.

Cependant, nous sommes conscient qu’il y a de nombreuses améliorations
possible à BitBox, nous les avons donc listés pour montrer les
possibilités auxquelles nous avons pensé pour BitBox.

 Piste d’amélioration
=====================

Les princpales pistes d’amélioration sont:

-   Meilleure gestion des votes (ex: remplacer l’utilisation d’une
    Cookie par une gestion niveau serveur des votes effectués)

-   Meilleure gestion du démarrage ex: utiliser init.d à la place de
    crontab

-   Rendre l’interface graphique plus joli, avec par exemple des
    diagrammes pour représenter les proportions du vote en cours

-   Ajout de commandes d’administration:

    -   Gestion du volume

    -   Permettre de passer au vote suivant

    -   Permettre de mettre en pause la musique ou les votes

-   Permettre aux utilisateurs d’ajouter leurs propres musiques à la
    base de donnée de BitBox

Fonction list\_music.php {#appendix:listmusic}
========================

10pt 3pt</span>

~~~~ {style="PHPtest"}
<?php
include "/var/www/html/bitbox/getid3/getid3.php";
include "/var/www/html/bitbox/libs/modele.php";
$BDD_host="localhost";
$BDD_base="bitbox";
$BDD_user="root";
$BDD_password="mysql";
$dir = "/home/pi/Music/";
  if (is_dir($dir)) {
    mysql_connect($BDD_host,$BDD_user,$BDD_password) or die("<font color=\"red\">SQLInsert: Erreur de connexion : " . mysql_error() . "</font>");
    mysql_set_charset('utf8');
    mysql_select_db($BDD_base) or die ("<font color=\"red\">SQLInsert: Erreur select db : 
    " . mysql_error() . "</font>");
    mysql_query("TRUNCATE TABLE Liste_Musique") or die("SQLInsert: Erreur sur la requete :
    <font color=\"red\">$sql" . "|". mysql_error() . "</font>");
     // si il contient quelque chose
     if ($dh = opendir($dir)) {
         $getid3 = new getid3;
         // boucler tant que quelque chose est trouve
         while (($file = readdir($dh)) !== false) {
             // affiche le nom et le type si ce n'est pas un element du systeme
             if( $file != '.' && $file != '..' && preg_match('#\.(mp3)$#i', $file))
             {
              $details = $getid3->analyze($dir.$file);
              $title = $details['tags']['id3v2']['title'][0];
              $artist = $details['tags']['id3v2']['artist'][0];
              $playtime= ($details['playtime_seconds']);
              $playtime_string = $details['playtime_string'];
              $album = $details['tags']['id3v2']['album'][0];
              $path = $dir.$file;
              addMusic("'".$title."'","'".$artist."'","'".$album."'","'".$playtime."'","'".$path."'","'".$playtime_string."'");
             }
         }
         closedir($dh);
     }
  }
?>
~~~~

Fonction random\_music.php {#appendix:randommusic}
==========================

10pt 3pt</span>

~~~~ {style="PHPtest"}
<?php
include_once("/var/www/html/bitbox/libs/maLibSQL.pdo.php");
$SQL="UPDATE Liste_Musique SET Isproposed = 0";
SQLUpdate($SQL);
$SQL="UPDATE Liste_Musique SET Nb_Vote = 0";
SQLUpdate($SQL);
$SQL="UPDATE Liste_Musique SET IsProposed = 1 ORDER BY RAND() LIMIT 5";
SQLUpdate($SQL);
 ?>
~~~~

Fonction get\_winner.php {#appendix:getwinner}
========================

10pt 3pt</span>

~~~~ {style="PHPtest"}
<?php
include_once "/var/www/html/bitbox/libs/maLibSQL.pdo.php";
include_once "/var/www/html/bitbox/libs/modele.php";
$SQL_path = "SELECT path FROM Liste_Musique WHERE (Nb_Vote= (SELECT MAX(Nb_VOTE) FROM Liste_Musique)) AND Isproposed = 1";
$res_path=SQLGetChamp($SQL_path);
$SQL_time = "SELECT Play_Time FROM Liste_Musique WHERE (Nb_Vote= (SELECT MAX(Nb_VOTE) FROM Liste_Musique)) AND Isproposed = 1";
$res_time=SQLGetChamp($SQL_time);
$SQL_name = "SELECT Title FROM Liste_Musique WHERE (Nb_Vote = (SELECT MAX(Nb_Vote) FROM Liste_Musique)) AND Isproposed = 1";
$res_title = SQLGetChamp($SQL_name);
echo $res_path;
echo ";";
echo $res_time-30;
echo ";";
echo $res_title;
 ?>
~~~~

Le script master.sh {#appendix:master}
===================

10pt 3pt</span>

~~~~ {style="Bash"}
#!/bin/bash
rm /var/www/html/bitbox/bin/list_title
rm /var/www/html/bitbox/bin/refresh_form
rm /var/www/html/bitbox/bin/current_title
while true
do
php /var/www/html/bitbox/random_music.php
touch /var/www/html/bitbox/bin/refresh_form
sleep 1
rm /var/www/html/bitbox/bin/refresh_form
sleep 29
winner=$(php /var/www/html/bitbox/get_winner.php)
music_time=$(echo $winner | cut -d ';' -f 2)
echo $music_time
music_path=$(echo $winner | cut -d ';' -f 1)
echo $music_path
music_name=$(echo $winner | cut -d';' -f 3)
echo $music_name > /var/www/html/bitbox/bin/current_title
echo -e $music_name >>/var/www/html/bitbox/bin/list_title
omxplayer "${music_path}" & sleep $music_time;
done
~~~~

Le javascript {#appendix:javascript}
=============

10pt 3pt</span>

~~~~ {style="PHPtest"}
var auto_refresh_table = setInterval(
function ()
{
 $('#tab').load('table.php').fadeIn("slow");
}, 500);

var auto_refresh_form = setInterval(
function test_refresh()
{
 $.get('bin/refresh_form')
  .done(function(){
    $('#form').load('form.php').fadeIn("slow");
    document.cookie = 'Voted'+'=;expires=Thu, 01 Jan 1970 00:00:01 GMT;';
  })
  .fail(function(){})
}, 500);
~~~~

Front {#appendix:screenshots}
=====

[c]<span>0.45</span> ![Page des votes](vote.png "fig:") [fig:vote]

[c]<span>0.45</span> ![Page d’administration](admin.png "fig:")
[fig:admin]

Références
==========

<https://learn.adafruit.com/setting-up-a-raspberry-pi-as-a-wifi-access-point/install-software><http://getid3.sourceforge.net/>

[lastPage]