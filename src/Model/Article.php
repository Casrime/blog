<?php

declare(strict_types=1);

namespace App\Model;

use Framework\Database\ArrayCollection;
use Framework\Database\CollectionInterface;
use Framework\Database\Model\ModelInterface;

final class Article implements ModelInterface
{
    private ?int $id = null;
    private ?string $title = null;
    private string $slug;
    private ?string $chapo = null;
    private ?User $user = null;
    private ?string $content = null;
    private \DateTime $createdAt;
    private ?\DateTime $updatedAt = null;
    private CollectionInterface $comments;

    public function __construct()
    {
        $this->comments = new ArrayCollection();
        $this->createdAt = new \DateTime();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function setId(int $id): void
    {
        $this->id = $id;
    }

    public function getTitle(): ?string
    {
        return $this->title;
    }

    public function setTitle(?string $title): void
    {
        $this->title = $title;
    }

    public function getSlug(): string
    {
        return $this->slug;
    }

    public function setSlug(string $slug): void
    {
        $this->slug = $slug;
    }

    public function getChapo(): ?string
    {
        return $this->chapo;
    }

    public function setChapo(?string $chapo): void
    {
        $this->chapo = $chapo;
    }

    public function getUser(): ?User
    {
        return $this->user;
    }

    public function setUser(?User $user): void
    {
        $this->user = $user;
    }

    public function getContent(): ?string
    {
        return $this->content;
    }

    public function setContent(?string $content): void
    {
        $this->content = $content;
    }

    public function getCreatedAt(): \DateTime
    {
        return $this->createdAt;
    }

    public function setCreatedAt(\DateTime $createdAt): void
    {
        $this->createdAt = $createdAt;
    }

    public function getUpdatedAt(): ?\DateTime
    {
        return $this->updatedAt;
    }

    public function setUpdatedAt(?\DateTime $updatedAt): void
    {
        $this->updatedAt = $updatedAt;
    }

    public function getComments(): array
    {
        return $this->comments->getModels();
    }

    public function addComment(Comment $comment): void
    {
        $this->comments->add($comment);
    }

    public function removeComment(Comment $comment): void
    {
        $key = array_search($comment, $this->comments->getModels());
        if (false !== $key) {
            unset($this->comments[$key]);
        }
    }
}
