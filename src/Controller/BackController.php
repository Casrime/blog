<?php

declare(strict_types=1);

namespace App\Controller;

use App\Form\ArticleType;
use App\Model\Article;
use App\Model\Comment;
use Framework\Core\AbstractController;
use Framework\Database\ManagerInterface;
use Framework\Exception\NotFoundException;
use Framework\HttpFoundation\Request;
use Framework\HttpFoundation\Response;
use Framework\Slugger\SluggerInterface;

final class BackController extends AbstractController
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

    public function newArticle(Request $request): Response
    {
        $form = $this->createForm(new ArticleType(), new Article());
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            /** @var SluggerInterface $slugger */
            $slugger = $this->getContainer()->get('slugger');
            /** @var Article $article */
            $article = $form->getData();
            $article->setUser($request->session->get('user'));
            $article->setSlug($slugger->slug($article->getTitle()));

            /** @var ManagerInterface $manager */
            $manager = $this->getContainer()->get('manager');
            $manager->persist($article);
            $manager->flush();

            return $this->redirectToRoute('admin');
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

        $form = $this->createForm(new ArticleType(), $article);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            /** @var SluggerInterface $slugger */
            $slugger = $this->getContainer()->get('slugger');
            /** @var Article $article */
            $article = $form->getData();
            $article->setSlug($slugger->slug($article->getTitle()));
            $article->setUpdatedAt(new \DateTime());

            /** @var ManagerInterface $manager */
            $manager = $this->getContainer()->get('manager');
            $manager->persist($article);
            $manager->flush();

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
