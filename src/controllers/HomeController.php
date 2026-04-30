<?php

class HomeController extends AbstractController
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
        // La page d'accueil ne fait qu'une lecture de donnees.
        // Elle refuse donc toute methode autre que GET.
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

        $recentBooksResult = $this->bookHelper->getRecentBooksForGrid(4);

        if ($recentBooksResult['success'] === false) {
            http_response_code(500);

            if ($this->isAjaxRequest()) {
                $this->renderJson([
                    'success' => false,
                    'error' => $recentBooksResult['error'],
                    'data' => null
                ]);
            }

            echo $recentBooksResult['error'] ?? 'Une erreur est survenue.';
            return;
        }

        $books = $recentBooksResult['data'];

        // On recupere les derniers livres, puis on prepare un format simple
        // directement exploitable par la vue et le composant de carte.
        $bookCards = array_map(function (array $book): array {
            $cover = null;

            // L'image est optionnelle : si elle existe, on charge son package
            // adapte au contexte "book_card".
            if (!empty($book['cover_picture_id'])) {
                $pictureResult = $this->pictureHelper->getPicturePackage(
                    (int) $book['cover_picture_id'],
                    'book_card'
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

        $view = new View('Accueil');
        $view->render('home', [
            'bookCards' => $bookCards
        ]);
    }
}
