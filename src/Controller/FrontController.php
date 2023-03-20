<?php

declare(strict_types=1);

namespace App\Controller;

use App\Form\CommentType;
use App\Form\ContactType;
use App\Form\RegisterType;
use Framework\Core\AbstractController;
use Framework\HttpFoundation\Request;
use Framework\HttpFoundation\Response;

final class FrontController extends AbstractController
{
    public function home(Request $request): Response
    {
        $form = $this->createForm(new ContactType());
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $form->getData();
            $this->addFlash('message_send', 'Email envoyé');

            return $this->redirectToRoute('home');
        }

        return $this->render('front/index.html.twig', [
            'foo' => 'bar',
            'form' => $form,
        ]);
    }

    public function blog(): Response
    {
        // TODO - replace this
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

        return $this->render('front/blog.html.twig', [
            'articles' => $articles,
        ]);
    }

    public function article(Request $request): Response
    {
        $slug = $request->query->get('slug');
        // TODO - replace this
        $article = [
            'title' => $this->getSlug()->slug($slug),
            'slug' => $slug,
        ];

        $form = $this->createForm(new CommentType());
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            //$form->getData();
            $this->addFlash('comment_send', 'Le commentaire a été posté. Il sera publié après validation');

            return $this->redirectToRoute('show_article', [
                'slug' => $article['slug'],
                //'slug' => $comment->getArticle()->getSlug(),
            ]);
        }

        return $this->render('front/article.html.twig', [
            'article' => $article,
            'form' => $form,
        ]);
    }

    public function register(Request $request): Response
    {
        $form = $this->createForm(new RegisterType());
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            var_dump($form->getData());
            //$user = $form->getData();
            // TODO - hash user password
            //$em->persist($user);
            //$em->flush();

            // Flash message
            return $this->redirectToRoute('home');
        }

        return $this->render('front/register.html.twig', [
            'form' => $form,
        ]);
    }

    public function login(): Response
    {
        return $this->render('front/login.html.twig');
    }
}
