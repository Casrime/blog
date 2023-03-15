<?php

declare(strict_types=1);

namespace App\Controller;

use Framework\Core\AbstractController;
use Framework\HttpFoundation\Request;
use Framework\HttpFoundation\Response;

final class FrontController extends AbstractController
{
    public function home(): Response
    {
        return $this->render('front/index.html.twig', [
            'foo' => 'bar',
        ]);
    }

    public function blog(): Response
    {
        $articles = [
            [
                'title' => 'foo title',
                'content' => 'foo content',
                // TODO - replace this by a slugger
                'slug' => 'foo-title',
            ],
            [
                'title' => 'bar title',
                'content' => 'bar content',
                // TODO - replace this by a slugger
                'slug' => 'bar-title',
            ],
        ];

        return $this->render('front/blog.html.twig', [
            'articles' => $articles,
        ]);
    }

    public function article(Request $request): Response
    {
        $slug = $request->query->get('slug');
        $article = [
            // TODO - replace this
            'title' => str_replace('-', ' ', $slug),
            'slug' => $slug,
        ];

        return $this->render('front/article.html.twig', [
            'article' => $article,
        ]);
    }

    public function register(): Response
    {
        return $this->render('front/register.html.twig');
    }

    public function login(): Response
    {
        return $this->render('front/login.html.twig');
    }
}
