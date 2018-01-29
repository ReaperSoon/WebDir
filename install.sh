#!/bin/bash

# Reset
Color_Off='\033[0m'       # Text Reset

# Regular Colors
Black='\033[0;30m'        # Black
Red='\033[0;31m'          # Red
Green='\033[0;32m'        # Green
Yellow='\033[0;33m'       # Yellow
Blue='\033[0;34m'         # Blue
Purple='\033[0;35m'       # Purple
Cyan='\033[0;36m'         # Cyan
White='\033[0;37m'        # White

if [[ $EUID -ne 0 ]]; then # we can compare directly with this syntax.
  printf "${Red}Please run as root/sudo${Color_Off}\n"
  exit 1
fi

printf "Welcome to WebDir installation guide, it will be very simple and very fast...\n"
read -p "WebDir website path [/var/www/webdir]: " INSTALL_PATH
INSTALL_PATH=${INSTALL_PATH:-/var/www/webdir}
read -p "WebDir access URL [localhost]: " URL
URL=${URL:-localhost}

printf "\n"

printf "${Red}Please verify this informations:${Color_Off}\n"
printf "WebDir website path: ${INSTALL_PATH}\n"
printf "WebDir access URL: ${URL}\n"
printf "\n"
read -p "Are this information correct ? [Y/n] " PROCEED
PROCEED=${PROCEED:-Y}

if [ ${PROCEED} != 'y' ] && [ ${PROCEED} != 'Y' ]; then
   printf "${Red}Canceled${Color_Off}\n"
   exit 0;
fi

printf "Downloading WebDir in ${INSTALL_PATH}...\n"
if [ ! -d ${INSTALL_PATH} ]; then
   read -p "Directory ${INSTALL_PATH} does not exists, do you want to create it ? [Y/n] " PROCEED
   PROCEED=${PROCEED:-Y}
   if [ ${PROCEED} != 'y' ] && [ ${PROCEED} != 'Y' ]; then
      printf "${Red}Canceled${Color_Off}\n"
      exit 0;
   fi
   mkdir -p ${INSTALL_PATH}
fi
if [ "$(ls -A /var/www/webdir)" ]; then
   read -p "Directory ${INSTALL_PATH} is not empty, proceed anyway ? [Y/n] " PROCEED
   PROCEED=${PROCEED:-Y}
   if [ ${PROCEED} != 'y' ] && [ ${PROCEED} != 'Y' ]; then
      printf "${Red}Canceled${Color_Off}\n"
      exit 0;
   fi 
fi
git clone -q http://git.stevecohen.fr/public-projects/webdir.git ${INSTALL_PATH}
sed -i -e "s|AuthUserFile.*|AuthUserFile ${INSTALL_PATH}/.htpasswd|g" ${INSTALL_PATH}/.htaccess
printf "${Green}Done${Color_Off}\n"
printf "Configuring VirtualHost...\n"
cat > /etc/apache2/sites-available/webdir.conf << EOL
<VirtualHost *:80>
    DocumentRoot ${INSTALL_PATH}
    ServerName ${URL}

    <Directory />
        <IfModule mod_rewrite.c>
            RewriteCond %{REQUEST_FILENAME} !-f
            RewriteCond %{REQUEST_URI} !index
            RewriteEngine on
            RewriteRule ^(.*)$ /.index.php
        </IfModule>
        #Options FollowSymLinks
        Options Indexes FollowSymLinks Includes ExecCGI
        AllowOverride All
        Order deny,allow
        Allow from all
    </Directory>

    ErrorLog ${APACHE_LOG_DIR}/error.log
    CustomLog ${APACHE_LOG_DIR}/access.log combined
</VirtualHost>
EOL

printf "${Green}Configuration writen in /etc/apache2/sites-available/webdir.conf${Color_Off}\n"
printf "Enabling apache configuration...\n"
a2ensite webdir.conf &> /dev/null
printf "${Green}Configuration enabled${Color_Off}\n"
service apache2 reload &> /dev/null
printf "Reloading apache...\n"
printf "${Green}Apache reloaded${Color_Off}\n"

printf "\n"

printf "${Green}Everything is ready, you can now access your WebDir : ${URL}${Color_Off}\n"
printf "${Blue}Thanks using WebDir :) For more informations please visit http://git.stevecohen.fr/public-projects/webdir${Color_Off}\n"
