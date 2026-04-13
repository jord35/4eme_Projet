<?php

class UserManager extends AbstractEntityManager{



public function usernameExists(string $username): bool
    {
        $sql = "SELECT id FROM users WHERE username = :username LIMIT 1";
        $stmt = $this->db->query($sql, ['username' => $username]);

        return (bool) $stmt->fetch();
    }

public function emailExists(string $email): bool
    {
        $sql = "SELECT id FROM users WHERE email = :email LIMIT 1";
        $stmt = $this->db->query($sql, ['email' => $email]);

        return (bool) $stmt->fetch();
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
     public function checkCredentials(array $data): ?array
    {
        $login = new Login($data);

        $sql = "SELECT * FROM users WHERE username = :username";
        $stmt = $this->db->getPDO()->prepare($sql);
        $stmt->execute([
            ':username' => $login->getUsername()
        ]);

        $user = $stmt->fetch();

        if ($user && password_verify($login->getPassword(), $user['password_hash'])) {
            return $user;
        }

        return null;
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