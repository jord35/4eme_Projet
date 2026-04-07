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
            return;
        }

        $manager = new LoginManager();
        $user = $manager->checkCredentials($_POST);

        if ($user) {
            session_regenerate_id(true);

            $_SESSION['user_id'] = (int) $user['id'];
            $_SESSION['username'] = $user['username'];

            $this->renderJson([
                'success' => true,
                'message' => 'Connexion réussie'
            ]);
            return;
        }

        http_response_code(401);
        $this->renderJson([
            'success' => false,
            'message' => 'Identifiants invalides'
        ]);
    }
}