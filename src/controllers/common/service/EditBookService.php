<?php

class EditBookService
{
    private AuthenticationService $authenticationService;
    private PictureHelper $pictureHelper;
    private BookHelper $bookHelper;

    public function __construct()
    {
        $this->authenticationService = new AuthenticationService();
        $this->pictureHelper = new PictureHelper();
        $this->bookHelper = new BookHelper();
    }

    public function getFormBook(?int $bookId = null): array
    {
        // On commence toujours par verifier la session.
        // Sans utilisateur connecte, on ne doit ni creer ni modifier de livre.
        $authResult = $this->authenticationService->requireUserId();

        if ($authResult['success'] === false) {
            return $authResult;
        }

        $ownerUserId = $authResult['data']['user_id'];
        $coverPicture = null;

        // En modification, on recharge uniquement un livre appartenant au user connecte.
        // Cela evite qu'un utilisateur edite le livre d'un autre en changeant juste l'id dans l'URL.
        if ($bookId !== null && $bookId > 0) {
            $bookResult = $this->bookHelper->getOwnedBook($bookId, $ownerUserId);

            if ($bookResult['success'] === false) {
                return $bookResult;
            }

            /** @var Book $book */
            $book = $bookResult['data'];
        } else {
            $book = new Book([
                'id' => 0,
                'owner_user_id' => $ownerUserId,
                'title' => '',
                'author_name' => '',
                'description' => null,
                'cover_picture_id' => null,
                'is_available' => 1
            ]);
        }

        // L'image n'est chargee que si le livre possede deja une couverture.
        if ($book->getCoverPictureId() !== null) {
            $pictureResult = $this->pictureHelper->getPicturePackage(
                $book->getCoverPictureId(),
                'cover'
            );

            if ($pictureResult['success'] === true) {
                $coverPicture = $pictureResult['data'];
            }
        }

        return [
            'success' => true,
            'error' => null,
            'data' => [
                'book' => $book,
                'coverPicture' => $coverPicture
            ]
        ];
    }

    public function saveBook(array $post, array $files): array
    {
        // Meme logique ici : on s'appuie sur la session pour savoir
        // quel utilisateur a le droit de creer ou modifier le livre.
        $authResult = $this->authenticationService->requireUserId();

        if ($authResult['success'] === false) {
            return $authResult;
        }

        $ownerUserId = $authResult['data']['user_id'];
        $bookId = isset($post['id']) ? (int) $post['id'] : 0;

        // Si un id est fourni, on verifie que ce livre appartient bien au user connecte.
        // Sinon on prepare un nouvel objet Book vide qui sera rempli avec le formulaire.
        if ($bookId > 0) {
            $bookResult = $this->bookHelper->getOwnedBook($bookId, $ownerUserId);

            if ($bookResult['success'] === false) {
                return $bookResult;
            }

            /** @var Book $book */
            $book = $bookResult['data'];
        } else {
            $book = new Book([
                'id' => 0,
                'owner_user_id' => $ownerUserId,
                'title' => '',
                'author_name' => '',
                'description' => null,
                'cover_picture_id' => null,
                'is_available' => 1
            ]);
        }

        $title = trim($post['title'] ?? '');
        $authorName = trim($post['author_name'] ?? '');
        $description = trim($post['description'] ?? '');
        $isAvailable = isset($post['is_available']) ? (int) $post['is_available'] : 1;

        // Titre et auteur sont les champs minimums pour enregistrer un livre.
        if ($title === '' || $authorName === '') {
            return [
                'success' => false,
                'error' => 'Title and author are required.',
                'data' => null
            ];
        }

        $oldCoverPictureId = $book->getCoverPictureId();
        $newCoverPictureId = $oldCoverPictureId;
        $newUploadedPictureId = null;

        // Si une nouvelle image est envoyee, on la sauvegarde avant le livre
        // pour pouvoir rattacher son id directement a l'entite.
        if (!empty($files['picture']) && !empty($files['picture']['tmp_name'])) {
            $pictureResult = $this->pictureHelper->savePicture($files['picture'], [
                'variant_types' => ['book_card', 'book_table', 'book_detail', 'cover']
            ]);

            if ($pictureResult['success'] === false) {
                return $pictureResult;
            }

            $pictureData = $pictureResult['data'];
            $newUploadedPictureId = $pictureData['picture_id'] ?? null;
            $newCoverPictureId = $newUploadedPictureId ?? $newCoverPictureId;
        }

        // L'entite Book est remplie a partir des donnees nettoyees du formulaire,
        // puis envoyee au helper qui gere la persistance.
        $book->setOwnerUserId($ownerUserId);
        $book->setTitle($title);
        $book->setAuthorName($authorName);
        $book->setDescription($description !== '' ? $description : null);
        $book->setCoverPictureId($newCoverPictureId);
        $book->setIsAvailable($isAvailable);

        if ($bookId > 0) {
            $saveResult = $this->bookHelper->saveBook($book);
        } else {
            $saveResult = $this->bookHelper->createBook($book);
        }

        // Si la sauvegarde du livre echoue apres un upload,
        // on supprime l'image fraichement creee pour eviter les fichiers orphelins.
        if ($saveResult['success'] === false) {
            if ($newUploadedPictureId !== null) {
                $this->pictureHelper->deletePicturePackageIfUnused($newUploadedPictureId);
            }

            return $saveResult;
        }

        // Si la couverture a change, on nettoie l'ancienne seulement
        // lorsqu'elle n'est plus utilisee ailleurs.
        if (
            $oldCoverPictureId !== null &&
            $newCoverPictureId !== null &&
            $oldCoverPictureId !== $newCoverPictureId
        ) {
            $this->pictureHelper->deletePicturePackageIfUnused($oldCoverPictureId);
        }

        return $saveResult;
    }
}
