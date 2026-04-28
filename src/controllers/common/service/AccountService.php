<?php

class AccountService
{
    private AuthenticationService $authenticationService;
    private UserManager $userManager;
    private BookHelper $bookHelper;
    private PictureHelper $pictureHelper;

    public function __construct()
    {
        $this->authenticationService = new AuthenticationService();
        $this->userManager = new UserManager();
        $this->bookHelper = new BookHelper();
        $this->pictureHelper = new PictureHelper();
    }

    public function getPageData(): array
    {
        // Mon compte est une zone privée.
        // On repart donc toujours de la session pour savoir quel profil charger.
        $authResult = $this->authenticationService->requireUserId();

        if ($authResult['success'] === false) {
            return $authResult;
        }

        $userId = $authResult['data']['user_id'];

        $profileResult = $this->userManager->findProfileByUserId($userId);

        if ($profileResult === null) {
            return [
                'success' => false,
                'error' => 'User not found.',
                'data' => null
            ];
        }

        // Le service prépare à la fois les informations du profil
        // et la liste des livres du user connecté.
        $profilePicture = $this->getProfilePictureData($profileResult);

        $booksResult = $this->bookHelper->getOwnedBooksForLibrary($userId);

        if ($booksResult['success'] === false) {
            return $booksResult;
        }

        $libraryBooks = array_map(function (array $book): array {
            $cover = null;

            if (!empty($book['cover_picture_id'])) {
                $pictureResult = $this->pictureHelper->getPicturePackage(
                    (int) $book['cover_picture_id'],
                    'book_table'
                );

                if ($pictureResult['success'] === true) {
                    $cover = $pictureResult['data'];
                }
            }

            return [
                'id' => $book['id'],
                'title' => $book['title'],
                'author_name' => $book['author_name'],
                'description' => $book['description'],
                'is_available' => $book['is_available'],
                'cover' => $cover
            ];
        }, $booksResult['data']);

        return [
            'success' => true,
            'error' => null,
            'data' => [
                'profile' => $this->buildProfileData($profileResult),
                'profilePicture' => $profilePicture,
                'libraryBooks' => $libraryBooks
            ]
        ];
    }

    public function updateProfile(array $post, array $files): array
    {
        // Cette méthode centralise toute la mise à jour du profil :
        // pseudo, email, mot de passe et photo.
        $authResult = $this->authenticationService->requireUserId();

        if ($authResult['success'] === false) {
            return $authResult;
        }

        $userId = $authResult['data']['user_id'];
        $currentProfile = $this->userManager->findProfileByUserId($userId);

        if ($currentProfile === null) {
            return [
                'success' => false,
                'error' => 'User not found.',
                'data' => null
            ];
        }

        $username = trim($post['username'] ?? '');
        $email = trim($post['email'] ?? '');
        $password = trim($post['password'] ?? '');

        // On ne remplit le payload qu'avec les champs réellement envoyés,
        // pour éviter d'écraser une valeur existante avec une chaîne vide.
        $payload = [];

        if ($username !== '') {
            $payload['username'] = $username;
        }

        if ($email !== '') {
            $payload['email'] = $email;
        }

        if ($password !== '') {
            $payload['password'] = $password;
        }

        $oldProfilePictureId = !empty($currentProfile['profile_picture_id'])
            ? (int) $currentProfile['profile_picture_id']
            : null;

        $newProfilePictureId = $oldProfilePictureId;
        $newUploadedPictureId = null;

        // Si une nouvelle photo est envoyée, on la sauvegarde d'abord.
        // Son id est ensuite injecté dans la mise à jour du profil.
        if (!empty($files['profile_image']) && !empty($files['profile_image']['tmp_name'])) {
            $pictureResult = $this->pictureHelper->savePicture($files['profile_image'], [
                'variant_type' => 'profile'
            ]);

            if ($pictureResult['success'] === false) {
                return $pictureResult;
            }

            $pictureData = $pictureResult['data'];
            $newUploadedPictureId = $pictureData['picture_id'] ?? null;
            $newProfilePictureId = $newUploadedPictureId ?? $newProfilePictureId;
        }

        $payload['profile_picture_id'] = $newProfilePictureId;

        $updateResult = $this->userManager->updateProfile($userId, $payload);

        // En cas d'échec après upload, on nettoie le nouveau fichier
        // pour éviter de garder des images inutilisées sur le disque.
        if ($updateResult === false) {
            if ($newUploadedPictureId !== null) {
                $this->pictureHelper->deletePicturePackageIfUnused($newUploadedPictureId);
            }

            return [
                'success' => false,
                'error' => 'Profile update failed.',
                'data' => null
            ];
        }

        // Si l'utilisateur a changé sa photo, on peut tenter de supprimer l'ancienne
        // seulement si elle n'est plus utilisée ailleurs.
        if (
            $oldProfilePictureId !== null &&
            $newProfilePictureId !== null &&
            $oldProfilePictureId !== $newProfilePictureId
        ) {
            $this->pictureHelper->deletePicturePackageIfUnused($oldProfilePictureId);
        }

        $updatedProfile = $this->userManager->findProfileByUserId($userId);

        if ($updatedProfile === null) {
            return [
                'success' => false,
                'error' => 'User not found.',
                'data' => null
            ];
        }

        // On remet aussi le pseudo à jour en session,
        // car la navigation s'appuie dessus dans plusieurs vues.
        $_SESSION['username'] = (string) $updatedProfile['username'];

        return [
            'success' => true,
            'error' => null,
            'data' => [
                'message' => 'Profil mis à jour.',
                'profile' => $this->buildProfileData($updatedProfile),
                'profilePicture' => $this->getProfilePictureData($updatedProfile),
                'passwordUpdated' => $password !== ''
            ]
        ];
    }

    public function deleteBook(int $bookId): array
    {
        // La suppression d'un livre depuis Mon compte reste protégée :
        // il faut être connecté et propriétaire du livre.
        $authResult = $this->authenticationService->requireUserId();

        if ($authResult['success'] === false) {
            return $authResult;
        }

        if ($bookId <= 0) {
            return [
                'success' => false,
                'error' => 'Invalid book id.',
                'data' => null
            ];
        }

        $userId = $authResult['data']['user_id'];

        // On relit d'abord le livre pour vérifier la propriété
        // et conserver l'id de couverture à nettoyer après suppression.
        $bookResult = $this->bookHelper->getOwnedBook($bookId, $userId);

        if ($bookResult['success'] === false) {
            return $bookResult;
        }

        /** @var Book $book */
        $book = $bookResult['data'];
        $coverPictureId = $book->getCoverPictureId();

        $deleteResult = $this->bookHelper->deleteBook($bookId, $userId);

        if ($deleteResult['success'] === false) {
            return $deleteResult;
        }

        if ($coverPictureId !== null) {
            $this->pictureHelper->deletePicturePackageIfUnused((int) $coverPictureId);
        }

        return [
            'success' => true,
            'error' => null,
            'data' => [
                'message' => 'Livre supprimé.'
            ]
        ];
    }

    private function buildProfileData(array $profileResult): array
    {
        return [
            'id' => (int) $profileResult['id'],
            'username' => (string) $profileResult['username'],
            'email' => (string) $profileResult['email'],
            'created_at' => (string) $profileResult['created_at'],
            'books_count' => (int) $profileResult['books_count']
        ];
    }

    private function getProfilePictureData(array $profileResult): ?array
    {
        // Petite méthode utilitaire pour garder le reste du service lisible.
        if (empty($profileResult['profile_picture_id'])) {
            return null;
        }

        $pictureResult = $this->pictureHelper->getPicturePackage(
            (int) $profileResult['profile_picture_id'],
            'profile'
        );

        if ($pictureResult['success'] !== true) {
            return null;
        }

        return $pictureResult['data'];
    }
}
