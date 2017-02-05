#/bin/bash
touch /var/www/html/bitbox/bin/wifi_start
sudo /usr/sbin/hostapd /etc/hostapd/hostapd.conf
touch /var/www/html/bitbox/bin/wifi_started