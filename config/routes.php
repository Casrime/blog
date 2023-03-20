<?php

use App\Controller\BackController;
use App\Controller\FrontController;
use Framework\Routing\Route;
use Framework\Routing\RouteCollection;

$routeCollection = new RouteCollection();
$routeCollection
    ->add(new Route('/', 'home', [FrontController::class, 'home']))
    ->add(new Route('/blog', 'blog', [FrontController::class, 'blog']))
    ->add(new Route('/article/{slug}', 'show_article', [FrontController::class, 'article'], ['GET', 'POST']))
    ->add(new Route('/admin', 'admin', [BackController::class, 'admin'], ['GET']))
    ->add(new Route('/admin/article/new', 'new_article', [BackController::class, 'newArticle'], ['GET', 'POST']))
    ->add(new Route('/admin/article/edit/{slug}', 'edit_article', [BackController::class, 'editArticle'], ['GET', 'POST']))
    ->add(new Route('/admin/article/delete/{slug}', 'delete_article', [BackController::class, 'deleteArticle'], ['GET', 'DELETE']))
    ->add(new Route('/register', 'register', [FrontController::class, 'register'], ['GET', 'POST']))
    ->add(new Route('/login', 'login', [FrontController::class, 'login'], ['GET']))
;

return $routeCollection;
