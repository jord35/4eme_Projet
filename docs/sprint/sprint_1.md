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

## Edit book
- [x] Mise en place du routing
- [x] Mise en place d'un manager ou d'un appel DB minimal
  Comme pour picture, j'ai dû créer un modèle partagé afin de pouvoir par la suite réutiliser ces classes dans d'autres features telles que book, single book,
- [x] Mise en place d'un contrôleur
   J'ai dû modifier en profondeur la logique des contrôleurs en passant par un service qui lui utilisait les helpers. ainsi qu'une solution pour vérifier si l'utilisateur était bien connecté. Cette solution permettra d'être réutilisée dans d'autres features.
- [x] Mise en place d'un rendu HTML minimal
- [x] Vérification que la page répond correctement

## Books
- [x] Mise en place du routing
- [x] Mise en place d'un manager ou d'un appel DB minimal
- [x] Mise en place d'un contrôleur
- [x] Mise en place d'un rendu HTML minimal
- [x] Vérification que la page répond correctement

## My account_
- [x] Mise en place du routing
- [x] Mise en place d'un manager ou d'un appel DB minimal  
- [x] Mise en place d'un contrôleur
  Tout comme EditBook, My Account Service, étant donné qu'elle demande d'utiliser plusieurs helpers ainsi que la sécurité côté authentification, j'ai fait le choix de passer par un service.
  afin d'alléger le contrôleur pour qu'il ne pointe que vers la vue.
  Cela permet de faciliter la maintenance à l'avenir.

  Note importante.   ::::: A l'heure actuelle, nous utilisons My Account Manager pour gérer l'update d'adresse email, mot de passe, pseudo et user id image .
  Cela fonctionne mais n'est pas optimal. Lors de la merge de toutes les features sur develop, il sera judicieux de créer un helper user afin qu'il puisse être utilisé dans my account sign up et login.
  Cette configuration actuelle est donc délibérément une dette technique. Cependant elle reste utile pour permettre au projet d'avancer. J'estime qu'à l'heure actuelle c'est pas le bon moment pour faire les modifications.
- [x] Mise en place d'un rendu HTML minimal
- [x] Vérification que la page répond correctement

## Public account_
- [x] Mise en place du routing
- [x] Mise en place d'un manager ou d'un appel DB minimal
- [x] Mise en place d'un contrôleur
- [x] Mise en place d'un rendu HTML minimal
- [x] Vérification que la page répond correctement


## Home _(en review)_
- [x] Mise en place du routing
- [x] Mise en place d'un manager ou d'un appel DB minimal
- [x] Mise en place d'un contrôleur
- [x] Mise en place d'un rendu HTML minimal
- [x] Vérification que la page répond correctement

## Single book
- [x] Mise en place du routing
- [x] Mise en place d'un manager ou d'un appel DB minimal
- [x] Mise en place d'un contrôleur
- [x] Mise en place d'un rendu HTML minimal
- [x] Vérification que la page répond correctement

 le projet rencontre un petit blocage sur message cette feature est beaucoup trop imposante pour être faite à l'heure actuelle afin d'y voir plus clair je pense merger sur développe toutes les features déjà créé puis les brancher les unes aux autres et des bogey quelques petits soucis liés à l'architecture qui a évolué pendant le développement.


J'ai modifié toute l'architecture autour de users afin que User Manager soit utilisé dans les contrôleurs le nécessitant. Cette réorganisation va me permettre normalement de pouvoir créer la section message plus facilement..

ça y est j'ai fait le gros des changements et j'ai mis des liens pour naviguer sur l'ensemble du site je vais pour m'attaquer a message

## Messages
- [x] Mise en place du routing
- [x] Mise en place d'un manager ou d'un appel DB minimal
- [x] Mise en place d'un contrôleur
- [x] Mise en place d'un rendu HTML minimal
- [x] Vérification que la page répond correctement



## Objectif
Valider l’architecture globale du projet avec un site fonctionnel de bout en bout, encore brut visuellement, mais complet dans son enchaînement route, données, contrôleur et rendu.




## notes 
## Notes
J'ai dû faire quelques modifications. Lorsque j'ai refondu toute la logique liée aux utilisateurs, je n'ai pas modifié la logique des entités et je ne suis pas allé jusqu'au bout du raisonnement.

Cela a entraîné la conservation de certains managers qui n'avaient plus vraiment lieu d'être, ainsi que quelques erreurs, ou du moins des incohérences de cheminement, entre des éléments pourtant parallèles, notamment pour login et signup.


J'ai corrigé cela en créant une entité User et en faisant passer cette logique par AuthenticationService.

fixe de quelques petites incohérences entre l'update de user manager et de book manager ainsi que picture manager et picture variant manager au sujet D'erreurs potentiellement silencieuses.
en cas d'échec D'ajout des variantes d'images.

J'ai dû faire une modification sur SignUp puisque vu que c'était l'une des premières, voire même la première branche que j'avais mise en place, l'architecture ne passait pas par le layout.

J'ai dû ajouter un nouveau helper pour être utilisé dans la vue. MyAcompte et PublicAcompte. Les deux contrôleur qui s'occupe de ses vues avait besoin d'une méthode pour gérer le phrasé en fonction des dates de création des comptes d'utilisateurs certes ce n'était qu'une seule méthode mais je trouvais ça pas suffisamment propre de copier ces deux méthodes dans les deux contrôleurs. J'ai donc préféré faire un helper qui sont utilisés par ces deux contrôleurs.

J'ai dû modifier un petit souci de logique au login. Je cherchais à me loguer avec le pseudo et le mot de passe, alors qu'il fallait se faire loguer avec l'adresse email et le mot de passe.