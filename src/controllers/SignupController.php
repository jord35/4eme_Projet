<?php

class SignupController extends AbstractController
{
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

        $manager = new SignupManager();
        $result = $manager->checkUsername($username);

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

        $manager = new SignupManager();
        $result = $manager->checkEmail($email);

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

        $manager = new SignupManager();
        $result = $manager->register($_POST);

        if (!$result['success'] && isset($result['message']) && $result['message'] === 'Erreur lors de la création du compte.') {
            http_response_code(500);
        }

        $this->renderJson($result);
    }
}
