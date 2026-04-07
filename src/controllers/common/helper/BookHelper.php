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