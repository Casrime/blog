<?php

declare(strict_types=1);

namespace Framework\Routing;

use Framework\HttpFoundation\Request;

interface RouterInterface
{
    public function loadRoutes(): RouteCollection;

    public function removeParamsFromUri(string $requestUri): string|false;

    public function match(Request $request): Route;

    public function handleRoutes(Request $request): void;

    public function matchRegex(Route $route): array;
}
