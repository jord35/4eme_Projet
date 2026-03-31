<?php

class MyAccount extends AbstractEntity
{
    protected string $username = '';
    protected string $email = '';
    protected string $created_at = '';
    protected int $books_count = 0;

    public function getUsername(): string
    {
        return $this->username;
    }

    public function setUsername(string $username): void
    {
        $this->username = $username;
    }

    public function getEmail(): string
    {
        return $this->email;
    }

    public function setEmail(string $email): void
    {
        $this->email = $email;
    }

    public function getCreatedAt(): string
    {
        return $this->created_at;
    }

    public function setCreatedAt(string $created_at): void
    {
        $this->created_at = $created_at;
    }

    public function getBooksCount(): int
    {
        return $this->books_count;
    }

    public function setBooksCount(int $books_count): void
    {
        $this->books_count = $books_count;
    }
}