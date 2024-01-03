<?php

declare(strict_types=1);

namespace Framework\Security;

use Framework\Core\ContainerInterface;

class Security
{
    public function __construct(private readonly ContainerInterface $container)
    {
    }

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
        $this->container->get('session')->set('user', $user);
    }

    public function logout(): void
    {
        $this->container->get('session')->remove('user');

        session_destroy();
    }
}
