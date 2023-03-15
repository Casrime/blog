<?php

declare(strict_types=1);

namespace Framework\Routing;

final class RouteCollection
{
    /**
     * @var array<Route>
     */
    private array $routes = [];

    public function add(Route $route): void
    {
        $this->routes[] = $route;
    }

    /**
     * @return array<Route>
     */
    public function all(): array
    {
        return $this->routes;
    }

    // get
    // remove
}
