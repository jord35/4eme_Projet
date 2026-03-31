
<?php

class Login extends AbstractEntity
{
    protected string $username;
    protected string $password;

    public function getUsername(): string { return $this->username; }
    public function setUsername(string $username) { $this->username = $username; }

    public function getPassword(): string { return $this->password; }
    public function setPassword(string $password) { $this->password = $password; }
}