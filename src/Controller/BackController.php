<?php

declare(strict_types=1);

namespace App\Controller;

use App\Form\ArticleType;
use App\Model\Article;
use App\Model\Comment;
use Framework\Core\AbstractController;
use Framework\HttpFoundation\Request;
use Framework\HttpFoundation\Response;

final class BackController extends AbstractController
{
    public function admin(): Response
    {
        return $this->render('back/admin.html.twig', [
            'articles' => $this->getRepository(Article::class)->findAll(),
            'comments' => $this->getRepository(Comment::class)->findAll(),
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
        $article = $this->getRepository(Article::class)->findOneBy(['slug' => 'title-eleven-updated']);
        if (null === $article) {
            throw new \Exception('Article not found');
        }

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
            'form' => $form,
        ]);
    }

    public function deleteArticle(Request $request): Response
    {
        // TODO - replace this with a redirectToRoute method
        return new Response('article deleted');
    }
}
