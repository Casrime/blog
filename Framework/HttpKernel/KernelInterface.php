<?php

declare(strict_types=1);

namespace Framework\HttpKernel;

use Framework\HttpFoundation\Request;
use Framework\HttpFoundation\Response;

interface KernelInterface
{
    public function handle(Request $request): Response;
}
