<?php

declare(strict_types=1);

class User extends BaseModel
{
    public function register(string $name, string $email, string $password): bool
    {
        if (!$this->isValidEmail($email)) {
            throw new InvalidArgumentException('Neplatný formát e-mailu.');
        }

        if (strlen($password) < 8) {
            throw new InvalidArgumentException('Heslo musí mať minimálne 8 znakov.');
        }

        if ($this->emailExists($email)) {
            throw new RuntimeException('E-mail je už zaregistrovaný.');
        }

        $hashedPassword = password_hash($password, PASSWORD_BCRYPT);

        $query = 'INSERT INTO users (name, email, password_hash) VALUES (:name, :email, :password_hash)';
        $statement = $this->db->prepare($query);

        return $statement->execute([
            ':name' => $name,
            ':email' => $email,
            ':password_hash' => $hashedPassword,
        ]);
    }

    public function login(string $email, string $password): ?array
    {
        if (!$this->isValidEmail($email)) {
            return null;
        }

        $query = 'SELECT id, name, email, password_hash FROM users WHERE email = :email LIMIT 1';
        $statement = $this->db->prepare($query);
        $statement->execute([':email' => $email]);

        $user = $statement->fetch();

        if (!$user || !password_verify($password, $user['password_hash'])) {
            return null;
        }

        unset($user['password_hash']);
        return $user;
    }

    public function getUserById(int $userId): ?array
    {
        $query = 'SELECT id, name, email, created_at FROM users WHERE id = :id LIMIT 1';
        $statement = $this->db->prepare($query);
        $statement->execute([':id' => $userId]);

        return $statement->fetch() ?: null;
    }

    private function emailExists(string $email): bool
    {
        $query = 'SELECT COUNT(*) as count FROM users WHERE email = :email';
        $statement = $this->db->prepare($query);
        $statement->execute([':email' => $email]);

        $result = $statement->fetch();
        return $result['count'] > 0;
    }

    private function isValidEmail(string $email): bool
    {
        return filter_var($email, FILTER_VALIDATE_EMAIL) !== false;
    }
}
