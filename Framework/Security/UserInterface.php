<?php

declare(strict_types=1);

namespace Framework\Security;

interface UserInterface
{
    public function getEmail(): ?string;

    public function getRoles(): array;
}
