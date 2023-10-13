<?php

declare(strict_types=1);

namespace App\Model;

use Framework\Database\Model\ModelInterface;

final class Contact implements ModelInterface
{
    private ?string $lastname = null;

    private ?string $firstname = null;

    private ?string $email = null;

    private ?string $message = null;

    public function getId(): int
    {
        return 1;
    }

    public function getLastname(): ?string
    {
        return $this->lastname;
    }

    public function setLastname(?string $lastname): void
    {
        $this->lastname = $lastname;
    }

    public function getFirstname(): ?string
    {
        return $this->firstname;
    }

    public function setFirstname(?string $firstname): void
    {
        $this->firstname = $firstname;
    }

    public function getEmail(): ?string
    {
        return $this->email;
    }

    public function setEmail(?string $email): void
    {
        $this->email = $email;
    }

    public function getMessage(): ?string
    {
        return $this->message;
    }

    public function setMessage(?string $message): void
    {
        $this->message = $message;
    }
}
