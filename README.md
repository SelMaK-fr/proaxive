# PROAXIVE 2.0
### version 2.0.3 [beta]
#### Slim Framework v4.13.0

[![Minimum PHP Version](https://img.shields.io/badge/PHP->=8.2-%23786fa6)](https://php.net/)
[![Minimum MySQL Version](https://img.shields.io/badge/MySQL-5.x-%23f0932b)](https://www.mysql.com/fr/)
[![Minimum Composer Version](https://img.shields.io/badge/Composer-2.x-%33f9334a)](https://www.mysql.com/fr/)

Proaxive est une application web dédiée aux techniciens informatique.
Elle permet de gérer les interventions informatique en ligne. L'application web Proaxive a pour but de simplifier le suivi en atelier. Vos clients peuvent suivre ce qu'il se passe sur leur PC en temps réel.

![ScreenShot](https://proaxive.fr/assets/MiniLapTop_proaxive.png)

### Site web et support

**Site officiel :** https://proaxive.fr  
**Documentations :** https://proaxive.fr/docs/app/v-2-2-0  
**Discord :** https://discord.gg/R9XeNMw5D4

#### Requis
- PHP >= 8.2
- MySQL 5.x
- Apache or Nginx

#### Licence

Proaxive est distribué sous les termes de la licence GNU General Public License v3+ ou supérieure.

## Installation avec script (Linux)

Rendez vous dans le dossier où sera installé Proaxive et téléchargez le script d'installation pour Linux
```
curl -O https://files.proaxive.fr/script/debian/proaxive-for-xxxx.sh
```
Ou
```
wget https://files.proaxive.fr/script/debian/proaxive-for-xxxx.sh
```
Rendre le script exécutable
```
chmod +x proaxive-for-xxxx.sh
```
Lancez l'installation de Proaxive
```
./proaxive-for-xxxx.sh
```
Le script va vous posez plusieurs questions :
```
Veuillez entrer le nom de domaine de votre installation Proaxive :  
mydomain.fr
```
```
Voulez-vous configurer l'envoi de courriel maintenant ?  
```
*Réponse* : répondre "non" n'est pas une mauvaise réponse car vous pourez configurer tout ça après l'installation (il faudra impérativement le faire).  
Le script procédera ensuite à la création des tables et des données par défaut.  

## Installation avec le dépôt Git

Avant de commencer l'installation, il est impératif de créer une base de données dédiée à Proaxive. 

Dans votre dossier Proaxive, récupérez le dépôt officiel :
```
git clone https://github.com/yanndev-com/proaxive.git .
```
### Installation des dépendances
```
composer install --ignore-platform-reqs
```
### Modification du fichier .env
```
nano .env
```
Renseignez les informations de votre base de données dans les variables ci-dessous :
```
DB_HOST=localhostOu127.0.0.1
DB_NAME=nomDevotreBaseDeDonnées
DB_USER=utilisateurDeVotreBaseDedonnées
DB_PASSWORD=motDePasseDeVotreBaseDeDonnées
```
### Lancer la migration et le seeding
```
make migrate
make seed
```
ou
```
vendor/bin/phinx migrate
vendor/bin/phinx seed:run
```
Rendez-vous sur https://mondomaine.fr/login-dash  

Le compte administrateur par défaut est :  
Utilisateur : **admin@proaxive.app**  
Mot de passe : **admin**
## Fichier de configuration
Le fichier de configuration de l'application se trouve dans 
```
config/settings.php
```
Il n'est pas conseiller de le modifier, les paramètres pricnipaux ont été reporté dans le fichier .env