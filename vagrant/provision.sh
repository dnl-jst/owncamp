#!/bin/bash

# set mysql password so it isn't prompted during installation
debconf-set-selections <<< 'mysql-server-5.5 mysql-server/root_password password rootpass'
debconf-set-selections <<< 'mysql-server-5.5 mysql-server/root_password_again password rootpass'

# install postfix
debconf-set-selections <<< "postfix postfix/mailname string `hostname -f`"
debconf-set-selections <<< "postfix postfix/main_mailer_type string 'Internet Site'"

# update package lists
apt-get update

# install mysql, nginx, php5-fpm
apt-get install -y mysql-server mysql-client nginx ssl-cert postfix php5-fpm php5-mysql php5-curl php5-gd php5-intl php-pear php5-imagick php5-imap php5-mcrypt php5-memcached php5-cli

# generate snakeoil ssl certificate
make-ssl-cert generate-default-snakeoil

# copy nginx config
cp -f /vagrant/vagrant/default /etc/nginx/sites-available

# remove default html directory
rm -Rf /usr/share/nginx/html

# mount www-root
ln -s /vagrant/ /usr/share/nginx/html

# install composer
curl -sS https://getcomposer.org/installer | php
mv composer.phar /usr/local/bin/composer

# restart services
service nginx restart
service php5-fpm restart

echo "CREATE DATABASE IF NOT EXISTS owncamp;" | mysql -uroot -prootpass
