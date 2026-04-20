<?php

class SignupController extends AbstractController
{
    private AuthenticationService $authenticationService;

    public function __construct()
    {
        $this->authenticationService = new AuthenticationService();
    }

    public function checkUsername(): void
    {
        if (!$this->isAjaxRequest()) {
            http_response_code(400);
            $this->renderJson([
                'success' => false,
                'message' => 'Requête invalide.'
            ]);
        }

        $username = trim($_GET['username'] ?? '');

        $result = $this->authenticationService->checkUsernameAvailability($username);

        $this->renderJson($result);
    }

    public function checkEmail(): void
    {
        if (!$this->isAjaxRequest()) {
            http_response_code(400);
            $this->renderJson([
                'success' => false,
                'message' => 'Requête invalide.'
            ]);
        }

        $email = trim($_GET['email'] ?? '');

        $result = $this->authenticationService->checkEmailAvailability($email);

        $this->renderJson($result);
    }

    public function register(): void
    {
        if (!$this->isAjaxRequest()) {
            http_response_code(400);
            $this->renderJson([
                'success' => false,
                'message' => 'Requête invalide.'
            ]);
        }

        $result = $this->authenticationService->register($_POST);

        if (isset($result['status_code'])) {
            http_response_code((int) $result['status_code']);
        }

        $this->renderJson([
            'success' => $result['success'],
            'message' => $result['data']['message'] ?? $result['error'] ?? null,
            'errors' => $result['errors'] ?? []
        ]);
    }
}
