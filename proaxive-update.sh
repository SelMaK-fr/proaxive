#!/bin/sh

# Proaxive Updater v 1.0

#----------------------------------------------------------#
#                   Variables                              #
#----------------------------------------------------------#
export PATH=$PATH:/sbin
export DEBIAN_FRONTEND=noninteractive
txtgreen='\033[32m'
txtyellow='\033[33m'
txtblue='\033[44m'
txtreset='\033[0m'

#----------------------------------------------------------#
#                     Launch installation                  #
#----------------------------------------------------------#
echo "======= MISE A JOUR DE PROAXIVE 2.x ======="
echo "Par SelMaK - Proaxive.fr"
echo "=========================================="
#----------------------------------------------------------#
#                     Download reposit                     #
#----------------------------------------------------------#
#git pull https://github.com/SelMaK-fr/proaxive2.git .
#----------------------------------------------------------#
#                Check if Composer Update                  #
#----------------------------------------------------------#
for arg in "$@" ; do
   shift
   case "$arg" in
      --dep)
    echo "\033[1mInstallation des dépendances\033[0m"
    echo "${txtblue}--- Installation en cours, veuillez patienter ---${txtreset}"
    #composer update
      ;;
      *)
      echo "Composer ignoré"
         exit 1
      ;;
   esac
done
#----------------------------------------------------------#
#                   Migrate Data                           #
#----------------------------------------------------------#
echo "\033[1mEtape 3 : Migration de la base de données\033[0m"
echo "${txtblue}--- Migration en cours, veuillez patienter ---${txtreset}"
#vendor/bin/phinx migrate
#----------------------------------------------------------#
#                   End Update                       #
#----------------------------------------------------------#
echo "\033[1mFélicitation, Proaxive a correctement été mis à jour.\033[0m

############################################
Vous pouvez quitter SSH et reprendre
une activité normale !
############################################

--------
SelMaK
Proaxive.fr
"
echo
unset DEBIAN_FRONTEND