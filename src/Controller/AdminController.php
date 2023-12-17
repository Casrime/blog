<?php

declare(strict_types=1);

namespace App\Controller;

use App\Model\Article;
use App\Model\Comment;
use Framework\Core\AbstractController;
use Framework\HttpFoundation\Response;

final class AdminController extends AbstractController
{
    public function admin(): Response
    {
        $articles = $this->getRepository(Article::class)->findAll();
        $comments = $this->getRepository(Comment::class)->findAll();
        var_dump($articles);
        var_dump($comments);
        die;
        return $this->render('back/admin.html.twig', [
            'articles' => $this->getRepository(Article::class)->findAll(),
            'comments' => $this->getRepository(Comment::class)->findAll(),
        ]);
    }
}
