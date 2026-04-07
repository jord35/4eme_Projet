<?php

class BookHelper
{
    private BookManager $bookManager;

    public function __construct()
    {
        $this->bookManager = new BookManager();
    }

    public function getOwnedBook(int $bookId, int $ownerUserId): array
    {
        if ($bookId <= 0) {
            return [
                'success' => false,
                'error' => 'Invalid book id.',
                'data' => null
            ];
        }

        if ($ownerUserId <= 0) {
            return [
                'success' => false,
                'error' => 'Invalid owner user id.',
                'data' => null
            ];
        }

        $book = $this->bookManager->findOwnedBookById($bookId, $ownerUserId);

        if (!$book instanceof Book) {
            return [
                'success' => false,
                'error' => 'Book not found or access denied.',
                'data' => null
            ];
        }

        return [
            'success' => true,
            'error' => null,
            'data' => $book
        ];
    }

    public function getBooksForGrid(): array
    {
        $books = $this->bookManager->findAllForGrid();

        if (empty($books)) {
            return [
                'success' => true,
                'error' => null,
                'data' => []
            ];
        }

        $normalizedBooks = array_map(function (array $book): array {
            return [
                'id' => (int) $book['id'],
                'title' => (string) $book['title'],
                'author_name' => (string) $book['author_name'],
                'owner_user_id' => (int) $book['owner_user_id'],
                'owner_username' => (string) $book['owner_username'],
                'cover_picture_id' => isset($book['cover_picture_id']) ? (int) $book['cover_picture_id'] : null,
                'is_available' => (bool) $book['is_available'],
            ];
        }, $books);

        return [
            'success' => true,
            'error' => null,
            'data' => $normalizedBooks
        ];
    }

    public function createBook(Book $book): array
    {
        if ($book->getOwnerUserId() <= 0) {
            return [
                'success' => false,
                'error' => 'Invalid owner user id.',
                'data' => null
            ];
        }

        if (trim($book->getTitle()) === '') {
            return [
                'success' => false,
                'error' => 'Title is required.',
                'data' => null
            ];
        }

        if (trim($book->getAuthorName()) === '') {
            return [
                'success' => false,
                'error' => 'Author name is required.',
                'data' => null
            ];
        }

        $newBookId = $this->bookManager->insert($book);

        if ($newBookId <= 0) {
            return [
                'success' => false,
                'error' => 'Book creation failed.',
                'data' => null
            ];
        }

        $book->setId($newBookId);

        return [
            'success' => true,
            'error' => null,
            'data' => $book
        ];
    }

    public function saveBook(Book $book): array
    {
        if ($book->getId() <= 0) {
            return [
                'success' => false,
                'error' => 'Invalid book id.',
                'data' => null
            ];
        }

        if ($book->getOwnerUserId() <= 0) {
            return [
                'success' => false,
                'error' => 'Invalid owner user id.',
                'data' => null
            ];
        }

        if (trim($book->getTitle()) === '') {
            return [
                'success' => false,
                'error' => 'Title is required.',
                'data' => null
            ];
        }

        if (trim($book->getAuthorName()) === '') {
            return [
                'success' => false,
                'error' => 'Author name is required.',
                'data' => null
            ];
        }

        $result = $this->bookManager->update($book);

        if ($result === false) {
            return [
                'success' => false,
                'error' => 'Book update failed.',
                'data' => null
            ];
        }

        return [
            'success' => true,
            'error' => null,
            'data' => $book
        ];
    }
}