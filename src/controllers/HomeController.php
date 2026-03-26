<?php

class HomeController extends AbstractController
{
    public function index(): void
    {
        $homeManager = new HomeManager();

        if ($this->isLatestBooksAjaxRequest()) {
            $this->handleAjaxLatestBooks($homeManager);
            return;
        }

        $this->renderIndex($homeManager);
    }

    private function renderIndex(HomeManager $homeManager): void
    {
        $books = $homeManager->findLatestBooks(3);

        $view = new View('Accueil');
        $view->render('home/index', [
            'books' => $books
        ]);
    }

    private function handleAjaxLatestBooks(HomeManager $homeManager): void
    {
        $books = $homeManager->findBooksAfterId((int) ($_GET['afterId'] ?? 0));

        $lastId = 0;

        if (!empty($books)) {
            $lastBook = end($books);
            $lastId = $lastBook->getId();
        }

        $formattedBooks = [];

        foreach ($books as $book) {
            $formattedBooks[] = [
                'id' => $book->getId(),
                'title' => $book->getTitle(),
                'author_name' => $book->getAuthorName(),
                'username' => $book->getUsername(),
                'created_at' => $book->getCreatedAt(),
                'webp_320_path' => $book->getWebp320Path(),
                'original_path' => $book->getOriginalPath()
            ];
        }

        $this->renderJson([
            'success' => true,
            'books' => $formattedBooks,
            'lastId' => $lastId
        ]);
    }

    private function isLatestBooksAjaxRequest(): bool
    {
        return isset($_GET['ajax']) && $_GET['ajax'] === 'latest-books';
    }
}
