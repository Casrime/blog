<?php

declare(strict_types=1);

namespace Framework\HttpFoundation;

class Parameter implements ParameterInterface
{
    /**
     * @param array<string, mixed> $parameters
     */
    public function __construct(private array $parameters)
    {
    }

    public function has(string $key): bool
    {
        return (bool) $this->parameters[$key];
    }

    public function get(string $key): mixed
    {
        return $this->parameters[$key] ?? null;
    }

    public function set(string $key, mixed $value): void
    {
        $this->parameters[$key] = $value;
    }

    /**
     * @return array<string, mixed>
     */
    public function all(): array
    {
        return $this->parameters;
    }

    public function getString(string $key): string
    {
        if (is_string($this->parameters[$key])) {
            return $this->parameters[$key];
        }

        return '';
    }
}
