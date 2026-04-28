<?php

class LoginController extends AbstractController
{
    private AuthenticationService $authenticationService;

    public function __construct()
    {
        $this->authenticationService = new AuthenticationService();
    }

    public function execute(): void
    {
        // En GET on affiche simplement le formulaire,
        // en POST on tente la connexion via le service d'authentification.
        if ($_SERVER['REQUEST_METHOD'] === 'GET') {
            $view = new View('Connexion');
            $view->render('login');
            return;
        }

        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            http_response_code(405);
            $this->renderJson([
                'success' => false,
                'message' => 'Method Not Allowed'
            ]);
            return;
        }

        // Le contrôleur reste léger : la vérification des identifiants
        // et la création de session sont gérées dans AuthenticationService.
        $result = $this->authenticationService->login($_POST);

        if ($result['success'] === true) {
            $this->renderJson([
                'success' => true,
                'message' => $result['data']['message'] ?? 'Connexion réussie'
            ]);
            return;
        }

        http_response_code((int) ($result['status_code'] ?? 401));
        $this->renderJson([
            'success' => false,
            'message' => $result['error'] ?? 'Identifiants invalides'
        ]);
    }
}
