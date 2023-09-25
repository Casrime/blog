<?php

declare(strict_types=1);

namespace App\Model;

use Framework\Database\Model\ModelInterface;

final class User implements ModelInterface
{
    private string $email;
    private string $password;

    public function getId(): int
    {
        return 1;
    }

    public function getEmail(): string
    {
        return $this->email;
    }

    public function setEmail(string $email): void
    {
        $this->email = $email;
    }

    public function getPassword(): string
    {
        return $this->password;
    }

    public function setPassword(string $password): void
    {
        $this->password = $password;
    }
}
