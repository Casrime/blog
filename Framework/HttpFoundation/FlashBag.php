<?php

declare(strict_types=1);

namespace Framework\HttpFoundation;

final class FlashBag
{
    public function add(string $key, mixed $value): void
    {
        $_SESSION['flashbag'][$key] = $value;
    }

    public function all(): array
    {
        $flashes = $_SESSION['flashbag'] ?? [];

        unset($_SESSION['flashbag']);

        return $flashes;
    }
}
