#!/bin/bash
sleep 20
sudo rm /var/www/html/bitbox/bin/current_title
omxplayer /var/www/html/bitbox/ressources/start_Listing.mp3
php /var/www/html/bitbox/list.php
omxplayer /var/www/html/bitbox/ressources/end_Listing.mp3