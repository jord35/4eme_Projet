# Plan du projet

Pour les requêtes, je pense utiliser la même logique que pour le projet précédent, avec les mêmes types de classes pour garder quelque chose de propre et organisé.

Au début, je pensais ne pas avoir le droit d’utiliser du JavaScript, donc je pensais gérer le rechargement des messages en HTML avec un rafraîchissement régulier.  
Finalement, j’ai le droit au JavaScript, donc je vais pouvoir l’utiliser pour la messagerie.

Pour la messagerie, je dois encore faire un choix technique important.  
Soit je fais un système qui interroge régulièrement le serveur toutes les quelques secondes pour vérifier s’il y a de nouveaux messages.  
Soit je pars sur quelque chose de plus avancé comme le long polling.  
Pour l’instant, le short polling me semble plus simple, plus propre et plus adapté à un projet en PHP natif.

Le HTML et le CSS ne m’inquiètent pas particulièrement.  
Je pense aussi utiliser du SCSS si c’est autorisé, puis le compiler en CSS.

Pour les images, elles seront stockées directement sur le serveur.  
La base de données contiendra seulement les chemins ou URLs nécessaires pour les récupérer.

## Ordre de développement

Je pense avancer dans cet ordre :

1. créer la base de données ;
2. récupérer les premières données depuis la base pour tester les routes ;
3. mettre en place la landing page avec le header et le footer ;
4. implémenter l’inscription et la connexion ;
5. développer la partie livres côté utilisateur ;
6. commencer par les miniatures et la grille ;
7. faire ensuite la page détaillée d’un livre ;
8. terminer par la messagerie.

## Remarque

Ce document est surtout là pour garder une trace simple de la manière dont je compte avancer dans le projet.  
Il pourra évoluer au fur et à mesure du développement.
