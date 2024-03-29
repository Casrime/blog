<?php

declare(strict_types=1);

namespace App\Model;

use Framework\Database\Model\ModelInterface;

final class Comment implements ModelInterface
{
    private ?int $id = null;
    private ?string $comment = null;
    private ?Article $article = null;
    private ?string $active = '0';

    public function getId(): ?int
    {
        return $this->id;
    }

    public function setId(?int $id): void
    {
        $this->id = $id;
    }

    public function getComment(): ?string
    {
        return $this->comment;
    }

    public function setComment(?string $comment): void
    {
        $this->comment = $comment;
    }

    public function getArticle(): ?Article
    {
        return $this->article;
    }

    public function setArticle(?Article $article): void
    {
        $this->article = $article;
    }

    public function getActive(): ?string
    {
        return $this->active;
    }

    public function setActive(?string $active): void
    {
        $this->active = $active;
    }
}
