# PROAXIVE 2.0
### version 2.0.10
#### Slim Framework v4.14.0

[![Minimum PHP Version](https://img.shields.io/badge/PHP->=8.2-%23786fa6)](https://php.net/)
[![Minimum MySQL Version](https://img.shields.io/badge/MySQL-5.x-%23f0932b)](https://www.mysql.com/fr/)
[![Minimum Composer Version](https://img.shields.io/badge/Composer-2.x-%33f9334a)](https://www.mysql.com/fr/)

Proaxive est une application web dédiée aux techniciens informatique.
Elle permet de gérer les interventions informatique en ligne. L'application web Proaxive a pour but de simplifier le suivi en atelier. Vos clients peuvent suivre ce qu'il se passe sur leur PC en temps réel.

![ScreenShot](https://proaxive.fr/images/gallery/01.jpg)

### Site web et support

**Site officiel :** https://proaxive.fr  
**Documentations :** (WIP) https://proaxive.fr/docs/app/v-2-2-x   
**Discord :** https://discord.gg/R9XeNMw5D4

#### Requis
- PHP >= 8.2 (php-intl, php-xml, php-openssl, imagick, php-iconv et php-mbstring)
- MySQL 5.x
- Apache or Nginx

#### Licence

Proaxive est distribué sous les termes de la licence GNU General Public License v3+ ou supérieure.

#### Version complète packagée (archive ZIP)

Téléchargement : https://proaxive.fr/changelogs

## Installation avec le dépôt Git

Avant de commencer l'installation, il est impératif de créer une base de données dédiée à Proaxive. 

Dans votre dossier (totalement vide) Proaxive, récupérez le dépôt officiel :
```
git clone https://github.com/SelMaK-fr/proaxive.git .
```
### Installation des dépendances
```
make first-install
```
ou
```
composer install --ignore-platform-reqs --no-dev
```
### Fichier de configuration .env
Renommez le fichier .env.exemple en .env
```
mv .env.exemple .env
```
### Fichier de paramètres JSON
Renommez le fichier config/parameters.exemple.json en config/parameters.json
```
mv config/parameters.exemple.json config/parameters.json
```
### Fichier HTACCESS
Renommez le .htaccess.lock en .htaccess.  
Vous pouvez tout aussi bien créer un virtualhost pointant vers le dossier "public" de Proaxive. Pensez également à configurer HTTPS dans votre virtualhost.
```
mv .htaccess.lock .htaccess
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
Profitez en également pour renseigner votre nom de domaine.
```
APP_DOMAIN='https://www.yourdomain.fr'
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
Rendez-vous sur https://mondomaine.fr/auth/login 

Le compte administrateur par défaut est :  
Utilisateur : **admin@proaxive.app**  
Mot de passe : **admin/admin**
## Fichier de configuration
Le fichier de configuration de l'application se trouve dans 
```
config/settings.php
```
Il n'est pas conseiller de le modifier, les paramètres principaux ont été reportés dans le fichier .env
## Mise à jour vers nouvelle version
Pour mettre à jour votre version, lancez simplement la commande suivante
```
make update
```
NOTE : cette commande mettra à jour les fichiers sources de Proaxive (récupérés depuis le dépôt) et lancera une migration (si mise à jour de la base de données).   
Je fais au mieux pour ne pas impacter la base de données lors des mises à jour afin de ne pas pertuber vos données.   
Il est donc **très important** d'effectuer une **sauvegarde de votre base de données** avant toute mise à jour de Proaxive.   
### Dépendances
Si notifié dans le changelog, vous devrez peut être mettre à jour les dépendances. Pour ce faire, lancez la commande
```
make update-dep
```
NOTE : à lancer uniquement si spécifié dans le changelog.