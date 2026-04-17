# Consignes

Coche les cases ci-dessous pour valider chaque indicateur de réussite.

Tu peux ajouter des remarques sous chaque section si besoin.

Quand toutes les cases sont cochées, les livrables peuvent être déposés sur la plateforme.

## Développer et intégrer l’interface

Livrable : Site web.

### Conformité des interfaces graphiques

- [ ] Les pages correspondent aux maquettes Figma fournies.
- [x] Toutes les pages demandées sont présentes.
- [ ] Il n’y a aucune règle CSS directement dans le code HTML/PHP, seulement l’utilisation de classes.

### Qualité des interfaces graphiques

- [ ] Le site passe le test W3C sans erreur (warnings autorisés).
- [ ] Le site passe le test WCAG niveau 2 (warnings autorisés).
- [ ] Les images, logos, icônes, polices, couleurs et textes sont exactement les bons et disposés aux mêmes endroits que sur la maquette.
- [ ] Les tailles et distances entre les éléments sont les mêmes que sur la maquette, avec une tolérance de +/-10%.

### Opérationnalité des interfaces

- [ ] Tous les liens présents sur le site sont fonctionnels.
- [ ] Accéder à une page qui n’existe pas amène sur une page d’erreur 404.
- [ ] Aucune page ne génère d’erreurs PHP.
- [x] La partie admin n’est pas accessible aux utilisateurs lambda.

### Ne peut pas faire l’objet d’un refus de soutenance

- [ ] Le responsive peut être non réalisé ou imparfait sans provoquer à lui seul un refus de soutenance.

### Notes

- 

## Développer les règles de gestion des données

Livrable : Site web.

### Conformité des règles de gestion des données

- [x] Une base de données relationnelle a été utilisée (MySQL, MariaDB...).
- [x] Les bases NoSQL (ex. MongoDB) ne sont pas utilisées.
- [x] Les tables possèdent des relations garantissant l’intégrité de la base de données.

### Opérationnalité des règles de gestion des données

- [ ] Le code PHP respecte les PSR, avec une indentation cohérente sur l’ensemble du projet.
- [x] Les noms des classes, méthodes, variables et constantes sont cohérents et explicites.
- [x] Le projet respecte obligatoirement l’architecture MVC.
- [x] Un routeur est présent et toutes les pages passent par lui.
- [x] Un ou plusieurs contrôleurs existent et sont écrits en POO.
- [ ] Les modèles suivent une architecture entité/manager ou entité/repository en POO.
- [x] Un système évite de dupliquer le header et le footer sur chaque page.
- [x] Aucune librairie PHP interdite n’a été utilisée (Twig, Doctrine, routeur clef en main, etc.).
- [x] Les outils CSS autorisés peuvent être utilisés (Sass, Less, Bootstrap, Tailwind, etc.).
- [ ] Un fichier README explique comment déployer le site.

### Respect des bonnes pratiques en gestion des données

- [x] Les types utilisés en base de données sont cohérents avec les données stockées.

### Ne peuvent pas faire l’objet d’un refus de soutenance

- [ ] L’utilisation ou non d’un autoloader ou des namespaces n’est pas bloquante.
- [ ] Le fait que le projet soit codé en totalité, partiellement ou pas du tout en anglais n’est pas bloquant.
- [ ] La présence ou l’absence de commentaires n’est pas bloquante.

### Notes

- 

## Utiliser un environnement de développement

Livrable : Site web.

### Choix / maîtrise de l’environnement de travail

- [x] Un IDE adapté au projet est installé sur le poste de l’étudiant (VS Code, PhpStorm, IntelliJ, etc.).

### Notes

- 

## Collaborer avec l’équipe projet en utilisant Git et GitHub

Livrable : Site web.

### Qualité du code

- [x] Le projet est versionné sur Git.
- [x] Le projet est stocké sur un dépôt en ligne (GitHub, GitLab, etc.).
- [x] Le projet a été versionné régulièrement.
- [x] Le projet présente au minimum 5 commits durant le projet.

### Conformité / qualité du versioning

- [x] Les messages de commit sont complets et explicites.
- [x] Les messages de commit permettent de tenir l’équipe technique informée de l’avancement.

### Notes

- J'ai fait quelques petits comites en français.

## Effectuer des requêtes sur des données

Livrable : Site web.

### Conformité de la communication avec la base de données

- [x] Les données sont stockées dans une base de données relationnelle.

### Opérationnalité de la connexion à la base de données

- [x] Le code contient des requêtes SQL de création, récupération, mise à jour et suppression de données (CRUD).

### Respect des bonnes pratiques en échanges de données

- [x] Les mots de passe stockés dans la base de données sont encodés.
- [ ] Les identifiants de connexion à la base de données ne sont pas visibles sur la dernière version du projet en ligne sur GitHub.

### Ne peut pas faire l’objet d’un refus de soutenance

- [ ] Le fait que les mots de passe soient visibles dans d’anciens commits n’est pas, à lui seul, un motif de refus.

### Notes

- 