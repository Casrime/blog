<?php

declare(strict_types=1);

namespace Framework\HttpFoundation;

interface ParameterInterface
{
    public function has(string $key): bool;

    public function get(string $key): mixed;

    public function set(string $key, mixed $value): void;

    /**
     * @return array<string, mixed>
     */
    public function all(): array;
}
