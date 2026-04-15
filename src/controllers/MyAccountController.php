<?php

class MyAccountController extends AbstractController
{
    private MyAccountService $myAccountService;

    public function __construct()
    {
        $this->myAccountService = new MyAccountService();
    }

    public function execute(): void
    {
        if ($_SERVER['REQUEST_METHOD'] === 'GET') {
            $pageResult = $this->myAccountService->getPageData();

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

            $memberSince = $createdAt;
            if ($createdAt !== '') {
                $timestamp = strtotime($createdAt);

                if ($timestamp !== false) {
                    $memberSince = date('d/m/Y', $timestamp);
                }
            }

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

        $updateResult = $this->myAccountService->updateProfile($_POST, $_FILES);

        if ($updateResult['success'] === false) {
            $this->handleError($updateResult['error']);
            return;
        }

        if ($this->isAjaxRequest()) {
            $this->renderJson([
                'success' => true,
                'error' => null,
                'data' => $updateResult['data']
            ]);
        }

        header('Location: /?action=my-account');
        exit;
    }

    private function handleError(string $error): void
    {
        $statusCode = 500;

        if ($error === 'Authentication required.') {
            $statusCode = 403;
        } elseif ($error === 'User not found.') {
            $statusCode = 404;
        } elseif (
            $error === 'Invalid username.' ||
            $error === 'Invalid email.' ||
            $error === 'Invalid password.' ||
            $error === 'Username already used.' ||
            $error === 'Email already used.' ||
            $error === 'Profile update failed.' ||
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