<?php

declare(strict_types=1);

namespace App\Controller;

use App\Form\CommentType;
use App\Form\ContactType;
use App\Form\UserType;
use App\Model\Article;
use App\Model\Comment;
use App\Model\Contact;
use App\Model\User;
use App\Repository\ServiceRepository;
use Framework\Core\AbstractController;
use Framework\HttpFoundation\Request;
use Framework\HttpFoundation\Response;

final class FrontController extends AbstractController
{
    public function home(Request $request): Response
    {
        $form = $this->createForm(new ContactType(), new Contact());
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            /** @var Contact $contact */
            $contact = $form->getData();
            // TODO - implement Mailer interface
            // $this->mailer->send($contact);
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
        $service = new ServiceRepository();
        /** @var Article[] $articles */
        $articles = $service->fetchEntities("SELECT * FROM article", Article::class, []);
        var_dump($articles);
        foreach ($articles as $article) {
            var_dump($article->getComments());
        }
        die;
        //$articles = $this->getRepository(Article::class)->findAll();
        var_dump($articles);
        die;

        return $this->render('front/blog.html.twig', [
            'articles' => $this->articleRepository->findAll(),
        ]);
    }

    public function article(Request $request): Response
    {
        // TODO - use the slug, check the value is correct first
        $slug = $request->query->get('slug');

        /** @var Article $article */
        //$article = $this->articleRepository->findBy(['slug' => 'mon-premier-article']);
        //$article->getComments();
        //$comments = $this->commentRepository->findBy(['article_id' => $article->getId()]);
        //$comments = $this->commentRepository->findBy(['article_id' => 1]);
        //var_dump($article->getComments());
        //die;

        $comments = [
            [
                'comment' => 'comment one',
                'validated' => true,
            ],
            [
                'comment' => 'comment two',
                'validated' => true,
            ],
        ];

        $form = $this->createForm(new CommentType(), new Comment());
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $comment = $form->getData();
            // TODO - implement this method
            // $this->manager->persist($comment);
            // $this->manager->flush();

            return $this->redirectToRoute('show_article', [
                'slug' => $slug,
            ]);
        }

        return $this->render('front/article.html.twig', [
            'article' => $article,
            'comments' => $comments,
            'form' => $form,
        ]);
    }

    public function register(Request $request): Response
    {
        $form = $this->createForm(new UserType(), new User());
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $user = $form->getData();
            // TODO - implement these methods
            // $user->isActive(false);
            // $this->manager->persist($user);
            // $this->manager->flush();

            return $this->redirectToRoute('register');
        }

        return $this->render('front/register.html.twig', [
            'form' => $form,
        ]);
    }

    public function login(Request $request): Response
    {
        $form = $this->createForm(new UserType(), new User());
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $user = $form->getData();
            // TODO - check if email and password are valid and if user is active

            return $this->redirectToRoute('login');
        }

        return $this->render('front/login.html.twig', [
            'form' => $form,
        ]);
    }
}
