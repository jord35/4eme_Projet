<?php

class SignupManager extends AbstractEntityManager
{
    
    private UserManager $userManager;

    public function __construct()
    {
        parent::__construct();
        $this->userManager = new UserManager();
    }
    private function create(Signup $signup): bool
    {
        $sql = "INSERT INTO users (username, email, password_hash, created_at)
                VALUES (:username, :email, :password_hash, NOW())";

        $params = [
            'username' => $signup->getUsername(),
            'email' => $signup->getEmail(),
            'password_hash' => $signup->getPasswordHash(),
        ];

        $stmt = $this->db->query($sql, $params);

        return $stmt->rowCount() > 0;
    }

    public function checkUsername(string $username): array
    {
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

    public function checkEmail(string $email): array
    {
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
        $username = trim($data['username'] ?? '');
        $email = trim($data['email'] ?? '');
        $password = $data['password'] ?? '';

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
                'errors' => $errors
            ];
        }

        $signup = new Signup([
            'username' => $username,
            'email' => $email,
            'password_hash' => password_hash($password, PASSWORD_DEFAULT)
        ]);

        $created = $this->create($signup);

        if (!$created) {
            return [
                'success' => false,
                'message' => 'Erreur lors de la création du compte.'
            ];
        }

        return [
            'success' => true,
            'message' => 'Compte créé avec succès.'
        ];
    }
}