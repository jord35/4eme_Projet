# Tom troc

## Présentation

Tom troc est une plateforme web d’échange de livres entre utilisateurs.  
Le projet permet à chaque membre de créer un compte, proposer des livres, consulter ceux des autres utilisateurs et communiquer grâce à une messagerie intégrée.

L’objectif est de développer une application complète en PHP suivant une architecture MVC, avec une base de données relationnelle et une organisation claire du code.

---

## Fonctionnalités principales

- inscription et connexion utilisateur ;
- gestion du compte utilisateur ;
- ajout, modification et suppression de livres ;
- affichage des livres disponibles à l’échange ;
- consultation d’une fiche détaillée pour chaque livre ;
- consultation du profil public d’un utilisateur ;
- messagerie privée entre utilisateurs.

---

## Stack technique

- PHP
- MySQL / phpMyAdmin
- HTML
- CSS / SCSS
- JavaScript (polling et interactions dynamiques)

---

## Architecture

Le projet repose sur une architecture MVC avec :
- des contrôleurs pour gérer les actions ;
- des managers pour accéder aux données ;
- des vues pour l’affichage ;
- une base de données relationnelle pour stocker les utilisateurs, livres, images et messages.

---

## Base de données

Les principales tables du projet sont :

- `users`
- `pictures`
- `books`
- `conversations`
- `conversation_participants`
- `messages`

---

## Développement

Le projet est organisé autour d’une branche `develop` et de plusieurs branches `feature`, chacune correspondant à une page ou une fonctionnalité principale.

Le travail est réparti en trois sprints :

- **Sprint 1** : mise en place du proof of concept global et validation de l’architecture ;
- **Sprint 2** : consolidation de la logique métier et structuration des fonctionnalités ;
- **Sprint 3** : finalisation du front, du responsive, de l’accessibilité et des performances.

---

## Objectif pédagogique

Ce projet a pour but de mettre en pratique :
- la conception d’une architecture MVC ;
- la modélisation d’une base de données ;
- la gestion d’authentification utilisateur ;
- la manipulation de données relationnelles ;
- la mise en place d’une messagerie simple ;
- l’organisation d’un développement par features et par sprints.

---

## Installation et lancement

### Prérequis

Pour lancer le projet en local, il faut disposer de :

- PHP 8.x ;
- MySQL;
- un serveur local type XAMPP ;
- l'extension PDO MySQL activée ;
- l'extension GD activée pour la gestion des images.

### 1. Cloner ou récupérer le projet

Placez le projet dans le dossier de votre serveur local.

### 2. Créer la base de données

Créez une base de données nommée `tomtroc` dans MySQL.

### 3. Importer la structure SQL

Importez le fichier [docs/sql.sql](docs/sql.sql) dans la base `tomtroc`.

Avec phpMyAdmin :

1. créez la base `tomtroc` ;
2. ouvrez la base ;
3. utilisez l'onglet Importer ;
4. sélectionnez le fichier [docs/sql.sql](docs/sql.sql) ;
5. lancez l'import.

### 4. Configurer l'accès à la base de données

Modifiez les constantes dans [src/config/config.php](src/config/config.php) selon votre environnement :

- `DB_HOST`
- `DB_NAME`
- `DB_USER`
- `DB_PASS`

Valeurs actuelles du projet :

- `DB_HOST=localhost`
- `DB_NAME=tomtroc`
- `DB_USER=root`
- `DB_PASS=`

### 5. Vérifier le point d'entrée du site

Le front controller du projet est [public/index.php](public/index.php).

Un fichier [.htaccess](e:/openclassrooms_FStack/4eme_Projet/.htaccess) est présent à la racine pour rediriger automatiquement vers `public/` si le projet est lancé avec Apache depuis la racine du dépôt.

En pratique, vous pouvez donc placer directement le dossier du projet dans votre serveur local et ouvrir la racine du projet dans le navigateur.

Si la redirection ne fonctionne pas, vérifiez que le module de réécriture Apache est activé et que les fichiers `.htaccess` sont autorisés par la configuration du serveur.


### 6. Gestion des fichiers uploadés

Le projet stocke les images dans :

- `public/uploads/pictures/original/`
- `public/uploads/pictures/variants/`

Ces dossiers doivent être accessibles en écriture par le serveur web.

### Remarques

- le routeur passe par le paramètre `action` dans [public/index.php](public/index.php) ;
- si les images ne se génèrent pas, vérifiez que l'extension GD est bien activée ;
- si la connexion à la base échoue, vérifiez les identifiants définis dans [src/config/config.php](src/config/config.php).

---

## Auteur

Projet réalisé dans le cadre d’un apprentissage du développement full stack.

