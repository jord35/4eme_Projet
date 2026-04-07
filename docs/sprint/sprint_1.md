# Sprint 1 - Proof of Concept global

.
## Sign-up
- [x] Mise en place du routing
- [x] Mise en place d'un manager ou d'un appel DB minimal
- [x] Mise en place d'un contrôleur
- [x] Mise en place d'un rendu HTML minimal
- [x] Vérification que la page répond correctement

## Login
- [x] Mise en place du routing
- [x] Mise en place d'un manager ou d'un appel DB minimal
- [x] Mise en place d'un contrôleur
- [x] Mise en place d'un rendu HTML minimal
- [x] Vérification que la page répond correctement.

## Picture common
- [x] Définition du rôle transverse de la gestion d'image
- [x] Définition de l'emplacement dans l'architecture (common)
- [x] Mise en place d'un service ou manager minimal de traitement d'image
- [x] Gestion d'un upload brut minimal
- [x] Validation du format de fichier
- [x] Définition de la stratégie de nommage et de stockage
- [x] Préparation de la conversion WebP
- [x] Préparation de la génération des tailles dérivées
- [x] Vérification que le composant peut être réutilisé dans plusieurs features

## My account_
- [x] Mise en place du routing
- [~] Mise en place d'un manager ou d'un appel DB minimal  

  Le manager est cadré, mais il n'est pas encore finalisé. Il dépend encore de composants transverses qui ne sont pas prêts, notamment la stratégie image dans `common` et la feature `Edit book` pour l'affichage réel des livres.  
  Des solutions temporaires existent pour avancer : utiliser des données moquées, gérer provisoirement l'image brute sans transformation, et tester le cas où l'utilisateur n'a aucun livre.  
  À ce stade, le projet n'est pas encore assez avancé pour couvrir tous les cas de figure de manière complète et cohérente.

- [~] Mise en place d'un contrôleur
- [x] Mise en place d'un rendu HTML minimal
- [ ] Vérification que la page répond correctement

## Edit book
- [x] Mise en place du routing
- [x] Mise en place d'un manager ou d'un appel DB minimal
  Comme pour picture, j'ai dû créer un modèle partagé afin de pouvoir par la suite réutiliser ces classes dans d'autres features telles que book, single book,
- [x] Mise en place d'un contrôleur
   J'ai dû modifier en profondeur la logique des contrôleurs en passant par un service qui lui utilisait les helpers. ainsi qu'une solution pour vérifier si l'utilisateur était bien connecté. Cette solution permettra d'être réutilisée dans d'autres features.
- [x] Mise en place d'un rendu HTML minimal
- [x] Vérification que la page répond correctement

## Books
- [ ] Mise en place du routing
- [ ] Mise en place d'un manager ou d'un appel DB minimal
- [ ] Mise en place d'un contrôleur
- [ ] Mise en place d'un rendu HTML minimal
- [ ] Vérification que la page répond correctement

## Single book
- [ ] Mise en place du routing
- [ ] Mise en place d'un manager ou d'un appel DB minimal
- [ ] Mise en place d'un contrôleur
- [ ] Mise en place d'un rendu HTML minimal
- [ ] Vérification que la page répond correctement

## Public account
- [ ] Mise en place du routing
- [ ] Mise en place d'un manager ou d'un appel DB minimal
- [ ] Mise en place d'un contrôleur
- [ ] Mise en place d'un rendu HTML minimal
- [ ] Vérification que la page répond correctement

## Messages
- [ ] Mise en place du routing
- [ ] Mise en place d'un manager ou d'un appel DB minimal
- [ ] Mise en place d'un contrôleur
- [ ] Mise en place d'un rendu HTML minimal
- [ ] Vérification que la page répond correctement

## Home _(en review)_
- [x] Mise en place du routing
- [x] Mise en place d'un manager ou d'un appel DB minimal
- [x] Mise en place d'un contrôleur
- [x] Mise en place d'un rendu HTML minimal
- [ ] Vérification que la page répond correctement


## Objectif

Valider l’architecture globale du projet avec un site fonctionnel de bout en bout, encore brut visuellement, mais complet dans son enchaînement route, données, contrôleur et rendu.
