<?php

declare(strict_types=1);

namespace Framework\Core;

use Framework\HttpFoundation\Response;

interface ControllerInterface
{
    /**
     * @param array<string, mixed> $options
     */
    public function render(string $template, array $options = []): Response;
}
