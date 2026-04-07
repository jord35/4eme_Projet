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
        $authResult = $this->authenticationService->requireUserId();

        if ($authResult['success'] === false) {
            return $authResult;
        }

        $ownerUserId = $authResult['data']['user_id'];
        $coverPicture = null;

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
        $authResult = $this->authenticationService->requireUserId();

        if ($authResult['success'] === false) {
            return $authResult;
        }

        $ownerUserId = $authResult['data']['user_id'];
        $bookId = isset($post['id']) ? (int) $post['id'] : 0;

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

        if (!empty($files['picture']) && !empty($files['picture']['tmp_name'])) {
            $pictureResult = $this->pictureHelper->savePicture($files['picture'], [
                'context' => 'cover',
                'variant_type' => 'cover'
            ]);

            if ($pictureResult['success'] === false) {
                return $pictureResult;
            }

            $pictureData = $pictureResult['data'];
            $newUploadedPictureId = $pictureData['picture_id'] ?? null;
            $newCoverPictureId = $newUploadedPictureId ?? $newCoverPictureId;
        }

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

        if ($saveResult['success'] === false) {
            if ($newUploadedPictureId !== null) {
                $this->pictureHelper->deletePicturePackageIfUnused($newUploadedPictureId);
            }

            return $saveResult;
        }

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