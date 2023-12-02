<?php

declare(strict_types=1);

namespace Framework\Database;

interface ManagerInterface
{
    public function persist(object $entity): void;

    public function flush(): void;
}
