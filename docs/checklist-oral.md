# Checklist avant oral

Ce document sert de todo de préparation avant la soutenance.

## Priorité haute

- [x] Ajouter un fichier `.htaccess` à la racine pour rediriger vers `public/` et permettre un démarrage du projet sans se placer manuellement dans ce dossier.
- [x] Vérifier que le projet démarre correctement depuis la racine après ajout de la redirection.
- [x] Créer un `.gitignore` adapté au projet.
- [x] Définir une vraie stratégie pour les documents internes qui ne doivent pas arriver sur `main`.
- [ ] Remplacer toutes les données de test par des données réalistes et présentables pour la démo.

## Git et hygiène du dépôt


- [ ] Si `docs/sprint/` ne doit pas apparaître sur `main`, retirer ces fichiers du suivi Git au lieu de compter uniquement sur `.gitignore`.
- [ ] Vérifier que le dépôt final ne contient pas de fichiers de travail, de brouillons ou d'artefacts inutiles.
- [ ] Contrôler que les identifiants sensibles ne sont pas exposés sur la version finale du dépôt.

## Front et SCSS

- [ ] Finaliser le SCSS de chaque page.
- [ ] Vérifier la cohérence visuelle globale entre toutes les pages.
- [ ] faire une passe responsive sur les pages principales.
- [ ] Vérifier les états interactifs visibles : hover, focus, disabled, erreurs, messages de succès.
- [ ] Lancer une vérification finale sur les écarts entre maquettes et rendu réel.
- [ ] Recompiler le CSS final et vérifier qu'aucune erreur Sass ne bloque la génération.

## Réutilisation et nettoyage du code

- [ ] Identifier les blocs HTML/PHP dupliqués qui peuvent devenir des composants ou templates communs.
- [ ] Identifier les styles SCSS dupliqués qui peuvent être mutualisés.
- [ ] Regrouper les classes utilitaires ou patterns récurrents quand cela réduit vraiment la duplication.
- [ ] Faire une passe de nettoyage sur le code avant l'oral : suppression du code mort, renommages utiles, simplifications locales.
- [ ] Vérifier que les éléments mutualisés restent lisibles et n'ajoutent pas de complexité inutile.

## Données et démonstration

- [ ] Préparer un jeu de données cohérent pour montrer les cas principaux du projet.
- [ ] Vérifier que les livres, profils, messages et images affichés sont crédibles pour une démonstration.
- [ ] Retirer les contenus de test trop évidents, incohérents ou inachevés.
- [ ] Préparer un parcours de démo simple, fluide et court.

## Support de soutenance

- [ ] Préparer un support type PowerPoint ou équivalent pour structurer l'oral.
- [ ] Définir un enchaînement clair des slides : contexte, besoins, architecture, base de données, démonstration, bilan.
- [ ] Préparer une slide dédiée aux choix techniques importants.
- [ ] Préparer une slide dédiée aux difficultés rencontrées et aux solutions apportées.
- [ ] Préparer une slide de conclusion avec les axes d'amélioration.

## Gestion du temps pendant l'oral

- [ ] Définir le temps cible pour chaque partie de la présentation.
- [ ] Vérifier si l'outil de présentation choisi permet d'afficher un minuteur pendant le diaporama.
- [ ] Tester une solution simple de timer visible pendant la soutenance.
- [ ] Prévoir un déroulé de secours sans timer intégré si la solution retenue n'est pas fiable.

## Vérifications finales avant dépôt / soutenance

- [ ] Relire le README et vérifier qu'il correspond bien au projet réellement livrable.
- [ ] Vérifier le lancement du projet sur un environnement propre.
- [ ] Vérifier les pages principales une dernière fois sans erreurs PHP visibles.
- [ ] Vérifier les liens, formulaires et parcours importants.
- [ ] Vérifier l'affichage des images et des uploads.
- [ ] Vérifier la messagerie avec au moins un scénario complet.
- [ ] Faire une répétition complète de la démo avec chronométrage.

## Questions à trancher

- [ ] Décider quels documents doivent rester dans le dépôt final et quels documents doivent rester uniquement en travail interne.
- [ ] Décider si la préparation orale doit vivre dans `docs/` ou dans un support externe séparé.
