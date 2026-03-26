<?php

class Home extends AbstractEntity
{
    protected string $title = '';
    protected string $authorName = '';
    protected string $username = '';
    protected string $createdAt = '';
    protected ?string $webp320Path = null;
    protected ?string $originalPath = null;

    public function setTitle(string $title): void
    {
        $this->title = $title;
    }

    public function getTitle(): string
    {
        return $this->title;
    }

    public function setAuthorName(string $authorName): void
    {
        $this->authorName = $authorName;
    }

    public function getAuthorName(): string
    {
        return $this->authorName;
    }

    public function setUsername(string $username): void
    {
        $this->username = $username;
    }

    public function getUsername(): string
    {
        return $this->username;
    }

    public function setCreatedAt(string $createdAt): void
    {
        $this->createdAt = $createdAt;
    }

    public function getCreatedAt(): string
    {
        return $this->createdAt;
    }

    public function setWebp320Path(?string $webp320Path): void
    {
        $this->webp320Path = $webp320Path;
    }

    public function getWebp320Path(): ?string
    {
        return $this->webp320Path;
    }

    public function setOriginalPath(?string $originalPath): void
    {
        $this->originalPath = $originalPath;
    }

    public function getOriginalPath(): ?string
    {
        return $this->originalPath;
    }

    public function getImagePath(): string
    {
        return $this->webp320Path ?? $this->originalPath ?? '';
    }
}
