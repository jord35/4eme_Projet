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

## Image common
- [ ] Définition du rôle transverse de la gestion d'image
- [ ] Définition de l'emplacement dans l'architecture (common)
- [ ] Mise en place d'un service ou manager minimal de traitement d'image
- [ ] Gestion d'un upload brut minimal
- [ ] Validation du format de fichier
- [ ] Définition de la stratégie de nommage et de stockage
- [ ] Préparation de la conversion WebP
- [ ] Préparation de la génération des tailles dérivées
- [ ] Vérification que le composant peut être réutilisé dans plusieurs features

## My account
- [x] Mise en place du routing
- [~] Mise en place d'un manager ou d'un appel DB minimal  

  Le manager est cadré, mais il n'est pas encore finalisé. Il dépend encore de composants transverses qui ne sont pas prêts, notamment la stratégie image dans `common` et la feature `Edit book` pour l'affichage réel des livres.  
  Des solutions temporaires existent pour avancer : utiliser des données moquées, gérer provisoirement l'image brute sans transformation, et tester le cas où l'utilisateur n'a aucun livre.  
  À ce stade, le projet n'est pas encore assez avancé pour couvrir tous les cas de figure de manière complète et cohérente.

- [~] Mise en place d'un contrôleur
- [x] Mise en place d'un rendu HTML minimal
- [ ] Vérification que la page répond correctement

## Edit book
- [ ] Mise en place du routing
- [ ] Mise en place d'un manager ou d'un appel DB minimal
- [ ] Mise en place d'un contrôleur
- [ ] Mise en place d'un rendu HTML minimal
- [ ] Vérification que la page répond correctement

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
