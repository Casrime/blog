<?php

declare(strict_types=1);

namespace Framework\Core;

interface ContainerInterface
{
    public function get(string $serviceName): object;

    public function has(string $serviceName): bool;
}
