<?php

require_once __DIR__ . '/Home.php';

class HomeManager extends AbstractEntityManager
{
    public function findLatestBooks(int $limit = 3): array
    {
        $sql = '
            SELECT
                books.id,
                books.title,
                books.author_name,
                books.created_at,
                users.username,
                pictures.webp_320_path,
                pictures.original_path
            FROM books
            INNER JOIN users ON users.id = books.owner_user_id
            LEFT JOIN pictures ON pictures.id = books.cover_picture_id
            WHERE books.is_available = 1
            ORDER BY books.id DESC
            LIMIT ' . (int) $limit;

        $result = $this->db->query($sql)->fetchAll();

        $result = array_reverse($result);

        return $this->hydrateAll($result);
    }

    public function findBooksAfterId(int $afterId): array
    {
        $sql = '
            SELECT
                books.id,
                books.title,
                books.author_name,
                books.created_at,
                users.username,
                pictures.webp_320_path,
                pictures.original_path
            FROM books
            INNER JOIN users ON users.id = books.owner_user_id
            LEFT JOIN pictures ON pictures.id = books.cover_picture_id
            WHERE books.is_available = 1
              AND books.id > :afterId
            ORDER BY books.id ASC
        ';

        $result = $this->db->query($sql, [
            ':afterId' => $afterId
        ])->fetchAll();

        return $this->hydrateAll($result);
    }

    private function hydrateAll(array $rows): array
    {
        $homes = [];

        foreach ($rows as $row) {
            $homes[] = new Home($row);
        }

        return $homes;
    }
}

