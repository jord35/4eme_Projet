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

    public function findAllForGrid(): array
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

        $stmt = $this->db->query($sql);
        $rows = $stmt->fetchAll();

        return $rows ?: [];
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
}