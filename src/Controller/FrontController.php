<?php

declare(strict_types=1);

namespace App\Controller;

use App\Form\CommentType;
use App\Form\ContactType;
use App\Form\LoginType;
use App\Form\RegisterType;
use App\Model\Article;
use App\Model\Comment;
use App\Model\Contact;
use App\Model\User;
use App\Repository\UserRepository;
use Framework\Core\AbstractController;
use Framework\Database\ManagerInterface;
use Framework\Exception\NotFoundException;
use Framework\HttpFoundation\Request;
use Framework\HttpFoundation\Response;
use Framework\Mailer\Email;
use Framework\Mailer\MailerInterface;
use Framework\Security\Security;

final class FrontController extends AbstractController
{
    public function home(Request $request): Response
    {
        $form = $this->createForm(new ContactType(), new Contact());
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            /** @var Contact $contact */
            $contact = $form->getData();
            /** @var MailerInterface $mailer */
            $mailer = $this->getContainer()->get('mailer');
            $email = new Email($contact->getEmail(), 'abdounikarim@gmail.com', 'Contact', 'mail.html.twig', [
                'lastname' => $contact->getLastname(),
                'firstname' => $contact->getFirstname(),
                'email' => $contact->getEmail(),
                'message' => $contact->getMessage(),
            ]);
            $mailer->send($email);
            $this->addFlash('success', 'Email envoyé');

            return $this->redirectToRoute('home');
        }

        return $this->render('front/index.html.twig', [
            'foo' => 'bar',
            'form' => $form,
        ]);
    }

    public function blog(): Response
    {
        return $this->render('front/blog.html.twig', [
            'articles' => $this->getRepository(Article::class)->findAll(),
        ]);
    }

    public function article(Request $request): Response
    {
        $slug = $request->query->get('slug');

        /** @var Article $article */
        $article = $this->getRepository(Article::class)->findOneBy(['slug' => $slug]);
        $comments = $this->getRepository(Comment::class)->findBy(['article_id' => $article->getId(), 'active' => '1']);

        if (null === $article) {
            throw new NotFoundException('Article not found');
        }

        $form = $this->createForm(new CommentType(), new Comment());
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $comment = $form->getData();
            $comment->setArticle($article);

            /** @var ManagerInterface $manager */
            $manager = $this->getContainer()->get('manager');
            $manager->persist($comment);
            $manager->flush();

            $this->addFlash('success', 'Commentaire ajouté');

            return $this->redirectToRoute('show_article', [
                'slug' => $article->getSlug(),
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
        $form = $this->createForm(new RegisterType($this->getContainer()->get('security')), new User());
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            /** @var User $user */
            $user = $form->getData();
            /** @var Security $security */
            $security = $this->getContainer()->get('security');
            $user->setPassword($security->hash($user->getPassword()));
            $user->setRoles(['ROLE_USER']);
            /** @var ManagerInterface $manager */
            $manager = $this->getContainer()->get('manager');
            $manager->persist($user);
            $manager->flush();

            $this->addFlash('success', 'Utilisateur enregistré');

            return $this->redirectToRoute('register');
        }

        return $this->render('front/register.html.twig', [
            'form' => $form,
        ]);
    }

    public function login(Request $request): Response
    {
        $form = $this->createForm(new LoginType(), new User());
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $user = $form->getData();
            $userRepository = new UserRepository($this->getContainer()->get('security'));

            $existingUser = $userRepository->login($user);

            if (null === $existingUser) {
                $this->addFlash('danger', 'Identifiants incorrects');

                return $this->redirectToRoute('login');
            }
            /** @var Security $security */
            $security = $this->getContainer()->get('security');
            $security->login($existingUser);

            $this->addFlash('success', 'Bon retour parmi nous '.$existingUser->getEmail());

            return $this->redirectToRoute('home');
        }

        return $this->render('front/login.html.twig', [
            'form' => $form,
        ]);
    }

    public function logout(Request $request): Response
    {
        /** @var Security $security */
        $security = $this->getContainer()->get('security');
        $security->logout();

        return $this->redirectToRoute('home');
    }
}
