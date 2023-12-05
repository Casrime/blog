<?php

declare(strict_types=1);

namespace Framework\HttpFoundation;

final class Session
{
    public function has(string $key): bool
    {
        return (bool) $_SESSION[$key];
    }

    public function get(string $key): mixed
    {
        return $_SESSION[$key] ?? null;
    }

    public function set(string $key, mixed $value): void
    {
        $_SESSION[$key] = $value;
    }

    public function remove(string $key): void
    {
        unset($_SESSION[$key]);
    }

    /**
     * @return array<string, mixed>
     */
    public function all(): array
    {
        return $_SESSION;
    }

    public function getFlashBag(): FlashBag
    {
        return new FlashBag();
    }
}
