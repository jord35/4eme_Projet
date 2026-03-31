<?php

class MyAccountManager extends AbstractEntityManager
{
    public function findByUserId(int $userId): ?MyAccount
{
    $sql = "
        SELECT 
            u.id,
            u.username,
            u.email,
            u.created_at,
            0 AS books_count
        FROM users u
        WHERE u.id = :id
    ";

    $stmt = $this->db->getPDO()->prepare($sql);
    $stmt->execute([':id' => $userId]);

    $data = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$data) {
        return null;
    }

    return new MyAccount($data);
}

    public function updateProfile(int $userId, array $data): bool
    {
        $fields = [];
        $params = [':id' => $userId];

        if (!empty($data['username'])) {
            $fields[] = 'username = :username';
            $params[':username'] = $data['username'];
        }

        if (!empty($data['email'])) {
            $fields[] = 'email = :email';
            $params[':email'] = $data['email'];
        }

        if (!empty($data['password'])) {
            $fields[] = 'password_hash = :password_hash';
            $params[':password_hash'] = password_hash($data['password'], PASSWORD_DEFAULT);
        }

        if (empty($fields)) {
            return false;
        }

        $sql = "UPDATE users SET " . implode(', ', $fields) . " WHERE id = :id";

        $stmt = $this->db->getPDO()->prepare($sql);
        return $stmt->execute($params);
    }
}