<?php

declare(strict_types=1);

namespace Framework\Core;

use Framework\Database\Model\ModelInterface;
use Framework\Database\ServiceRepositoryInterface;
use Framework\Form\FormInterface;
use Framework\Form\FormTypeInterface;
use Framework\HttpFoundation\RedirectResponse;
use Framework\HttpFoundation\Response;
use Framework\Routing\Router;

abstract class AbstractController implements ControllerInterface
{
    private ContainerInterface $container;

    public function __construct(ContainerInterface $container)
    {
        $this->container = $container;
    }

    public function render(string $template, array $options = []): Response
    {
        $twig = $this->container->get('twig');

        return new Response($twig->render($template, $options));
    }

    protected function createForm(FormTypeInterface $formType, ?ModelInterface $model = null): FormInterface
    {
        /** @var FormInterface $form */
        $form = $this->container->get('form');
        $form->createForm($formType, $model);

        return $form;
    }

    protected function redirectToRoute(string $name, array $options = []): RedirectResponse
    {
        /** @var Router $router */
        $router = $this->container->get('router');
        foreach ($router->getRoutes()->all() as $route) {
            if ($name === $route->getName()) {
                foreach ($options as $key => $value) {
                    if ('slug' === $key) {
                        $value = '/'.$value;
                    }
                    $route->setArgument($key, $value);
                }
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
        /** @var ServiceRepositoryInterface $serviceRepository */
        $serviceRepository = $this->getContainer()->get('service_repository');
        $serviceRepository->setEntityName($entityName);

        return $serviceRepository;
    }

    protected function getContainer(): ContainerInterface
    {
        return $this->container;
    }
}
