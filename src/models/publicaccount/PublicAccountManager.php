<?php

class PublicAccountManager extends AbstractEntityManager
{
    public function findProfileByUsername(string $username): ?array
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