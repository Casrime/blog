<?php

declare(strict_types=1);

namespace Framework\Validator\Constraints;

final class UniqueEntity
{
    public string $message = 'This value is already used.';
    private string $model;

    public function __construct(string $model)
    {
        $this->model = $model;
    }

    public function validate($value): bool
    {
        $userRepositoryName = str_replace('Model', 'Repository', $this->model.'Repository');
        $userRepository = new $userRepositoryName;

        // TODO - add custom exception here ?
        if (!$userRepository->checkIfValueIsAlreadyUsed($value)) {
            return false;
        }

        return true;
    }
}
