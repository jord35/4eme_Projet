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

## Lancement du projet

Le projet nécessite :
- un serveur local PHP ;
- une base MySQL ;
- phpMyAdmin pour l’import de la base ;
- la configuration des accès dans le fichier de configuration du projet.

---

## Auteur

Projet réalisé dans le cadre d’un apprentissage du développement full stack.

