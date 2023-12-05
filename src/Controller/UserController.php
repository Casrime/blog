<?php

declare(strict_types=1);

namespace App\Controller;

use App\Form\ArticleType;
use App\Model\Article;
use Framework\Core\AbstractController;
use Framework\Exception\AccessDeniedException;
use Framework\Exception\NotFoundException;
use Framework\HttpFoundation\Request;
use Framework\HttpFoundation\Response;

final class UserController extends AbstractController
{
    public function newArticle(Request $request): Response
    {
        $form = $this->createForm(new ArticleType(), new Article());
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            /** @var Article $article */
            $article = $form->getData();
            $this->getContainer()->get('article_handler')->add($article);
            $this->addFlash('success', 'Article créé');

            return $this->redirectToRoute('home');
        }

        return $this->render('back/new.html.twig', [
            'form' => $form,
        ]);
    }

    public function editArticle(Request $request): Response
    {
        $slug = $request->query->get('slug');
        // TODO - check if the slug exists in the article database table
        $article = $this->getRepository(Article::class)->findOneBy(['slug' => $slug]);
        if (null === $article) {
            throw new NotFoundException('Article not found');
        }

        // TODO - check if the user is the author of the article
        // TODO - or if the user is an admin
        if ($article->getUser()->getId() !== $request->session->get('user')->getId() && !in_array('ROLE_ADMIN', $request->session->get('user')->getRoles())) {
            throw new AccessDeniedException('You are not allowed to edit this article');
        }

        $form = $this->createForm(new ArticleType(), $article);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            /** @var Article $article */
            $article = $form->getData();
            $this->getContainer()->get('article_handler')->edit($article);
            $this->addFlash('success', 'Article modifié');

            return $this->redirectToRoute('show_article', [
                'slug' => $article->getSlug(),
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
