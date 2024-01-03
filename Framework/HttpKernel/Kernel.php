<?php

declare(strict_types=1);

namespace Framework\HttpKernel;

use Framework\Core\AbstractController;
use Framework\Core\ContainerInterface;
use Framework\Core\ServiceContainer;
use Framework\Exception\AccessDeniedException;
use Framework\Exception\GenericException;
use Framework\HttpFoundation\RedirectResponse;
use Framework\HttpFoundation\Request;
use Framework\HttpFoundation\Response;
use Framework\Routing\Router;
use Twig\Environment;
use Twig\Loader\FilesystemLoader;

final class Kernel implements KernelInterface
{
    private ContainerInterface $container;

    public function handle(Request $request): Response
    {
        $this->container = (new ServiceContainer())->registerContainer();

        try {
            return $this->handleRaw($request);
        } catch (\Exception $exception) {
            return $this->handleException($exception);
        }
    }

    private function handleRaw(Request $request)
    {
        $currentRoute = $this->container->get('router')->match($request);
        $user = $request->session->get('user');
        if (null === $user && null !== $currentRoute->getRole()) {
            /** @var Router $router */
            $router = $this->container->get('router');

            if (true === $router->hasRoute('login')) {
                return new RedirectResponse($router->getRoutePathByName('login'));
            }
            throw new GenericException('The login route does not exist');
        }

        if (null !== $currentRoute->getRole() && !in_array($currentRoute->getRole(), $user->getRoles())) {
            throw new AccessDeniedException('You are not allowed to access this page');
        }

        // Try to get the controller
        $controller = $currentRoute->getController();
        if (2 !== count($controller)) {
            throw new GenericException('The controller parameter should have two values');
        }

        $controllerFQN = $controller[0];
        if (!class_exists($controllerFQN)) {
            throw new GenericException(sprintf('The %s parameter is not a class we can instantiate', $controllerFQN));
        }

        $controllerMethod = $controller[1];
        $instantiateController = new $controllerFQN($this->container);
        if (!$instantiateController instanceof AbstractController) {
            throw new GenericException(sprintf('The %s class does not extend the AbstractController class', $controllerFQN));
        }

        // Try to get the method of the controller
        if (!method_exists($instantiateController, $controllerMethod)) {
            throw new GenericException(sprintf('The %s method does not exist in the %s controller', $controllerMethod, $controllerFQN));
        }

        return $instantiateController->$controllerMethod($request);
    }

    private function handleException(\Exception $exception)
    {
        /** @var Environment $twig */
        $twig = $this->container->get('twig');
        $loader = new FilesystemLoader('../Framework/Error');
        $twig->setLoader($loader);

        if (404 === $exception->getCode()) {
            $message = 'Page non trouvée';
        } elseif (403 === $exception->getCode()) {
            $message = 'Accès refusé';
        } else {
            $message = 'Erreur interne';
        }

        return new Response($twig->render('error.html.twig', [
            'message' => $message,
        ]), $exception->getCode());
    }
}
