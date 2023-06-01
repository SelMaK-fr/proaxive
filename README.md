# PROAXIVE 1.5.x
### version 1.5.6

[![Minimum PHP Version](https://img.shields.io/badge/PHP->=8.1-%23786fa6)](https://php.net/)
[![Minimum MySQL Version](https://img.shields.io/badge/MySQL-5.x-%23f0932b)](https://www.mysql.com/fr/)

Proaxive est une application web dédiée aux techniciens informatique.
Elle permet de gérer les interventions informatique en ligne. L'application web Proaxive a pour but de simplifier le suivi en atelier. Vos clients peuvent suivre ce qu'il se passe sur leur PC en temps réel.

![ScreenShot](https://proaxive.fr/assets/MiniLapTop_proaxive.png)

#### Requis
- PHP >= 8.1
- MySQL 5.x
- Apache or Nginx

#### Licence

Proaxive Lite est distribué sous les termes de la licence GNU General Public License v3+ ou supérieure.

## Installation

Installation WebSetup : https://files.proaxive.fr/pdf/Installation_Proaxive-v15x.pdf

## Fichier de configuration Proaxive
Le fichier de configuration de l'application **.env** se trouve à la racine de cette dernière

```
APP_NAME=ProaxiveLite
APP_ENV=local
APP_URL=http://localhost:8000
APP_AUTHOR=SynexoLabs
APP_ADMIN_THEME=/public/assets/styles/admin-default
APP_ROOT_PUBLIC=/public
APP_DEBUG=true
APP_DEBUG_PHP=true
APP_ROUTER_LOCAL=false
```
APP_NAME = Nom de l'application  
APP_ENV = local (dev) / production (mise en ligne)  
APP_URL = l'url de l'application (ex : https://tracker.monsite.fr)  
APP_AUTHOR = l'auteur de l'application   

## Sécurisation minimum (production)
Afin de sécuriser un peu plus l'application, je vous conseil de renommer la route qui permet de se connecter au panel.

Rendez-vous dans les paramètres (panel admin)

Remplacez **login-dash** par quelque chose de plus personnel.

Si l'application fonctionne correctement, **désactivez l'affichage des erreurs**.

## Configuration de l'envoi des courriels
**Important** Pensez à bien inscrire une adresse courriel dans les informations de votre entreprise  
> Menu "Accueil" puis onglet "Mon entreprise"

Champ "Courriel"  

La configuration des courriels se fait maintenant dans le panel d'administration  
> Menu "Paramètres" puis onglet "Service Courriel"  
### MailJet
Pour Mailjet, il également nécessaire de remplir la partie "Configuration SMTP".   

Adresse courriel : votre adresse courriel ajoutée dans Mailjet   
Utilisateur : votre clé public Mailjet   
Mot de passe : votre clé privée Mailjet  

La configuration des courriels sera "allégée" à l'avenir.  

### Tester la configuration
Il est obligatoire de renseigner une adresse courriel valide dans les informations de votre entreprise menu "Mon entreprise".
Rentrez votre adresse courriel de test dans le champ "Courriel de test".  
Afin de s'assurer que votre configuration fonctionne correctement, cliquez sur "Tester l'envoi".

