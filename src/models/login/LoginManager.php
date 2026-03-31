<?php

class LoginManager extends AbstractEntityManager
{
    public function checkCredentials(array $data): ?array
    {
        $login = new Login($data);

        $sql = "SELECT * FROM users WHERE username = :username";
        $stmt = $this->db->getPDO()->prepare($sql);
        $stmt->execute([
            ':username' => $login->getUsername()
        ]);

        $user = $stmt->fetch();

        if ($user && password_verify($login->getPassword(), $user['password_hash'])) {
            return $user;
        }

        return null;
    }
}