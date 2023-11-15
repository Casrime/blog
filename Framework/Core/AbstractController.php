<?php

declare(strict_types=1);

namespace Framework\Core;

use Framework\Database\Manager;
use Framework\Database\Model\ModelInterface;
use Framework\Database\ServiceRepository;
use Framework\Database\ServiceRepositoryInterface;
use Framework\Form\Form;
use Framework\Form\FormInterface;
use Framework\Form\FormTypeInterface;
use Framework\HttpFoundation\RedirectResponse;
use Framework\HttpFoundation\Response;
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

abstract class AbstractController implements ControllerInterface
{
    private Router $router;
    private ServiceRepository $serviceRepository;
    protected Manager $manager;
    protected Slugger $slugger;
    private Security $security;

    public function __construct()
    {
        $this->router = new Router();
        $this->serviceRepository = new ServiceRepository();
        $this->manager = new Manager();
        $this->slugger = new Slugger();
        $this->security = new Security;
    }

    public function render(string $template, array $options = []): Response
    {
        $loader = new FilesystemLoader('../templates');
        $twig = new Environment($loader, [
            // 'cache' => '/path/to/compilation_cache',
            'debug' => true,
        ]);
        $twig->addExtension(new DebugExtension());
        $twig->addFunction((new Path())->path());
        $twig->addFunction((new FormView())->renderView());
        $twig->addFunction((new FormStart())->renderView());
        $twig->addFunction((new FormEnd())->renderView());
        $twig->addFunction((new FormRow())->renderView());
        // TODO - replace $_SESSION['user']
        $twig->addGlobal('user', $_SESSION['user'] ?? null);

        return new Response($twig->render($template, $options));
    }

    protected function createForm(FormTypeInterface $formType, ?ModelInterface $model = null): FormInterface
    {
        $form = new Form();
        $form->createForm($formType, $model);

        return $form;
    }

    protected function redirectToRoute(string $name, array $options = []): RedirectResponse
    {
        // TODO - replace this by RouteInterface
        foreach ($this->router->loadRoutes()->all() as $route) {
            if ($name === $route->getName()) {
                var_dump($options);
                $route->setArguments($options);
                $route->updatePath();

                return new RedirectResponse($route->getPath(), 302, $options);
            }
        }
        throw new \Exception('Route not found');
    }

    protected function addFlash(string $key, string $message): void
    {
        // TODO: Implement addFlash() method.
    }

    protected function getRepository(string $entityName): ServiceRepositoryInterface
    {
        $this->serviceRepository->setEntityName($entityName);

        return $this->serviceRepository;
    }

    protected function getSecurity(): Security
    {
        return $this->security;
    }
}
