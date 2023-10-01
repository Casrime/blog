<?php

declare(strict_types=1);

namespace Framework\Core;

use App\Repository\ServiceRepository;
use Framework\Database\Model\ModelInterface;
use Framework\Form\Form;
use Framework\Form\FormInterface;
use Framework\Form\FormTypeInterface;
use Framework\HttpFoundation\RedirectResponse;
use Framework\HttpFoundation\Response;
use Framework\Routing\Router;
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

    public function __construct()
    {
        $this->router = new Router();
        $this->serviceRepository = new ServiceRepository();
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

    /*
    protected function getRepository($entityName): ServiceRepository
    {
        return $this->serviceRepository;
    }
    */
}
