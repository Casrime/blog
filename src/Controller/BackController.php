<?php

declare(strict_types=1);

namespace App\Controller;

use App\Form\ArticleType;
use App\Model\Article;
use Framework\Core\AbstractController;
use Framework\HttpFoundation\Request;
use Framework\HttpFoundation\Response;

final class BackController extends AbstractController
{
    public function admin(): Response
    {
        /*
        $articles = $this->articleRepository->findAll();
        */
        $articles = [
            [
                'title' => 'foo title',
                'content' => 'foo content',
                'slug' => $this->getSlug()->slug('foo title'),
            ],
            [
                'title' => 'bar title',
                'content' => 'bar content',
                'slug' => $this->getSlug()->slug('bar title'),
            ],
        ];

        return $this->render('back/admin.html.twig', [
            'articles' => $articles,
        ]);
    }

    public function newArticle(Request $request): Response
    {
        $form = $this->createForm(new ArticleType(), new Article());
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            // TODO - handle get data
            var_dump($form->getData());
            $this->addFlash('article_success', 'Le nouvel article a été créé');

            return $this->redirectToRoute('admin');
        }

        return $this->render('back/new.html.twig', [
            'form' => $form,
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

        return $this->render('back/edit.html.twig', [
            // 'article' => $article,
            'slug' => $slug,
        ]);
    }

    public function deleteArticle(Request $request): Response
    {
        // TODO - check if the article exists in database based on slug ?
        /*
        if (!$article) {
            throw new \Exception('There is no article with this slug');
        }
        */
        $this->addFlash('article_deleted', 'L\'article a bien été supprimé');

        return $this->redirectToRoute('admin');
    }
}
