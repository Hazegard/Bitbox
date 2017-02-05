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
	music_path=$(echo $winner | cut -d ';' -f 1)
	music_name=$(echo $winner | cut -d';' -f 3)
	echo $music_name > /var/www/html/bitbox/bin/current_title
	echo -e $music_name >>/var/www/html/bitbox/bin/list_title
	omxplayer "${music_path}" & sleep $music_time;
done