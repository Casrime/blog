<?php

declare(strict_types=1);

namespace Framework\Routing;

use Framework\Core\ContainerInterface;
use Framework\Exception\GenericException;
use Framework\Exception\NotFoundException;
use Framework\HttpFoundation\Request;
use Framework\Slugger\SluggerInterface;

class Router
{
    public string $currentRequestUri = '';
    private ?Route $currentRoute = null;
    private RouteCollection $routes;
    private ContainerInterface $container;

    public function __construct(ContainerInterface $container)
    {
        $this->routes = $this->loadRoutes();
        $this->container = $container;
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
            throw new NotFoundException('Route not found');
        }

        // Push the current route arguments into the Request object
        foreach ($this->currentRoute->getArguments() as $key => $value) {
            if (str_starts_with($value, '/')) {
                $value = substr($value, 1);
            }
            /** @var SluggerInterface $slugger */
            $slugger = $this->container->get('slugger');
            $value = $slugger->slug($value);
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
                    throw new GenericException('This route does not allowed this method', 405);
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
