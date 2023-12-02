<?php

declare(strict_types=1);

namespace Framework\Routing;

use Framework\HttpFoundation\Request;

class Router
{
    public string $currentRequestUri = '';
    private ?Route $currentRoute = null;
    private RouteCollection $routes;

    public function __construct()
    {
        $this->routes = $this->loadRoutes();
    }

    private function loadRoutes(): RouteCollection
    {
        $this->routes = include '../config/routes.php';

        return $this->routes;
    }

    public function removeParamsFromUri(string $requestUri): string|false
    {
        // check if there is a '?' or not
        if (!str_contains($requestUri, '?')) {
            return $requestUri;
        }

        return strstr($requestUri, '?', true);
    }

    public function match(Request $request): Route
    {
        $this->currentRequestUri = $this->removeParamsFromUri($request->getDataFromServer('request_uri'));

        $this->handleRoutes($request);

        if (null === $this->currentRoute) {
            // TODO - Convert an exception to a response object
            // TODO - add informations about the error
            throw new \Exception('Route not found');
        }

        // Push the current route arguments into the Request object
        foreach ($this->currentRoute->getArguments() as $key => $value) {
            $request->query->set($key, $value);
        }

        // TODO - is this really needed ?
        // it just lower case every server key
        foreach ($request->server->all() as $key => $value) {
            $request->server->set(strtolower($key), $value);
        }

        return $this->currentRoute;
    }

    public function handleRoutes(Request $request): void
    {
        foreach ($this->routes->all() as $route) {
            $matches = $this->matchRegex($route);
            foreach ($matches as $match) {
                $argumentName = $route->defineArgumentName($match);
                $route->setArgument($argumentName, $route->defineArgumentValue($this->currentRequestUri));
            }

            $route->updatePath();

            // If a route match the current route, get the controller associated
            if ($route->getPath() === $this->currentRequestUri) {
                $this->currentRoute = $route;

                if (0 === count($this->currentRoute->getMethods())) {
                    $this->currentRoute->setMethods([
                        'GET', 'POST', 'PUT', 'DELETE',
                    ]);
                }
                // Check if HTTP method is allowed or not
                $requestMethod = $request->getDataFromServer('request_method');
                if (false === in_array($requestMethod, $this->currentRoute->getMethods())) {
                    // TODO - send the status code 405 and the method name allowed for this route, and the current route methode used
                    throw new \Exception('This route does not allowed this method');
                }
            }
        }
    }

    public function matchRegex(Route $route): array
    {
        preg_match('/\/{[a-zA-Z0-9-_]*}/', $route->getPath(), $matches);

        return $matches;
    }

    public function getRoutes(): RouteCollection
    {
        return $this->routes;
    }
}
