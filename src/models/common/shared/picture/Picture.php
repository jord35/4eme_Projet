<?php

require_once __DIR__ . '/../../AbstractEntity.php';

class Picture extends AbstractEntity
{
    protected ?string $title = null;
    protected ?string $altText = null;
    protected string $originalPath = '';
    protected ?string $originalFilename = null;
    protected string $mimeType = '';
    protected ?int $width = null;
    protected ?int $height = null;
    protected ?string $createdAt = null;

    public function setTitle(?string $title): void
    {
        $this->title = $title;
    }

    public function getTitle(): ?string
    {
        return $this->title;
    }

    public function setAltText(?string $altText): void
    {
        $this->altText = $altText;
    }

    public function getAltText(): ?string
    {
        return $this->altText;
    }

    public function setOriginalPath(string $originalPath): void
    {
        $this->originalPath = $originalPath;
    }

    public function getOriginalPath(): string
    {
        return $this->originalPath;
    }

    public function setOriginalFilename(?string $originalFilename): void
    {
        $this->originalFilename = $originalFilename;
    }

    public function getOriginalFilename(): ?string
    {
        return $this->originalFilename;
    }

    public function setMimeType(string $mimeType): void
    {
        $this->mimeType = $mimeType;
    }

    public function getMimeType(): string
    {
        return $this->mimeType;
    }

    public function setWidth(?int $width): void
    {
        $this->width = $width;
    }

    public function getWidth(): ?int
    {
        return $this->width;
    }

    public function setHeight(?int $height): void
    {
        $this->height = $height;
    }

    public function getHeight(): ?int
    {
        return $this->height;
    }

    public function setCreatedAt(?string $createdAt): void
    {
        $this->createdAt = $createdAt;
    }

    public function getCreatedAt(): ?string
    {
        return $this->createdAt;
    }
}