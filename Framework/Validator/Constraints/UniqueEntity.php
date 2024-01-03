<?php

declare(strict_types=1);

namespace Framework\Validator\Constraints;

use Framework\Security\Security;

final class UniqueEntity
{
    public string $message = 'This value is already used.';
    private string $model;
    private Security $security;

    public function __construct(string $model, Security $security)
    {
        $this->model = $model;
        $this->security = $security;
    }

    public function validate($value): bool
    {
        $userRepositoryName = str_replace('Model', 'Repository', $this->model.'Repository');
        $userRepository = new $userRepositoryName($this->security);

        if (!$userRepository->checkIfValueIsAlreadyUsed($value)) {
            return false;
        }

        return true;
    }
}
