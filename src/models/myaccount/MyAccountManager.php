<?php

class MyAccountManager extends AbstractEntityManager
{
   

    public function updateProfile(int $userId, array $data): bool
    {
        $fields = [];
        $params = ['id' => $userId];

        if (array_key_exists('username', $data) && $data['username'] !== '') {
            $fields[] = 'username = :username';
            $params['username'] = $data['username'];
        }

        if (array_key_exists('email', $data) && $data['email'] !== '') {
            $fields[] = 'email = :email';
            $params['email'] = $data['email'];
        }

        if (array_key_exists('password', $data) && $data['password'] !== '') {
            $fields[] = 'password_hash = :password_hash';
            $params['password_hash'] = password_hash($data['password'], PASSWORD_DEFAULT);
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

        return $stmt->rowCount() > 0;
    }
}