<?php

use App\Controller\BackController;
use App\Controller\FrontController;
use Framework\Routing\Route;
use Framework\Routing\RouteCollection;

$routeCollection = new RouteCollection();
$routeCollection->add(new Route('/', 'home', [FrontController::class, 'home']));
$routeCollection->add(new Route('/blog', 'blog', [FrontController::class, 'blog']));
$routeCollection->add(new Route('/article/{slug}', 'show_article', [FrontController::class, 'article'], ['GET']));
$routeCollection->add(new Route('/admin', 'admin', [BackController::class, 'admin'], ['GET']));
$routeCollection->add(new Route('/admin/article/new', 'new_article', [BackController::class, 'newArticle'], ['GET', 'POST']));
$routeCollection->add(new Route('/admin/article/edit/{slug}', 'edit_article', [BackController::class, 'editArticle'], ['GET', 'POST']));
$routeCollection->add(new Route('/admin/article/delete/{slug}', 'delete_article', [BackController::class, 'deleteArticle'], ['GET', 'DELETE']));
$routeCollection->add(new Route('/register', 'register', [FrontController::class, 'register'], ['GET']));
$routeCollection->add(new Route('/login', 'login', [FrontController::class, 'login'], ['GET']));

return $routeCollection;
