#!/bin/sh

# Proaxive Installer v 1.0

#----------------------------------------------------------#
#                   Variables                              #
#----------------------------------------------------------#
export PATH=$PATH:/sbin
export DEBIAN_FRONTEND=noninteractive
ENVFILE=".env"
HTACCESS=".htaccess"
txtgreen='\033[32m'
txtyellow='\033[33m'
txtblue='\033[44m'
txtreset='\033[0m'

# Defning return code check function
check_result() {
    if [ $1 -ne 0 ]; then
        echo "Error: $2"
        exit $1
    fi
}
#----------------------------------------------------------#
#                     Check for Composer                   #
#----------------------------------------------------------#
composer -v > /dev/null 2>&1
COMPOSER=$?
if [ $COMPOSER -ne 0 ]; then
    echo "Composer n'est pas installé sur votre serveur !"
    echo "Installer Composer 2 (Debian) : https://www.digitalocean.com/community/tutorials/how-to-install-and-use-composer-on-debian-11"
    exit
else
    echo 'Composer est bien présent.'
fi
#----------------------------------------------------------#
#                     Launch installation                  #
#----------------------------------------------------------#
echo "======= INSTALLATION DE PROAXIVE 2 (HOSTING) ======="
echo "Par SelMaK - Proaxive.fr"
echo "=========================================="
#----------------------------------------------------------#
#                     Download reposit                     #
#----------------------------------------------------------#
#git clone https://github.com/SelMaK-fr/proaxive2.git .
#----------------------------------------------------------#
#                   Install Dependances                    #
#----------------------------------------------------------#
echo "\033[1mEtape 1 : Installation des dépendances\033[0m"
echo "${txtblue}--- Installation en cours, veuillez patienter ---${txtreset}"
composer install –no-dev
#----------------------------------------------------------#
#                   Parameters App                         #
#----------------------------------------------------------#
if [ -e "$ENVFILE" ]; then
        echo "Le fichier .env existe bien"
else
        echo "Le fichier .env nécessaire à Proaxive n'existe pas !"
        echo "Création du fichier .env..."
        echo >> .env
fi
echo -n "${txtgreen}Veuillez entrer le nom de domaine de votre installation Proaxive${txtreset} [ex : proaxive.monsite.fr] : "
read hostname
echo "DOMAIN_PATH='"$hostname"'" >> $ENVFILE
#----------------------------------------------------------#
#                   Setting Virtualhost                    #
#----------------------------------------------------------#
echo -n "${txtgreen}Avez-vous configuré un VirtualHost pour Proaxive ?  [Y/N] : ${txtreset}"
read yesno
if [ "$yesno" = "y" ] || [ "$yesno" = "Y" ]; then
    echo "PATH_PUBLIC=" >> $ENVFILE
elif [ "$yesno" = "n" ] || [ "$yesno" = "N" ]; then
    echo "PATH_PUBLIC=/public" >> $ENVFILE
    echo "${txtblue}--- Création du fichier .htaccess ---${txtreset}"
    mv .htaccess.lock .htaccess
else
    echo "Il faut taper Y ou N et non $yesno"
fi
#----------------------------------------------------------#
#                   Setting Database                       #
#----------------------------------------------------------#
## Database host
echo -n "${txtgreen}Serveur de base de données${txtreset} [localhost] : "
read host
echo >> $ENVFILE DB_HOST="$host"
## Database name
echo -n "${txtgreen}Nom de votre base de données :${txtreset} "
read database
echo >> $ENVFILE DB_NAME="$database"
## Database user
echo -n "${txtgreen}Utilisateur de votre base de données :${txtreset} "
read user
echo >> $ENVFILE DB_USER="$user"
## Database password
echo -n "${txtgreen}Mot de passe de votre base de données :${txtreset} "
read password
echo >> $ENVFILE DB_PASSWORD="$password"
#----------------------------------------------------------#
#                   Setting Mail                           #
#----------------------------------------------------------#
echo -n "${txtgreen}Voulez-vous configurer l'envoi de courriel maintenant ? [Y/N] :${txtreset} "
read yesno
if [ "$yesno" = "y" ] || [ "$yesno" = "Y" ]; then
    # From mailer
    echo -n "${txtgreen}Courriel d'envoi :${txtreset} "
    read mailfrom
    echo "MAIL_FROM='"$mailfrom"'" >> $ENVFILE
    # Host mailer
    echo -n "${txtgreen}Serveur courriel :${txtreset} "
    read mailhost
    echo >> $ENVFILE MAIL_HOST="$mailhost"
    # Password mailer
    echo -n "${txtgreen}Mot de passe courriel :${txtreset} "
    read mailpassword
    echo >> $ENVFILE MAIL_PASSWORD="$mailpassword"
    # Port mailer
    echo -n "${txtgreen}Port du serveur courriel :${txtreset} "
    read mailport
    echo >> $ENVFILE MAIL_PORT="$mailport"
elif [ "$yesno" = "n" ] || [ "$yesno" = "N" ]; then
    echo "${txtblue}-- Passage à l'étape suivante --${txtreset}"
else
    echo "Il faut taper Y ou N et non $yesno"
fi
#----------------------------------------------------------#
#                   Migrate Data                           #
#----------------------------------------------------------#
echo "\033[1mEtape 3 : Migration de la base de données\033[0m"
echo "${txtblue}--- Migration en cours, veuillez patienter ---${txtreset}"
vendor/bin/phinx migrate
#----------------------------------------------------------#
#                   Sedding Data                           #
#----------------------------------------------------------#
echo "\033[1mEtape 4 : Ajout des données par défaut\033[0m"
echo "${txtblue}--- Traitement en cours, veuillez patienter ---${txtreset}"
vendor/bin/phinx seed:run
#----------------------------------------------------------#
#                   End Installation                       #
#----------------------------------------------------------#
echo "\033[1mFélicitation, Proaxive a correctement été installé.\033[0m

####################################
Connexion au panel d'administration
https://"$hostname"/auth/login
Courriel : admin@admin.local
Mot de passe : admin
####################################

Si vous rencontrez des soucis lors de l'utilisation de Proaxive
N'hésitez pas à venir chercher de l'aide sur le Discord officiel.
Merci !

--------
SelMaK
Proaxive.fr
"
echo
unset DEBIAN_FRONTEND