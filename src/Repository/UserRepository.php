<?php

declare(strict_types=1);

namespace App\Repository;

use Framework\Database\ServiceRepository;

final class UserRepository extends ServiceRepository
{
    public function checkIfValueIsAlreadyUsed(string $email): bool
    {
        $query = $this->getConnection()->prepare('SELECT * FROM user WHERE email = :email');
        $query->execute(['email' => $email]);
        $result = $query->fetch();
        $query->closeCursor();

        return false !== $result;
    }
}
