<?php

declare(strict_types=1);

namespace Framework\Core;

use Framework\Database\Manager;
use Framework\Database\ServiceRepository;
use Framework\Form\Form;
use Framework\HttpFoundation\Request;
use Framework\Mailer\Mailer;
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
use Twig\TwigFilter;

final class ServiceContainer
{
    public function registerContainer(): ContainerInterface
    {
        $container = new Container();

        $container->register('form', function () use ($container) {
            return new Form($container);
        });

        $container->register('mailer', function () use ($container) {
            /** @var Environment $twig */
            $twig = $container->get('twig');

            return new Mailer($twig);
        });

        $container->register('manager', function () {
            return new Manager();
        });

        $container->register('router', function () use ($container) {
            return new Router($container);
        });

        $container->register('security', function () use ($container) {
            return new Security($container);
        });

        $container->register('service_repository', function () {
            return new ServiceRepository();
        });

        $container->register('session', function () {
            return (new Request())->session;
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
            $twig->addGlobal('user', $container->get('session')->get('user') ?? null);
            $twig->addGlobal('flashes', $container->get('session')->getFlashBag());
            $twig->addFilter(new TwigFilter('string', function ($value) {
                return (string) $value;
            }));

            return $twig;
        });

        return $container;
    }
}
