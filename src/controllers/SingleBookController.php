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

        if (!empty($book['cover_picture_id'])) {
            $pictureResult = $this->pictureHelper->getPicturePackage(
                (int) $book['cover_picture_id'],
                'cover'
            );

            if ($pictureResult['success'] === true) {
                $coverPicture = $pictureResult['data'];
            }
        }

        $view = new View((string) $book['title']);
        $view->render('single-book', [
            'book' => $book,
            'coverPicture' => $coverPicture
        ]);
    }
}