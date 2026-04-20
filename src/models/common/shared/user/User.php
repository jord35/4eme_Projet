<?php

class User extends AbstractEntity
{
    protected string $username = '';
    protected string $email = '';
    protected string $passwordHash = '';
    protected ?int $profilePictureId = null;
    protected ?string $createdAt = null;
    protected ?string $updatedAt = null;

    public function setUsername(string $username): void
    {
        $this->username = trim($username);
    }

    public function getUsername(): string
    {
        return $this->username;
    }

    public function setEmail(string $email): void
    {
        $this->email = trim($email);
    }

    public function getEmail(): string
    {
        return $this->email;
    }

    public function setPasswordHash(string $passwordHash): void
    {
        $this->passwordHash = $passwordHash;
    }

    public function getPasswordHash(): string
    {
        return $this->passwordHash;
    }

    public function setProfilePictureId(?int $profilePictureId): void
    {
        $this->profilePictureId = $profilePictureId;
    }

    public function getProfilePictureId(): ?int
    {
        return $this->profilePictureId;
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
