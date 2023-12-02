<?php

declare(strict_types=1);

namespace Framework\Core;

final class Container implements ContainerInterface
{
    private array $services = [];

    public function get(string $serviceName): object
    {
        if (isset($this->services[$serviceName])) {
            $callback = $this->services[$serviceName];
            return $callback();
        }
        throw new \Exception("Service not registered: $serviceName");
    }

    public function has(string $serviceName): bool
    {
        return array_key_exists($serviceName, $this->services);
    }

    public function register($serviceName, $callback): void
    {
        $this->services[$serviceName] = $callback;
    }
}
