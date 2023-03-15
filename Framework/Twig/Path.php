<?php

declare(strict_types=1);

namespace Framework\Twig;

use Framework\Routing\RouteCollection;
use Framework\Routing\Router;
use Twig\TwigFunction;

final class Path implements PathInterface
{
    private Router $router;
    private RouteCollection $routes;

    public function __construct()
    {
        $this->router = new Router();
        $this->path();
    }

    public function path(): TwigFunction
    {
        $this->routes = $this->router->loadRoutes();

        return new TwigFunction('path', function (string $value, array $options = []): string {
            foreach ($this->routes->all() as $route) {
                if ($value === $route->getName()) {
                    $matches = $this->router->matchRegex($route);
                    foreach ($matches as $match) {
                        foreach ($options as $key => $value) {
                            if ($key === $route->removeSpecialChars($match)) {
                                $argumentName = $route->defineArgumentName($match);
                                $route->setArgument($argumentName, $route->defineArgumentValue($value));
                            }
                        }
                    }

                    $route->updatePath();
                    if (0 < count($this->router->matchRegex($route))) {
                        throw new \Exception(sprintf('missing parameter : %s', $route->removeSpecialChars($this->router->matchRegex($route)[0])));
                    }

                    return $route->getPath();
                }
            }
            throw new \Exception(sprintf('No route found with the name %s', $value));
        });
    }
}
