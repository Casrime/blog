<?php

use App\Controller\AdminController;
use App\Controller\FrontController;
use App\Controller\UserController;
use Framework\Routing\Route;
use Framework\Routing\RouteCollection;

$routeCollection = new RouteCollection();
$routeCollection->add(new Route('/', 'home', [FrontController::class, 'home'], ['GET', 'POST']));
$routeCollection->add(new Route('/blog', 'blog', [FrontController::class, 'blog']));
$routeCollection->add(new Route('/article/{slug}', 'show_article', [FrontController::class, 'article'], ['GET', 'POST']));
$routeCollection->add(new Route('/register', 'register', [FrontController::class, 'register'], ['GET', 'POST']));
$routeCollection->add(new Route('/login', 'login', [FrontController::class, 'login'], ['GET', 'POST']));
$routeCollection->add(new Route('/logout', 'logout', [FrontController::class, 'logout'], ['GET']));
$routeCollection->add(new Route('/admin', 'admin', [AdminController::class, 'admin'], ['GET'], [], 'ROLE_ADMIN'));
$routeCollection->add(new Route('/admin/activate-user/{id}', 'admin_activate_user', [AdminController::class, 'adminActivateUser'], ['GET'], [], 'ROLE_ADMIN'));
$routeCollection->add(new Route('/admin/activate-comment/{id}', 'admin_activate_comment', [AdminController::class, 'adminActivateComment'], ['GET'], [], 'ROLE_ADMIN'));
$routeCollection->add(new Route('/user/article/new', 'new_article', [UserController::class, 'newArticle'], ['GET', 'POST'], [], 'ROLE_USER'));
$routeCollection->add(new Route('/user/article/edit/{slug}', 'edit_article', [UserController::class, 'editArticle'], ['GET', 'POST'], [], 'ROLE_USER'));
$routeCollection->add(new Route('/user/article/delete/{slug}', 'delete_article', [UserController::class, 'deleteArticle'], ['GET', 'DELETE'], [], 'ROLE_USER'));

return $routeCollection;
