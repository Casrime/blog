<?php

declare(strict_types=1);

namespace App\Model;

final class Article implements ModelInterface
{
    private int $id;

    public function getId(): int
    {
        return $this->id;
    }
}
