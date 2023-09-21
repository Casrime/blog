<?php

declare(strict_types=1);

namespace App\Model;

use Framework\Database\Model\ModelInterface;

final class Comment implements ModelInterface
{
    private string $comment;

    public function getId(): int
    {
        return 1;
    }

    public function getComment(): string
    {
        return $this->comment;
    }

    public function setComment(string $comment): void
    {
        $this->comment = $comment;
    }
}
