<?php

declare(strict_types=1);

namespace App\Controller;

use App\Form\ArticleType;
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
        $form = $this->createForm(new ArticleType());
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $article = $form->getData();
            // TODO - implement this method
            // $this->manager->persist($article);
            // $this->manager->flush();

            return $this->redirectToRoute('admin');
        }

        return $this->render('back/new.html.twig', [
            'form' => $form,
        ]);
    }

    public function editArticle(Request $request): Response
    {
        $slug = $request->query->get('slug');
        // var_dump($request->query->get('slug'));
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

        $form = $this->createForm(new ArticleType());
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            return $this->redirectToRoute('show_article', [
                'slug' => $slug,
            ]);
        }

        return $this->render('back/edit.html.twig', [
            'article' => $article,
            'slug' => $slug,
            'form' => $form,
        ]);
    }

    public function deleteArticle(Request $request): Response
    {
        // TODO - replace this with a redirectToRoute method
        return new Response('article deleted');
    }
}
