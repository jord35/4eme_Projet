<?php

class PublicAccountController extends AbstractController
{
    private UserManager $userManager;
    private BookHelper $bookHelper;
    private PictureHelper $pictureHelper;

    public function __construct()
    {
        $this->userManager = new UserManager();
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

        $username = trim($_GET['username'] ?? '');

        $userNotFound = false;
        $profile = null;
        $profilePicture = null;
        $libraryBooks = [];
        $memberSince = '';

        if ($username === '') {
            http_response_code(404);
            $userNotFound = true;
            $view = new View('Profil public');
            $view->render('public-account', [
                'userNotFound' => $userNotFound,
                'profile' => $profile,
                'profilePicture' => $profilePicture,
                'libraryBooks' => $libraryBooks,
                'memberSince' => $memberSince
            ]);
            return;
        }

        $profile = $this->userManager->findPublicProfileByUsername($username);

        if ($profile === null) {
            http_response_code(404);
            $userNotFound = true;
            $view = new View('Profil public');
            $view->render('public-account', [
                'userNotFound' => $userNotFound,
                'profile' => $profile,
                'profilePicture' => $profilePicture,
                'libraryBooks' => $libraryBooks,
                'memberSince' => $memberSince
            ]);
            return;
        }

        if (!empty($profile['profile_picture_id'])) {
            $pictureResult = $this->pictureHelper->getPicturePackage(
                (int) $profile['profile_picture_id'],
                'profile'
            );

            if ($pictureResult['success'] === true) {
                $profilePicture = $pictureResult['data'];
            }
        }

        $booksResult = $this->bookHelper->getOwnedBooksForLibrary((int) $profile['id']);

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

        $libraryBooks = array_map(function (array $book): array {
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
                'description' => $book['description'],
                'is_available' => $book['is_available'],
                'cover' => $cover
            ];
        }, $booksResult['data']);

        $createdAt = (string) ($profile['created_at'] ?? '');

        if ($createdAt !== '') {
            $timestamp = strtotime($createdAt);

            if ($timestamp !== false) {
                $memberSince = date('d/m/Y', $timestamp);
            }
        }

        $view = new View('Profil de ' . (string) ($profile['username'] ?? 'utilisateur'));
        $view->render('public-account', [
            'userNotFound' => $userNotFound,
            'profile' => $profile,
            'profilePicture' => $profilePicture,
            'libraryBooks' => $libraryBooks,
            'memberSince' => $memberSince
        ]);
    }
}