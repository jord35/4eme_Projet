<?php

class MyAccountController extends AbstractController
{
    public function show(): void
{
    if (empty($_SESSION['user']['id'])) {
        header('Location: index.php');
        exit;
    }

    $manager = new MyAccountManager();
    $user = $manager->findByUserId((int) $_SESSION['user']['id']);

    if (!$user) {
        http_response_code(404);
        exit('Utilisateur introuvable');
    }

    require ROOT_DIR . 'src/views/templates/MyAccount.php';
}

    public function update(): void
    {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            http_response_code(405);
            $this->renderJson([
                'success' => false,
                'message' => 'Method Not Allowed'
            ]);
        }

        if (empty($_SESSION['user']['id'])) {
            http_response_code(401);
            $this->renderJson([
                'success' => false,
                'message' => 'Non connecté'
            ]);
        }

        $data = $_POST;

        $manager = new MyAccountManager();
        $success = $manager->updateProfile((int) $_SESSION['user']['id'], $data);

        if (!$success) {
            $this->renderJson([
                'success' => false,
                'message' => 'Aucune modification effectuée'
            ]);
        }

        $this->renderJson([
            'success' => true,
            'message' => 'Profil mis à jour'
        ]);
    }
}