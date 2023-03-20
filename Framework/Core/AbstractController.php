<?php

declare(strict_types=1);

namespace Framework\Core;

use App\Model\ModelInterface;
use Framework\Form\Form;
use Framework\Form\FormInterface;
use Framework\Form\FormTypeInterface;
use Framework\HttpFoundation\Response;
use Framework\Routing\Router;
use Framework\Slugger\Slugger;
use Framework\Twig\FormView;
use Framework\Twig\Path;
use Twig\Environment;
use Twig\Loader\FilesystemLoader;

abstract class AbstractController implements ControllerInterface
{
    private Router $router;
    private Slugger $slug;

    public function __construct()
    {
        $this->router = new Router();
        $this->slug = new Slugger();
    }

    public function render(string $template, array $options = []): Response
    {
        $loader = new FilesystemLoader('../templates');
        $twig = new Environment($loader, [
            // TODO - use cache ?
            // 'cache' => '../var/cache',
            // TODO - change debug to false
            'debug' => true,
        ]);
        $twig->addFunction((new Path())->path());
        $twig->addFunction((new FormView())->renderView());

        return new Response($twig->render($template, $options));
    }

    public function getSlug(): Slugger
    {
        return $this->slug;
    }

    protected function createForm(FormTypeInterface $formType, ?ModelInterface $model = null): FormInterface
    {
        return (new Form())->createForm($formType, $model);
    }

    protected function redirectToRoute(string $name, array $options = [])
    {
        // TODO - replace this by RouteInterface
        foreach ($this->router->loadRoutes()->all() as $route) {
            if ($name === $route->getName()) {
                $route->setArguments($options);
                $route->updatePath();
                // TODO - replace this by a RedirectResponse
                // return new RedirectResponse($name, $options);
                header('Location: '.$route->getPath());
            }
        }
        throw new \Exception('Route not found');
    }

    protected function addFlash(string $key, string $message): void
    {
        // TODO: Implement addFlash() method.
    }
}
