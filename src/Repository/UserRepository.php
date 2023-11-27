<?php

declare(strict_types=1);

namespace App\Repository;

use App\Model\User;
use Framework\Database\ServiceRepository;
use Framework\Security\Security;
use Framework\Security\UserInterface;

final class UserRepository extends ServiceRepository
{
    private Security $security;

    public function __construct()
    {
        $this->security = new Security();
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
        // TODO - check if user is active ?
        $query = $this->getConnection()->prepare('SELECT * FROM user WHERE email = :email');
        $query->execute([
            'email' => $user->getEmail(),
        ]);

        $result = $query->fetch();
        if (false === $result) {
            return null;
        }

        if ($this->security->verify($user->getPassword(), $result['password'])) {
            // TODO - add method to hydrate user
            $user = new User();
            $user->setId($result['id']);
            $user->setRoles(json_decode($result['roles']));
            $user->setEmail($result['email']);
            //$user->setActive($result['active']);
            $user->setCreatedAt(new \DateTime($result['createdAt']));
            //$user->setUpdatedAt($result['updated_at']);

            return $user;
        }

        return null;
    }
}
