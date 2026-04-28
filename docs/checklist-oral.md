# Checklist avant oral

Ce document sert de todo de préparation avant la soutenance.

## Priorité haute

- [x] Ajouter un fichier `.htaccess` à la racine pour rediriger vers `public/` et permettre un démarrage du projet sans se placer manuellement dans ce dossier.
- [x] Vérifier que le projet démarre correctement depuis la racine après ajout de la redirection.
- [x] Créer un `.gitignore` adapté au projet.
- [x] Définir une vraie stratégie pour les documents internes qui ne doivent pas arriver sur `main`.
- [x] Remplacer toutes les données de test par des données réalistes et présentables pour la démo.

## Git et hygiène du dépôt


- [x]  `docs/sprint/` ne doit pas apparaître sur `main`, retirer ces fichiers du suivi Git au lieu de compter uniquement sur `.gitignore`.
- [ ] Vérifier que le dépôt final ne contient pas de fichiers de travail, de brouillons ou d'artefacts inutiles.
- [x] Contrôler que les identifiants sensibles ne sont pas exposés sur la version finale du dépôt.

## Front et SCSS

- [x] Finaliser le SCSS de chaque page.
- [x] Vérifier la cohérence visuelle globale entre toutes les pages.
- [ ] faire une passe responsive sur les pages principales.
- [x] Vérifier les états interactifs visibles : hover, focus, disabled, erreurs, messages de succès.
- [x] Lancer une vérification finale sur les écarts entre maquettes et rendu réel.
- [x] Recompiler le CSS final et vérifier qu'aucune erreur Sass ne bloque la génération.

## Réutilisation et nettoyage du code

- [x] Identifier les blocs HTML/PHP dupliqués qui peuvent devenir des composants ou templates communs.
- [x] Identifier les styles SCSS dupliqués qui peuvent être mutualisés.
- [x] Regrouper les classes utilitaires ou patterns récurrents quand cela réduit vraiment la duplication.
- [ ] Faire une passe de nettoyage sur le code avant l'oral : suppression du code mort, renommages utiles, simplifications locales.
- [x] Vérifier que les éléments mutualisés restent lisibles et n'ajoutent pas de complexité inutile.

.

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

- [x] Relire le README et vérifier qu'il correspond bien au projet réellement livrable.
- [x] Vérifier le lancement du projet sur un environnement propre.
- [x] Vérifier les pages principales une dernière fois sans erreurs PHP visibles.
- [x] Vérifier les liens, formulaires et parcours importants.
- [x] Vérifier l'affichage des images et des uploads.
- [x] Vérifier la messagerie avec au moins un scénario complet.
- [ ] Faire une répétition complète de la démo avec chronométrage.


