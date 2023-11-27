<?php

declare(strict_types=1);

namespace Framework\Database;

use Framework\Database\Model\ModelInterface;

interface CollectionInterface
{
    public function add(ModelInterface $model): void;

    public function getModels(): array;
}
