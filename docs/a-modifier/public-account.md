# Public Account

## Checklist détaillée

- [ ] Corriger la qualité des images de profil / compte affichées sur la page.
- [ ] Vérifier pourquoi l'image choisie par le navigateur apparaît floutée.
- [ ] Revoir la logique PHP de génération des images variantes.
- [ ] Vérifier si certaines variantes générées sont trop petites par rapport à la taille réellement affichée.
- [ ] Vérifier si le navigateur sélectionne actuellement une variante trop petite, ce qui dégrade la qualité visuelle.
- [ ] Étudier la suppression de la plus petite image variante si elle n'est pas pertinente.
- [ ] Revoir les dimensions des images variantes générées pour éviter qu'une image de faible largeur soit choisie pour un affichage plus grand.
- [ ] Contrôler le comportement `srcset` / sélection automatique du navigateur côté images.
- [ ] Passer le texte "Membre depuis ..." en gris au lieu de noir.
- [ ] Revoir le style du bouton "Écrire un message".
- [ ] Passer le background du bouton "Écrire un message" en gris au lieu de blanc.
- [ ] Vérifier si ce bouton doit reprendre le même style que sur my account.

## Tableau / livres

- [ ] Mettre en place une alternance de fond sur les lignes / blocs du tableau : blanc, bleu, blanc, bleu.
- [ ] Utiliser le bleu déjà défini précédemment pour les lignes alternées.

## Notes

- Le point principal de cette page semble être un problème structurel côté PHP sur la création des variantes d'image, plus qu'un simple problème CSS.
