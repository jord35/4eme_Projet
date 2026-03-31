<?php

class LoginController extends AbstractController
{
    public function authenticate(): void
    {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            http_response_code(405);
            $this->renderJson([
                'success' => false,
                'message' => 'Method Not Allowed'
            ]);
        }

        $manager = new LoginManager();
        $user = $manager->checkCredentials($_POST);

        if ($user) {
            $_SESSION['user'] = $user;
            $this->renderJson([
                'success' => true,
                'message' => 'Connexion réussie'
            ]);
        }

        $this->renderJson([
            'success' => false,
            'message' => 'Identifiants invalides'
        ]);
    }
}