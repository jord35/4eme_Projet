<?php



class Signup extends AbstractEntity
{
    private ?string $username = null;
    private ?string $email = null;
    private ?string $passwordHash = null;

    public function getUsername(): ?string
    {
        return $this->username;
    }

    public function setUsername(string $username): void
    {
        $this->username = trim($username);
    }

    public function getEmail(): ?string
    {
        return $this->email;
    }

    public function setEmail(string $email): void
    {
        $this->email = trim($email);
    }

    public function getPasswordHash(): ?string
    {
        return $this->passwordHash;
    }

    public function setPasswordHash(string $passwordHash): void
    {
        $this->passwordHash = $passwordHash;
    }
}
