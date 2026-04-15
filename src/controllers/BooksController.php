<?php

class BooksController extends AbstractController
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

        $booksResult = $this->bookHelper->getBooksForGrid();

        if ($booksResult['success'] === false) {
            http_response_code(500);

            if ($this->isAjaxRequest()) {
                $this->renderJson([
                    'success' => false,
                    'error' => $booksResult['error'],
                    'data' => null
                ]);
            }

            echo $booksResult['error'] ?? 'Une erreur est survenue.';
            return;
        }

        $books = $booksResult['data'];

        $bookCards = array_map(function (array $book): array {
            $cover = null;

            if (!empty($book['cover_picture_id'])) {
                $pictureResult = $this->pictureHelper->getPicturePackage(
                    (int) $book['cover_picture_id'],
                    'cover'
                );

                if ($pictureResult['success'] === true) {
                    $cover = $pictureResult['data'];
                }
            }

            return [
                'id' => $book['id'],
                'title' => $book['title'],
                'author_name' => $book['author_name'],
                'owner' => [
                    'id' => $book['owner_user_id'],
                    'username' => $book['owner_username'],
                ],
                'is_available' => $book['is_available'],
                'cover' => $cover,
            ];
        }, $books);

        $view = new View('Livres');
        $view->render('books', [
            'bookCards' => $bookCards
        ]);
    }
}