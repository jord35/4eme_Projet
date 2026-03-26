# Base de données du projet

Pour ce projet, plusieurs tables sont nécessaires afin de gérer les utilisateurs, les livres, les images et la messagerie.

## Tables principales

- `users`
- `pictures`
- `books`
- `conversations`
- `conversation_participants`
- `messages`

## users
Contient les informations des utilisateurs.

Champs :
- `id`
- `username`
- `email`
- `password_hash`
- `profile_picture_id`
- `created_at`
- `updated_at`

## pictures
Contient les chemins et métadonnées des images.

Champs :
- `id`
- `title`
- `alt_text`
- `original_path`
- `webp_path`
- `webp_320_path`
- `webp_640_path`
- `webp_1260_path`
- `original_filename`
- `mime_type`
- `width`
- `height`
- `created_at`

## books
Contient les livres proposés à l’échange.

Champs :
- `id`
- `title`
- `author_name`
- `description`
- `owner_user_id`
- `cover_picture_id`
- `is_available`
- `created_at`
- `updated_at`

## conversations
Représente une conversation entre utilisateurs.

Champs :
- `id`
- `created_at`
- `updated_at`

## conversation_participants
Relie les utilisateurs aux conversations.

Champs :
- `conversation_id`
- `user_id`
- `last_read_at`
- `joined_at`

## messages
Contient les messages envoyés dans une conversation.

Champs :
- `id`
- `conversation_id`
- `sender_user_id`
- `content`
- `created_at`

## Logique générale

- Un utilisateur possède des livres.
- Un livre appartient à un utilisateur.
- Un utilisateur peut avoir une image de profil.
- Un livre peut avoir une image de couverture.
- Une conversation contient plusieurs messages.
- Une conversation possède plusieurs participants.
- Un message appartient à une conversation et possède un expéditeur.

## Notes

- Les images ne sont pas stockées directement dans la base, seulement leurs chemins et métadonnées.
- Le nombre de livres d’un utilisateur sera calculé à partir de la table `books`.
- Le "membre depuis X temps" sera calculé à partir de `created_at`.
- Le non-lu en messagerie sera géré via `conversation_participants.last_read_at`.
