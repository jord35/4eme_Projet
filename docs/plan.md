 Pour ce qui est des requêtes, je pense utiliser les mêmes classes que pour le projet précédent.



  Je ne peux pas mettre de JS, donc je vais devoir gérer le reload des messages via le HTML.
   avec un timer toutes les x secondes
    Alors finalement j'ai le droit d'utiliser du JS. 

 Alors le petit souci que j'ai maintenant c'est de choisir comment le client va se comporter, ce qui va influencer énormément mon serveur. Soit que je demande toutes les X secondes via du JS une réponse, s'il y a du nouveau par exemple, avec un ID, LastUpdateID par exemple. Si je fais ça, ce ne sera pas tout à fait synchrone, mais par contre ça va être beaucoup plus facile à implémenter d'une part, et de deux, d'un point de vue serveur, en PHP natif, c'est une situation d'un plus d'avenir.
  Le problème, si je fais quelque chose de long polling, on appelle ça, je risque de saturer mon serveur. Alors, ça va fonctionner, mais conceptuellement, ça ne sera pas bon. donc bah je me demande si je passerai pas plutôt sur un short polling qui est moins techniquement moins intéressant mais plus propre et plus dans l'idée où on est censé faire des projets qui puissent être mis en prode. 

  le html et css es simple ça me fait pas trop peur.
   Est-ce que j'ai le droit au SCSS?

 Je vais pouvoir stocker les images directement sur le serveur et les récupérer via la dB avec leur URL.


Pour la planification du développement, je vois les étapes dans cet ordre :

Création de la base de données.

Récupération de données depuis la base pour créer les premières routes et vérifier que tout fonctionne.

Mise en place de la landing page, + header et  footer.

Implémentation de l’inscription et du login.

Passage à la partie « livres » côté utilisateur :

d’abord les miniatures pour construire la grille et la page de la bibliothèque de l’utilisateur,

puis la page de description détaillée d’un livre.

Enfin, je m’occuperai de la partie messagerie.



