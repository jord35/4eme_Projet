<?php

class MyAccountService
{
    private AuthenticationService $authenticationService;
    private MyAccountManager $myAccountManager;
    private BookHelper $bookHelper;
    private PictureHelper $pictureHelper;

    public function __construct()
    {
        $this->authenticationService = new AuthenticationService();
        $this->myAccountManager = new MyAccountManager();
        $this->bookHelper = new BookHelper();
        $this->pictureHelper = new PictureHelper();
    }

    public function getPageData(): array
    {
        $authResult = $this->authenticationService->requireUserId();

        if ($authResult['success'] === false) {
            return $authResult;
        }

        $userId = $authResult['data']['user_id'];

        $profileResult = $this->myAccountManager->findProfileByUserId($userId);

        if ($profileResult === null) {
            return [
                'success' => false,
                'error' => 'User not found.',
                'data' => null
            ];
        }

        $profilePicture = null;

        if (!empty($profileResult['profile_picture_id'])) {
            $pictureResult = $this->pictureHelper->getPicturePackage(
                (int) $profileResult['profile_picture_id'],
                'profile'
            );

            if ($pictureResult['success'] === true) {
                $profilePicture = $pictureResult['data'];
            }
        }

        $booksResult = $this->bookHelper->getOwnedBooksForLibrary($userId);

        if ($booksResult['success'] === false) {
            return $booksResult;
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

        return [
            'success' => true,
            'error' => null,
            'data' => [
                'profile' => [
                    'id' => (int) $profileResult['id'],
                    'username' => (string) $profileResult['username'],
                    'email' => (string) $profileResult['email'],
                    'created_at' => (string) $profileResult['created_at'],
                    'books_count' => (int) $profileResult['books_count']
                ],
                'profilePicture' => $profilePicture,
                'libraryBooks' => $libraryBooks
            ]
        ];
    }

    public function updateProfile(array $post, array $files): array
    {
        $authResult = $this->authenticationService->requireUserId();

        if ($authResult['success'] === false) {
            return $authResult;
        }

        $userId = $authResult['data']['user_id'];
        $currentProfile = $this->myAccountManager->findProfileByUserId($userId);

        if ($currentProfile === null) {
            return [
                'success' => false,
                'error' => 'User not found.',
                'data' => null
            ];
        }

        $username = trim($post['username'] ?? '');
        $email = trim($post['email'] ?? '');
        $password = trim($post['password'] ?? '');

        $payload = [];

        if ($username !== '') {
            $payload['username'] = $username;
        }

        if ($email !== '') {
            $payload['email'] = $email;
        }

        if ($password !== '') {
            $payload['password'] = $password;
        }

        $oldProfilePictureId = !empty($currentProfile['profile_picture_id'])
            ? (int) $currentProfile['profile_picture_id']
            : null;

        $newProfilePictureId = $oldProfilePictureId;
        $newUploadedPictureId = null;

        if (!empty($files['profile_image']) && !empty($files['profile_image']['tmp_name'])) {
            $pictureResult = $this->pictureHelper->savePicture($files['profile_image'], [
                'context' => 'profile',
                'variant_type' => 'profile'
            ]);

            if ($pictureResult['success'] === false) {
                return $pictureResult;
            }

            $pictureData = $pictureResult['data'];
            $newUploadedPictureId = $pictureData['picture_id'] ?? null;
            $newProfilePictureId = $newUploadedPictureId ?? $newProfilePictureId;
        }

        $payload['profile_picture_id'] = $newProfilePictureId;

        $updateResult = $this->myAccountManager->updateProfile($userId, $payload);

        if ($updateResult === false) {
            if ($newUploadedPictureId !== null) {
                $this->pictureHelper->deletePicturePackageIfUnused($newUploadedPictureId);
            }

            return [
                'success' => false,
                'error' => 'Profile update failed.',
                'data' => null
            ];
        }

        if (
            $oldProfilePictureId !== null &&
            $newProfilePictureId !== null &&
            $oldProfilePictureId !== $newProfilePictureId
        ) {
            $this->pictureHelper->deletePicturePackageIfUnused($oldProfilePictureId);
        }

        return [
            'success' => true,
            'error' => null,
            'data' => [
                'message' => 'Profil mis à jour.'
            ]
        ];
    }
}