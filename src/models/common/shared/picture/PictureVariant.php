<?php

require_once __DIR__ . '/../../AbstractEntity.php';

class PictureVariant extends AbstractEntity
{
    protected int $pictureId = -1;
    protected string $format = 'webp';
    protected int $width = 0;
    protected ?int $height = null;
    protected string $device = 'all';
    protected string $variantType = 'generic';
    protected string $path = '';
    protected ?string $createdAt = null;

    public function setPictureId(int $pictureId): void
    {
        $this->pictureId = $pictureId;
    }

    public function getPictureId(): int
    {
        return $this->pictureId;
    }

    public function setFormat(string $format): void
    {
        $this->format = $format;
    }

    public function getFormat(): string
    {
        return $this->format;
    }

    public function setWidth(int $width): void
    {
        $this->width = $width;
    }

    public function getWidth(): int
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

    public function setDevice(string $device): void
    {
        $this->device = $device;
    }

    public function getDevice(): string
    {
        return $this->device;
    }

    public function setVariantType(string $variantType): void
    {
        $this->variantType = $variantType;
    }

    public function getVariantType(): string
    {
        return $this->variantType;
    }

    public function setPath(string $path): void
    {
        $this->path = $path;
    }

    public function getPath(): string
    {
        return $this->path;
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