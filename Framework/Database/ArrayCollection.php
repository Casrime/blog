<?php

declare(strict_types=1);

namespace Framework\Database;

use Framework\Database\Model\ModelInterface;

class ArrayCollection implements CollectionInterface
{
    private array $models = [];

    public function add(ModelInterface $model): void
    {
        $this->models[] = $model;
    }

    public function getModels(): array
    {
        return $this->models;
    }
}
