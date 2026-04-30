<?php

class SingleBookController extends AbstractController
{
    private BookHelper $bookHelper;
    private PictureHelper $pictureHelper;

    public function __construct()
    {
        $this->bookHelper = new BookHelper();
        $this->pictureHelper = new PictureHelper();
    }

    public function execute(): void
    {
        // La page detail d'un livre est une page de consultation.
        // Elle charge le livre demande, puis ses images eventuelles.
        if ($_SERVER['REQUEST_METHOD'] !== 'GET') {
            http_response_code(405);

            if ($this->isAjaxRequest()) {
                $this->renderJson([
                    'success' => false,
                    'error' => 'Method Not Allowed',
                    'data' => null
                ]);
            }

            echo 'Method Not Allowed';
            return;
        }

        $bookId = isset($_GET['id']) ? (int) $_GET['id'] : 0;

        // Le helper centralise la validation de l'id et la recuperation
        // des informations utiles sur le livre et son proprietaire.
        $bookResult = $this->bookHelper->getBookDetails($bookId);

        if ($bookResult['success'] === false) {
            $statusCode = $bookResult['error'] === 'Book not found.' ? 404 : 422;
            http_response_code($statusCode);

            if ($this->isAjaxRequest()) {
                $this->renderJson([
                    'success' => false,
                    'error' => $bookResult['error'],
                    'data' => null
                ]);
            }

            echo $bookResult['error'];
            return;
        }

        $book = $bookResult['data'];
        $coverPicture = null;
        $ownerAvatar = null;

        // On charge separement la couverture du livre et l'avatar du proprietaire,
        // car ces deux images n'utilisent pas les memes variantes.
        if (!empty($book['cover_picture_id'])) {
            $pictureResult = $this->pictureHelper->getPicturePackage(
                (int) $book['cover_picture_id'],
                'book_detail'
            );

            if ($pictureResult['success'] === true) {
                $coverPicture = $pictureResult['data'];
            }
        }

        if (!empty($book['owner_profile_picture_id'])) {
            $pictureResult = $this->pictureHelper->getPicturePackage(
                (int) $book['owner_profile_picture_id'],
                'profile'
            );

            if ($pictureResult['success'] === true) {
                $ownerAvatar = (string) ($pictureResult['data']['src'] ?? '');
            }
        }

        $book['owner_avatar'] = $ownerAvatar;

        $view = new View((string) $book['title']);
        $view->render('single-book', [
            'book' => $book,
            'coverPicture' => $coverPicture
        ]);
    }
}
