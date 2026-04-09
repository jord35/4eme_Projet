<?php

class BookManager extends AbstractEntityManager
{
    public function findOwnedBookById(int $bookId, int $ownerUserId): ?Book
    {
        $sql = '
            SELECT
                id,
                title,
                author_name,
                description,
                owner_user_id,
                cover_picture_id,
                is_available,
                created_at,
                updated_at
            FROM books
            WHERE id = :id
              AND owner_user_id = :owner_user_id
            LIMIT 1
        ';

        $stmt = $this->db->query($sql, [
            'id' => $bookId,
            'owner_user_id' => $ownerUserId
        ]);

        $data = $stmt->fetch();

        if (!$data) {
            return null;
        }

        return new Book($data);
    }
    private function findForGrid(?int $limit = null): array
    {
        $sql = '
            SELECT
                b.id,
                b.title,
                b.author_name,
                b.owner_user_id,
                u.username AS owner_username,
                b.cover_picture_id,
                b.is_available
            FROM books b
            INNER JOIN users u ON u.id = b.owner_user_id
            ORDER BY b.created_at DESC
        ';

        if ($limit !== null) {
            $sql .= ' LIMIT ' . (int) $limit;
        }

        $stmt = $this->db->query($sql);
        $rows = $stmt->fetchAll();

        return $rows ?: [];
    }

    public function findAllForGrid(): array
    {
        return $this->findForGrid();
    }

    public function findRecentBooksForGrid(int $limit): array
    {
        if ($limit <= 0) {
            return [];
        }

        return $this->findForGrid($limit);
    }


    public function insert(Book $book): int
    {
        $sql = '
            INSERT INTO books (
                title,
                author_name,
                description,
                owner_user_id,
                cover_picture_id,
                is_available,
                created_at,
                updated_at
            ) VALUES (
                :title,
                :author_name,
                :description,
                :owner_user_id,
                :cover_picture_id,
                :is_available,
                NOW(),
                NOW()
            )
        ';

        $this->db->query($sql, [
            'title' => $book->getTitle(),
            'author_name' => $book->getAuthorName(),
            'description' => $book->getDescription(),
            'owner_user_id' => $book->getOwnerUserId(),
            'cover_picture_id' => $book->getCoverPictureId(),
            'is_available' => $book->getIsAvailable() ? 1 : 0
        ]);

        return (int) $this->db->getPDO()->lastInsertId();
    }

    public function update(Book $book): bool
    {
        $sql = '
            UPDATE books
            SET
                title = :title,
                author_name = :author_name,
                description = :description,
                cover_picture_id = :cover_picture_id,
                is_available = :is_available,
                updated_at = NOW()
            WHERE id = :id
              AND owner_user_id = :owner_user_id
        ';

        $stmt = $this->db->query($sql, [
            'title' => $book->getTitle(),
            'author_name' => $book->getAuthorName(),
            'description' => $book->getDescription(),
            'cover_picture_id' => $book->getCoverPictureId(),
            'is_available' => $book->getIsAvailable() ? 1 : 0,
            'id' => $book->getId(),
            'owner_user_id' => $book->getOwnerUserId()
        ]);

        if ($stmt->rowCount() > 0) {
            return true;
        }

        $existingBook = $this->findOwnedBookById($book->getId(), $book->getOwnerUserId());

        return $existingBook instanceof Book;
    }
    public function findOwnedBooksByUserId(int $ownerUserId): array
    {
        $sql = '
            SELECT
                id,
                title,
                author_name,
                description,
                owner_user_id,
                cover_picture_id,
                is_available,
                created_at,
                updated_at
            FROM books
            WHERE owner_user_id = :owner_user_id
            ORDER BY created_at DESC
        ';

        $stmt = $this->db->query($sql, [
            'owner_user_id' => $ownerUserId
        ]);

        return $stmt->fetchAll() ?: [];
    }
    public function findBookDetailsById(int $bookId): ?array
    {
        if ($bookId <= 0) {
            return null;
        }

        $sql = '
            SELECT
                b.id,
                b.title,
                b.author_name,
                b.description,
                b.owner_user_id,
                u.username AS owner_username,
                b.cover_picture_id,
                b.is_available,
                b.created_at,
                b.updated_at
            FROM books b
            INNER JOIN users u ON u.id = b.owner_user_id
            WHERE b.id = :id
            LIMIT 1
        ';

        $stmt = $this->db->query($sql, [
            'id' => $bookId
        ]);

        $data = $stmt->fetch();

        if (!$data) {
            return null;
        }

        return [
            'id' => (int) $data['id'],
            'title' => (string) $data['title'],
            'author_name' => (string) $data['author_name'],
            'description' => $data['description'] !== null ? (string) $data['description'] : null,
            'owner_user_id' => (int) $data['owner_user_id'],
            'owner_username' => (string) $data['owner_username'],
            'cover_picture_id' => isset($data['cover_picture_id']) ? (int) $data['cover_picture_id'] : null,
            'is_available' => (bool) $data['is_available'],
            'created_at' => $data['created_at'] !== null ? (string) $data['created_at'] : null,
            'updated_at' => $data['updated_at'] !== null ? (string) $data['updated_at'] : null,
        ];
    }
}