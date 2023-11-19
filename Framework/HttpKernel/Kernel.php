<?php

declare(strict_types=1);

namespace Framework\HttpKernel;

use Framework\Core\AbstractController;
use Framework\HttpFoundation\RedirectResponse;
use Framework\HttpFoundation\Request;
use Framework\HttpFoundation\Response;
use Framework\Routing\Route;
use Framework\Routing\Router;

final class Kernel implements KernelInterface
{
    private Router $router;

    public function __construct()
    {
        $this->router = new Router();
    }

    public function handle(Request $request): Response
    {
        $this->router->loadRoutes();

        try {
            return $this->handleRaw($request);
        } catch (\Exception $exception) {
            // TODO - remove this var_dump
            var_dump($exception->getMessage());
            return new Response('error');
        }
    }

    private function handleRaw(Request $request)
    {
        $currentRoute = $this->router->match($request);
        $user = $request->session->get('user');
        if (null === $user && null !== $currentRoute->getRole()) {
            // TODO - handle /login url if not exists
            return new RedirectResponse('/login');
        }

        if (null !== $currentRoute->getRole() && !in_array($currentRoute->getRole(), $user->getRoles())) {
            throw new \Exception('You are not allowed to access this page');
        }

        // Try to get the controller
        // TODO - check the currentRoute has mandatory parameters like :
        $controller = $currentRoute->getController();
        if (2 !== count($controller)) {
            throw new \Exception('The controller parameter should have two values');
        }

        $controllerFQN = $controller[0];
        if (!class_exists($controllerFQN)) {
            throw new \Exception(sprintf('The %s parameter is not a class we can instantiate ', $controllerFQN));
        }
        // var_dump(class_exists($controllerFQN));
        $controllerMethod = $controller[1];
        $instantiateController = new $controllerFQN();
        if (!$instantiateController instanceof AbstractController) {
            var_dump('not instance of AbstractController');
        }

        // Try to get the method of the controller
        // TODO - check if the controller method exists in the controller called
        // var_dump($instantiateController, $controllerMethod);
        if (!method_exists($instantiateController, $controllerMethod)) {
            // TODO - add controller name and method name
            var_dump('this method does not exist in this controller');
            // die;
        }

        return $instantiateController->$controllerMethod($request);
    }
}
