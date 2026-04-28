<?php

class AuthenticationService
{
    private UserManager $userManager;

    public function __construct()
    {
        $this->userManager = new UserManager();
    }

    public function requireUserId(): array
    {
        // Méthode utilisée partout où une action doit être réservée
        // à un utilisateur connecté. La source de vérité reste la session.
        if (empty($_SESSION['user_id'])) {
            return [
                'success' => false,
                'error' => 'Authentication required.',
                'data' => null
            ];
        }

        return [
            'success' => true,
            'error' => null,
            'data' => [
                'user_id' => (int) $_SESSION['user_id']
            ]
        ];
    }

    public function login(array $data): array
    {
        // Après vérification du mot de passe, on régénère l'id de session
        // puis on stocke les infos minimum utiles pour les autres pages.
        $email = trim((string) ($data['email'] ?? ''));
        $password = (string) ($data['password'] ?? '');

        if ($email === '' || $password === '') {
            return [
                'success' => false,
                'error' => 'Identifiants invalides',
                'data' => null,
                'status_code' => 401
            ];
        }

        $user = $this->userManager->findByEmail($email);

        if (!$user instanceof User || !password_verify($password, $user->getPasswordHash())) {
            return [
                'success' => false,
                'error' => 'Identifiants invalides',
                'data' => null,
                'status_code' => 401
            ];
        }

        session_regenerate_id(true);
        $_SESSION['user_id'] = $user->getId();
        $_SESSION['username'] = $user->getUsername();

        return [
            'success' => true,
            'error' => null,
            'data' => [
                'message' => 'Connexion réussie',
                'user_id' => $user->getId(),
                'username' => $user->getUsername()
            ],
            'status_code' => 200
        ];
    }

    public function checkUsernameAvailability(string $username): array
    {
        $username = trim($username);

        if ($username === '') {
            return [
                'success' => false,
                'available' => false,
                'message' => 'Pseudo vide.'
            ];
        }

        $exists = $this->userManager->usernameExists($username);

        return [
            'success' => true,
            'available' => !$exists,
            'message' => $exists ? 'pardon , pseudo deja pris .' : ''
        ];
    }

    public function checkEmailAvailability(string $email): array
    {
        $email = trim($email);

        if ($email === '') {
            return [
                'success' => false,
                'available' => false,
                'message' => 'Email vide.'
            ];
        }

        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            return [
                'success' => false,
                'available' => false,
                'message' => 'Format email invalide.'
            ];
        }

        $exists = $this->userManager->emailExists($email);

        return [
            'success' => true,
            'available' => !$exists,
            'message' => $exists ? 'cette email es deja ratacher as un compte .' : ''
        ];
    }

    public function register(array $data): array
    {
        $username = trim((string) ($data['username'] ?? ''));
        $email = trim((string) ($data['email'] ?? ''));
        $password = (string) ($data['password'] ?? '');

        $errors = [];

        if ($username === '') {
            $errors['username'] = 'Pseudo obligatoire.';
        }

        if ($email === '') {
            $errors['email'] = 'Email obligatoire.';
        } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $errors['email'] = 'Format email invalide.';
        }

        if ($password === '') {
            $errors['password'] = 'Mot de passe obligatoire.';
        } elseif (strlen($password) < 8) {
            $errors['password'] = 'Mot de passe trop court.';
        }

        if ($username !== '' && $this->userManager->usernameExists($username)) {
            $errors['username'] = 'pardon , pseudo deja pris .';
        }

        if ($email !== '' && filter_var($email, FILTER_VALIDATE_EMAIL) && $this->userManager->emailExists($email)) {
            $errors['email'] = 'cette email es deja ratacher as un compte .';
        }

        if (!empty($errors)) {
            return [
                'success' => false,
                'error' => null,
                'errors' => $errors,
                'data' => null,
                'status_code' => 422
            ];
        }

        $user = new User([
            'username' => $username,
            'email' => $email,
            'password_hash' => password_hash($password, PASSWORD_DEFAULT)
        ]);

        $userId = $this->userManager->insert($user);

        if ($userId <= 0) {
            return [
                'success' => false,
                'error' => 'Erreur lors de la création du compte.',
                'data' => null,
                'status_code' => 500
            ];
        }

        return [
            'success' => true,
            'error' => null,
            'data' => [
                'message' => 'Compte créé avec succès.',
                'user_id' => $userId
            ],
            'status_code' => 200
        ];
    }
}
