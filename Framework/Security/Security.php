<?php

declare(strict_types=1);

namespace Framework\Security;

class Security
{
    public function hash(string $password): string
    {
        return password_hash($password, PASSWORD_BCRYPT);
    }

    public function verify(string $password, string $hash): bool
    {
        return password_verify($password, $hash);
    }

    public function login(UserInterface $user): void
    {
        // TODO - use Request object or Parameter object to store in Session
        $_SESSION['user'] = $user;
    }

    public function logout(): void
    {
        // TODO - use Request object or Parameter object to store in Session
        unset($_SESSION['user']);

        session_destroy();
    }
}
