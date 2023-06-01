#### Proaxive Lite 1.5.6 (stable)
Mise à jour de la base de données nécessaire.

```
- Le logo entreprise est renommé lors de l'import
- Le redimensionnement du logo est fixé à 250px de large lors de l'import
- Le test de courriel est désactivé si vous n'avez pas renseigné de courriel entreprise
- Ajout du bouton "Confirmer" lors de la création d'un nouvel utilisateur
- Envoi d'un courriel automatique lors de la création d'une intervention
- Refonte des templates mail (intervention)
- Ajout de la suppression d'intervention si non approuvée
- Correction de la pagination (intervention)
- Correction du système de probation
- Correction de la date de dépôt
- Ajout de la modification d'une date de dépôt (edit intervention)
- Ajout de plus d'informations sur un équipement (utilisateur, réseau) (edit equipment)
- Ajout d'une vue générale pour chaque équipement
- Correction de l'éditeur CSS
```

#### Proaxive Lite 1.5.5 (stable)
Mise à jour de la base de données nécessaire.

```
- Modification du suivi si PMAD
- Ajout de l'année dans l'affichage des dates
- Correction fiche d'impression des interventions (hauteur de page)
- Amélioration de la recherche d'intervention
- Ajout de la gestion utilisateur
- Mise à jour des rôles et permissions
- Ajout d'un paramètre permettant ou non d'activer la probation "super admin"
- Ajout de statistiques dans l'accueil du panel
- Optimisation de quelques requêtes SQL
- Correction de la couleur des liens sociaux (suivi en ligne)
- Ajout cachet/signature de l'entreprise
```

#### Proaxive Lite 1.5.4 (stable)
L'installateur passe en version 0.5.0. Il est maintenant complètement intégré au système.
Mise à jour de la base de données nécessaire.

```
- Mise à jour de l'installateur
- Correction couleur de fond des tabs
- Correction de l'affichage du logo pour les marques
- Correction mot de passe oublié (envoi du courriel)
- Migration vers Mailer (SwiftMailer abandonné)
- Ajout de l'assistant de mise à jour (base de données)
- Plusieurs modifications HTML et CSS
- Sauvegarde des checkboxes dans les fiches/rapports d'intervention
- Bloquage des checkboxes dans la vue client
- Correction export (remplace " par % si présent dans les valeurs)
- Ajout de la date au fichier d'export CSV
- Ajout template mobile 1/2 (intervention)
- Mises à jour des packages (vendor)
```

#### Proaxive Lite 1.5.3 (stable)
Attention : cette mise à jour modifie les variables couleurs du CSS, pensez à sauvegarder vos fichiers `light-style.css` et `dark-style.css`
Pensez également à vider le cache de votre navigateur après la mise à jour.
```
- Modification du menu "Actions" dans l'édition d'intervention
- Ajout de l'édition du titre/nom de l'intervention
- Mise à jour de l'export CSV Client (pour import v1.7)
- Mise à jour CSS et HTML
- Correction grid dans la fiche client
- Correction de l'éditeur SimpleMde
- Réorganisation du profil client dans le panel d'administration
- Ajout d'un label si l'intervention n'est pas débutée
- Nouveau switcher de theme
- Plusieurs améliorations graphique
```

#### Proaxive Lite 1.5.2 (stable)
```
- Suppression de la décompression d'archive
- Modification de la connexion à la base de données
- Mise à jour du fichier SQL
```

#### Proaxive Lite 1.5.2 (stable)
Version stable du projet Proaxive.
```
- Ajout de la gestion de débours
- Suppression card sécurité application (paramètres)
- Corrections HTML/CSS mobile
- Correction du menu (mobile)
- Mise à jour "Services Courriel"
- Mise à jour profil client
```
#### [Fichiers nouveaux/édités]
config/routing.php  
app/Controller/Dashboard/AdminOutlayController.php  
app/Controller/Dashboard/AdminClientController.php  
app/Entity/OutlayEntity.php  
app/Model/OutlayModel.php  
views/outlay/*all  
views/_layout/dashboard/_main_menu.twig  
views/_layout/dashboard/_main_menu_mobile.twig
views/client/admin/show.twig
views/_layout/dashboard/layout.twig  
public/assets/styles/admin-default/stylesheets/layout.css  
public/assets/styles/print-intervention/voucher.css
views/dashboard/settings/courriel.twig  
views/dashboard/settings/home.twig  
views/templates/print_outlay.twig
app/Controller/Dashboard/AdminSettingController.php  
src/MyClass/SendMail.php

#### Proaxive Lite 1.5.1 (stable)
Première version stable du projet Proaxive.
```
- Ajout de la suppression client
```
#### [Fichiers édités]   
app/App.php  
config/routing.php  
views/clients/admin/show.twig  
version.xml  
README.md  
composer.json