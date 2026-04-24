# Layout

Ce fichier regroupe les points communs au projet.

Règle de tri :

- Si un point est clairement commun au layout, à la navigation, au footer ou à un shell partagé, il est listé ici.
- Si le doute existe, le point reste dans la page concernée avec la mention `(layout potentiel)`.

## Layout global

- [ ] Bloquer la largeur maximale du conteneur principal à `1440px`.
- [ ] Faire en sorte qu'au-delà de `1440px`, le layout n'continue pas à s'agrandir.
- [ ] Réduire l'écart horizontal global visible une fois la largeur maximale atteinte.

## Navigation et footer

- [ ] Revoir la barre de navigation pour l'harmoniser avec l'ensemble du projet.
- [ ] Revoir le footer commun.
- [ ] Réaligner le footer avec le style clair attendu.
- [ ] Harmoniser le footer avec le body et la navigation.
- [ ] Réduire les espaces trop importants avant le footer quand le problème vient du wrapper global.

## Shell partagé des pages auth

- [ ] Revoir la hauteur globale du shell partagé des pages login / signup.
- [ ] Limiter la hauteur visuelle du layout auth pour éviter que l'ensemble paraisse trop haut.
- [ ] Revoir l'image latérale des pages auth quand elle rend l'ensemble trop haut.
- [ ] Ajouter une limitation de taille côté image pour mieux contrôler la hauteur du layout auth.

## Notes

- Les points déplacés ici doivent ensuite être retirés des fichiers de page quand ils sont confirmés à 100% comme communs.