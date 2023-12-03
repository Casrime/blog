<?php

declare(strict_types=1);

namespace Framework\Twig;

use Framework\Core\ContainerInterface;
use Framework\Exception\GenericException;
use Framework\Routing\Router;
use Twig\TwigFunction;

final class Path implements PathInterface
{
    private ContainerInterface $container;

    public function __construct(ContainerInterface $container)
    {
        $this->container = $container;
    }

    public function path(): TwigFunction
    {
        /** @var Router $router */
        $router = $this->container->get('router');

        return new TwigFunction('path', function (string $value, array $options = []) use ($router): string {
            foreach ($router->getRoutes()->all() as $route) {
                if ($value === $route->getName()) {
                    $matches = $router->matchRegex($route);
                    foreach ($matches as $match) {
                        foreach ($options as $key => $value) {
                            if ($key === $route->removeSpecialChars($match)) {
                                $argumentName = $route->defineArgumentName($match);
                                $route->setArgument($argumentName, $route->defineArgumentValue($value));
                            }
                        }
                    }

                    $originalRoute = clone $route;
                    $originalRoute->setPath($route->getPath());
                    $route->updatePath();

                    if (0 < count($router->matchRegex($route))) {
                        throw new GenericException(sprintf('missing parameter : %s', $route->removeSpecialChars($router->matchRegex($route)[0])));
                    }

                    $path = $route->getPath();

                    // Restore original path with wildcard if path is used multiple times for matching with regex
                    $route->setPath($originalRoute->getPath());

                    return $path;
                }
            }
            throw new GenericException(sprintf('No route found with the name %s', $value));
        });
    }
}
