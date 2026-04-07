<?php

class EditBookController extends AbstractController
{
    private EditBookService $editBookService;

    public function __construct()
    {
        $this->editBookService = new EditBookService();
    }

    public function execute(): void
    {
        if ($_SERVER['REQUEST_METHOD'] === 'GET') {
            $bookId = isset($_GET['id']) ? (int) $_GET['id'] : 0;

            $formResult = $this->editBookService->getFormBook($bookId > 0 ? $bookId : null);

            if ($formResult['success'] === false) {
                $this->handleError($formResult['error']);
                return;
            }

            /** @var Book $book */
            $book = $formResult['data']['book'];
            $coverPicture = $formResult['data']['coverPicture'] ?? null;

            require_once ROOT_DIR . 'src/views/templates/edit-book.php';
            return;
        }

        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            if ($this->isAjaxRequest()) {
                http_response_code(405);
                $this->renderJson([
                    'success' => false,
                    'error' => 'Method Not Allowed',
                    'data' => null
                ]);
            }

            http_response_code(405);
            echo 'Method Not Allowed';
            return;
        }

        $saveResult = $this->editBookService->saveBook($_POST, $_FILES);

        if ($saveResult['success'] === false) {
            $this->handleError($saveResult['error']);
            return;
        }

        /** @var Book $book */
        $book = $saveResult['data'];

        $coverPicture = null;
        $formResult = $this->editBookService->getFormBook($book->getId());

        if ($formResult['success'] === true) {
            $coverPicture = $formResult['data']['coverPicture'] ?? null;
        }

        if ($this->isAjaxRequest()) {
            $this->renderJson([
                'success' => true,
                'error' => null,
                'data' => [
                    'message' => 'Livre enregistré avec succès.',
                    'book' => [
                        'id' => $book->getId(),
                        'title' => $book->getTitle(),
                        'author_name' => $book->getAuthorName(),
                        'description' => $book->getDescription(),
                        'is_available' => $book->getIsAvailable(),
                        'cover_picture_id' => $book->getCoverPictureId(),
                    ],
                    'coverPicture' => $coverPicture
                ]
            ]);
        }

        header('Location: /?action=edit-book&id=' . $book->getId());
        exit;
    }

    private function handleError(string $error): void
    {
        $statusCode = 500;

        if (
            $error === 'Authentication required.' ||
            $error === 'Invalid owner user id.'
        ) {
            $statusCode = 403;
        } elseif (
            $error === 'Invalid book id.' ||
            $error === 'Book not found or access denied.'
        ) {
            $statusCode = 404;
        } elseif (
            $error === 'Title and author are required.' ||
            $error === 'Title is required.' ||
            $error === 'Author name is required.' ||
            $error === 'No uploaded file provided.' ||
            $error === 'Upload failed.' ||
            $error === 'Unsupported image format.' ||
            $error === 'Invalid uploaded file.' ||
            $error === 'Upload error.' ||
            $error === 'Picture save failed.' ||
            $error === 'Picture package not found.' ||
            $error === 'Book creation failed.' ||
            $error === 'Book update failed.'
        ) {
            $statusCode = 422;
        }

        http_response_code($statusCode);

        if ($this->isAjaxRequest()) {
            $this->renderJson([
                'success' => false,
                'error' => $error,
                'data' => null
            ]);
        }

        echo $error;
    }
}