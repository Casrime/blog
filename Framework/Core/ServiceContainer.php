<?php

declare(strict_types=1);

namespace Framework\Core;

use Framework\Database\Manager;
use Framework\Database\ServiceRepository;
use Framework\Form\Form;
use Framework\Routing\Router;
use Framework\Security\Security;
use Framework\Slugger\Slugger;
use Framework\Twig\FormEnd;
use Framework\Twig\FormRow;
use Framework\Twig\FormStart;
use Framework\Twig\FormView;
use Framework\Twig\Path;
use Twig\Environment;
use Twig\Extension\DebugExtension;
use Twig\Loader\FilesystemLoader;

final class ServiceContainer
{
    public function registerContainer(): ContainerInterface
    {
        $container = new Container();
        $container->register('form', function () {
            return new Form();
        });

        $container->register('manager', function () {
            return new Manager();
        });

        $container->register('router', function () {
            return new Router();
        });

        $container->register('security', function () {
            return new Security();
        });

        $container->register('service_repository', function () {
            return new ServiceRepository();
        });

        $container->register('slugger', function () {
            return new Slugger();
        });

        $container->register('twig', function () use ($container) {
            $loader = new FilesystemLoader('../templates');
            $twig = new Environment($loader, [
                // 'cache' => '/path/to/compilation_cache',
                'debug' => true,
            ]);
            $twig->addExtension(new DebugExtension());
            $twig->addFunction((new Path($container))->path());
            $twig->addFunction((new FormView())->renderView());
            $twig->addFunction((new FormStart())->renderView());
            $twig->addFunction((new FormEnd())->renderView());
            $twig->addFunction((new FormRow())->renderView());
            // TODO - replace $_SESSION['user']
            $twig->addGlobal('user', $_SESSION['user'] ?? null);

            return $twig;
        });

        return $container;
    }
}
