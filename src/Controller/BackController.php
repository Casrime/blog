<?php

declare(strict_types=1);

namespace App\Controller;

use Framework\Core\AbstractController;
use Framework\HttpFoundation\Request;
use Framework\HttpFoundation\Response;

final class BackController extends AbstractController
{
    public function admin(): Response
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

        return $this->render('back/admin.html.twig', [
            'articles' => $articles,
        ]);
    }

    public function newArticle(Request $request): Response
    {
        var_dump($request->request->get('title'));
        // $form = $this->createForm(ArticleType::class);
        // $form->handleRequest($request);

        return $this->render('back/new.html.twig', [
            'article' => 'a new article',
        ]);
    }

    public function editArticle(Request $request): Response
    {
        $slug = $request->query->get('slug');
        var_dump($request->query->get('slug'));
        // TODO - check if the slug exists in the article database table
        /*
        $article = $this->articleRepository->findBy([
            'slug' => $slug,
        ]);
        if (!$article) {
            throw new \Exception('There is no article with this slug');
        }
        */
        // TODO - if not, send an exception

        $article = [
            'title' => 'title',
            'chapo' => 'chapo',
            'author' => 'author',
            'content' => 'content',
        ];

        return $this->render('back/edit.html.twig', [
            'article' => $article,
            'slug' => $slug,
        ]);
    }

    public function deleteArticle(Request $request): Response
    {
        // TODO - replace this with a redirectToRoute method
        return new Response('article deleted');
    }

    public function adminHome(): Response
    {
        $data = [
            'lastname' => 'nom',
            'firstname' => 'prénom',
            'image' => 'https://cdn.pixabay.com/photo/2016/10/02/19/51/lion-1710301_1280.png',
            'chapo' => '',
            'cv' => '',
            'github' => '',
            'linkedin' => '',
            'twitter' => '',
        ];
        return $this->render('back/home.html.twig', [
            'data' => $data,
        ]);
    }
}
