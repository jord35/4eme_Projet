<?php

class UserManager extends AbstractEntityManager
{
    public function usernameExists(string $username): bool
    {
        $username = trim($username);

        if ($username === '') {
            return false;
        }

        $sql = "SELECT id FROM users WHERE username = :username LIMIT 1";
        $stmt = $this->db->query($sql, ['username' => $username]);

        return (bool) $stmt->fetch();
    }

    public function emailExists(string $email): bool
    {
        $email = trim($email);

        if ($email === '') {
            return false;
        }

        $sql = "SELECT id FROM users WHERE email = :email LIMIT 1";
        $stmt = $this->db->query($sql, ['email' => $email]);

        return (bool) $stmt->fetch();
    }

    public function findById(int $userId): ?User
    {
        if ($userId <= 0) {
            return null;
        }

        $sql = 'SELECT * FROM users WHERE id = :id LIMIT 1';
        $stmt = $this->db->query($sql, [
            'id' => $userId
        ]);

        $data = $stmt->fetch();

        if (!$data) {
            return null;
        }

        return new User($data);
    }

    public function findByUsername(string $username): ?User
    {
        $username = trim($username);

        if ($username === '') {
            return null;
        }

        $sql = 'SELECT * FROM users WHERE username = :username LIMIT 1';
        $stmt = $this->db->query($sql, [
            'username' => $username
        ]);

        $data = $stmt->fetch();

        if (!$data) {
            return null;
        }

        return new User($data);
    }

    public function findByEmail(string $email): ?User
    {
        $email = trim($email);

        if ($email === '') {
            return null;
        }

        $sql = 'SELECT * FROM users WHERE email = :email LIMIT 1';
        $stmt = $this->db->query($sql, [
            'email' => $email
        ]);

        $data = $stmt->fetch();

        if (!$data) {
            return null;
        }

        return new User($data);
    }

    public function insert(User $user): int
    {
        $sql = '
            INSERT INTO users (
                username,
                email,
                password_hash,
                profile_picture_id,
                created_at,
                updated_at
            ) VALUES (
                :username,
                :email,
                :password_hash,
                :profile_picture_id,
                NOW(),
                NOW()
            )
        ';

        $this->db->query($sql, [
            'username' => $user->getUsername(),
            'email' => $user->getEmail(),
            'password_hash' => $user->getPasswordHash(),
            'profile_picture_id' => $user->getProfilePictureId()
        ]);

        return (int) $this->db->getPDO()->lastInsertId();
    }

    public function updateProfile(int $userId, array $data): bool
    {
        if ($userId <= 0) {
            return false;
        }

        $fields = [];
        $params = ['id' => $userId];

        if (array_key_exists('username', $data) && $data['username'] !== '') {
            $fields[] = 'username = :username';
            $params['username'] = trim((string) $data['username']);
        }

        if (array_key_exists('email', $data) && $data['email'] !== '') {
            $fields[] = 'email = :email';
            $params['email'] = trim((string) $data['email']);
        }

        if (array_key_exists('password', $data) && $data['password'] !== '') {
            $fields[] = 'password_hash = :password_hash';
            $params['password_hash'] = password_hash((string) $data['password'], PASSWORD_DEFAULT);
        }

        if (array_key_exists('profile_picture_id', $data)) {
            $fields[] = 'profile_picture_id = :profile_picture_id';
            $params['profile_picture_id'] = $data['profile_picture_id'];
        }

        if (empty($fields)) {
            return false;
        }

        $fields[] = 'updated_at = NOW()';

        $sql = '
            UPDATE users
            SET ' . implode(', ', $fields) . '
            WHERE id = :id
        ';

        $stmt = $this->db->query($sql, $params);

        if ($stmt->rowCount() > 0) {
            return true;
        }

        $existingUser = $this->findById($userId);

        return $existingUser instanceof User;
    }

    public function findProfileByUserId(int $userId): ?array
    {
        $sql = '
            SELECT
                u.id,
                u.username,
                u.email,
                u.profile_picture_id,
                u.created_at,
                COUNT(b.id) AS books_count
            FROM users u
            LEFT JOIN books b ON b.owner_user_id = u.id
            WHERE u.id = :id
            GROUP BY
                u.id,
                u.username,
                u.email,
                u.profile_picture_id,
                u.created_at
            LIMIT 1
        ';

        $stmt = $this->db->query($sql, [
            'id' => $userId
        ]);

        $data = $stmt->fetch();

        if (!$data) {
            return null;
        }

        return [
            'id' => (int) $data['id'],
            'username' => (string) $data['username'],
            'email' => (string) $data['email'],
            'profile_picture_id' => isset($data['profile_picture_id']) ? (int) $data['profile_picture_id'] : null,
            'created_at' => (string) $data['created_at'],
            'books_count' => (int) $data['books_count']
        ];
    }

    public function findPublicProfileByUsername(string $username): ?array
    {
        $username = trim($username);

        if ($username === '') {
            return null;
        }

        $sql = '
            SELECT
                u.id,
                u.username,
                u.profile_picture_id,
                u.created_at,
                COUNT(b.id) AS books_count
            FROM users u
            LEFT JOIN books b ON b.owner_user_id = u.id
            WHERE u.username = :username
            GROUP BY
                u.id,
                u.username,
                u.profile_picture_id,
                u.created_at
            LIMIT 1
        ';

        $stmt = $this->db->query($sql, [
            'username' => $username
        ]);

        $data = $stmt->fetch();

        if (!$data) {
            return null;
        }

        return [
            'id' => (int) $data['id'],
            'username' => (string) $data['username'],
            'profile_picture_id' => isset($data['profile_picture_id']) ? (int) $data['profile_picture_id'] : null,
            'created_at' => (string) $data['created_at'],
            'books_count' => (int) $data['books_count']
        ];
    }
}
