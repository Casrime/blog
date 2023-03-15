<?php

declare(strict_types=1);

namespace Framework\Core;

use Framework\HttpFoundation\Response;
use Framework\Twig\Path;
use Twig\Environment;
use Twig\Loader\FilesystemLoader;

abstract class AbstractController implements ControllerInterface
{
    public function render(string $template, array $options = []): Response
    {
        $loader = new FilesystemLoader('../templates');
        $twig = new Environment($loader, [
            // 'cache' => '/path/to/compilation_cache',
            'debug' => true,
        ]);
        $twig->addFunction((new Path())->path());

        return new Response($twig->render($template, $options));
    }
}
