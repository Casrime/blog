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
        $form = $this->createForm(new ArticleType(), new Article());
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            /** @var Article $article */
            $article = $form->getData();
            $article->setSlug($this->slugger->slug($article->getTitle()));

            $this->manager->persist($article);
            $this->manager->flush();

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

        $article = new Article();
        $article->setId(11);
        $article->setTitle('title');
        $article->setChapo('chapo');
        $article->setContent('content');

        $form = $this->createForm(new ArticleType(), $article);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            /** @var Article $article */
            $article = $form->getData();
            $article->setSlug($this->slugger->slug($article->getTitle()));
            $article->setUpdatedAt(new \DateTime());

            $this->manager->persist($article);
            $this->manager->flush();

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
