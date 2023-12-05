<?php

declare(strict_types=1);

namespace App\Repository;

use App\Model\User;
use Framework\Database\ServiceRepository;
use Framework\Security\Security;
use Framework\Security\UserInterface;

final class UserRepository extends ServiceRepository
{
    public function __construct(private readonly Security $security)
    {
    }

    public function checkIfValueIsAlreadyUsed(string $email): bool
    {
        $query = $this->getConnection()->prepare('SELECT * FROM user WHERE email = :email');
        $query->execute(['email' => $email]);
        $result = $query->fetch();
        $query->closeCursor();

        return false !== $result;
    }

    public function login(UserInterface $user): ?UserInterface
    {
        $query = $this->getConnection()->prepare('SELECT * FROM user WHERE email = :email AND active = 1');
        $query->execute([
            'email' => $user->getEmail(),
        ]);

        $result = $query->fetch();
        if (false === $result) {
            return null;
        }

        if ($this->security->verify($user->getPassword(), $result['password'])) {
            $user = new User();
            $user->setId($result['id']);
            $user->setRoles(json_decode($result['roles']));
            $user->setEmail($result['email']);
            $user->setCreatedAt(new \DateTime($result['createdAt']));

            return $user;
        }

        return null;
    }
}
