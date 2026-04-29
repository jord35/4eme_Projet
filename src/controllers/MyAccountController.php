<?php

class MyAccountController extends AbstractController
{
    private AccountService $accountService;
    private DateHelper $dateHelper;

    public function __construct()
    {
        $this->accountService = new AccountService();
        $this->dateHelper = new DateHelper();
    }

    public function execute(): void
    {
        if ($_SERVER['REQUEST_METHOD'] === 'GET') {
            $pageResult = $this->accountService->getPageData();

            if ($pageResult['success'] === false) {
                $this->handleError($pageResult['error']);
                return;
            }

            $profile = $pageResult['data']['profile'] ?? [];
            $profilePicture = $pageResult['data']['profilePicture'] ?? null;
            $libraryBooks = $pageResult['data']['libraryBooks'] ?? [];

            $username = (string) ($profile['username'] ?? '');
            $email = (string) ($profile['email'] ?? '');
            $createdAt = (string) ($profile['created_at'] ?? '');
            $booksCount = (int) ($profile['books_count'] ?? 0);

            $memberSince = $this->dateHelper->formatMemberSince($createdAt);

            $view = new View('Mon compte');
            $view->render('MyAccount', [
                'profile' => $profile,
                'profilePicture' => $profilePicture,
                'libraryBooks' => $libraryBooks,
                'username' => $username,
                'email' => $email,
                'createdAt' => $createdAt,
                'booksCount' => $booksCount,
                'memberSince' => $memberSince
            ]);
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

        $formAction = trim((string) ($_POST['form_action'] ?? 'update_profile'));

        if ($formAction === 'delete_book') {
            $bookId = isset($_POST['id']) ? (int) $_POST['id'] : 0;
            $actionResult = $this->accountService->deleteBook($bookId);
        } else {
            $actionResult = $this->accountService->updateProfile($_POST, $_FILES);
        }

        if ($actionResult['success'] === false) {
            $this->handleError($actionResult['error']);
            return;
        }

        if ($this->isAjaxRequest()) {
            $this->renderJson([
                'success' => true,
                'error' => null,
                'data' => $actionResult['data']
            ]);
        }

        header('Location: /?action=my-account');
        exit;
    }

    private function handleError(string $error): void
    {
        if ($error === 'Authentication required.' && !$this->isAjaxRequest()) {
            header('Location: /?action=login');
            exit;
        }

        $statusCode = 500;

        if ($error === 'Authentication required.') {
            $statusCode = 403;
        } elseif ($error === 'User not found.') {
            $statusCode = 404;
        } elseif (
            $error === 'Invalid book id.' ||
            $error === 'Book not found or access denied.'
        ) {
            $statusCode = 404;
        } elseif (
            $error === 'Invalid username.' ||
            $error === 'Invalid email.' ||
            $error === 'Invalid password.' ||
            $error === 'Username already used.' ||
            $error === 'Email already used.' ||
            $error === 'Profile update failed.' ||
            $error === 'Book deletion failed.' ||
            $error === 'Unsupported image format.' ||
            $error === 'Upload failed.' ||
            $error === 'Invalid uploaded file.'
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
