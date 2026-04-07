<?php

class Book extends AbstractEntity
{
    protected string $title = '';
    protected string $authorName = '';
    protected ?string $description = null;
    protected int $ownerUserId = -1;
    protected ?int $coverPictureId = null;
    protected bool $isAvailable = true;
    protected ?string $createdAt = null;
    protected ?string $updatedAt = null;

    public function setTitle(string $title): void
    {
        $this->title = trim($title);
    }

    public function getTitle(): string
    {
        return $this->title;
    }

    public function setAuthorName(string $authorName): void
    {
        $this->authorName = trim($authorName);
    }

    public function getAuthorName(): string
    {
        return $this->authorName;
    }

    public function setDescription(?string $description): void
    {
        $description = $description !== null ? trim($description) : null;
        $this->description = $description === '' ? null : $description;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setOwnerUserId(int $ownerUserId): void
    {
        $this->ownerUserId = $ownerUserId;
    }

    public function getOwnerUserId(): int
    {
        return $this->ownerUserId;
    }

    public function setCoverPictureId(?int $coverPictureId): void
    {
        $this->coverPictureId = $coverPictureId;
    }

    public function getCoverPictureId(): ?int
    {
        return $this->coverPictureId;
    }

    public function setIsAvailable(int|bool $isAvailable): void
    {
        $this->isAvailable = (bool) $isAvailable;
    }

    public function getIsAvailable(): bool
    {
        return $this->isAvailable;
    }

    public function setCreatedAt(?string $createdAt): void
    {
        $this->createdAt = $createdAt;
    }

    public function getCreatedAt(): ?string
    {
        return $this->createdAt;
    }

    public function setUpdatedAt(?string $updatedAt): void
    {
        $this->updatedAt = $updatedAt;
    }

    public function getUpdatedAt(): ?string
    {
        return $this->updatedAt;
    }
}