<?php

declare(strict_types=1);

namespace Framework\Database;

use Framework\Database\Model\ModelInterface;

interface ServiceRepositoryInterface
{
    public function findAll(): array;

    public function find(int $id): ?ModelInterface;

    public function findBy(array $criteria): array;

    public function findOneBy(array $criteria): ?ModelInterface;
}
