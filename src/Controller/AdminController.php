<?php

declare(strict_types=1);

namespace App\Controller;

use App\Model\Comment;
use App\Model\User;
use Framework\Core\AbstractController;
use Framework\Database\ManagerInterface;
use Framework\HttpFoundation\Request;
use Framework\HttpFoundation\Response;

final class AdminController extends AbstractController
{
    public function admin(): Response
    {
        $users = $this->getRepository(User::class)->findBy(['active' => '0']);
        $comments = $this->getRepository(Comment::class)->findBy(['active' => '0']);

        return $this->render('back/admin.html.twig', [
            'users' => $users,
            'comments' => $comments,
        ]);
    }

    public function adminActivateUser(Request $request): Response
    {
        $id = $request->query->get('id');
        $user = $this->getRepository(User::class)->findOneBy(['id' => $id]);
        $user->setActive('1');
        /** @var ManagerInterface $manager */
        $manager = $this->getContainer()->get('manager');
        $manager->persist($user);
        $manager->flush();

        $this->addFlash('success', 'Utilisateur activé');

        return $this->redirectToRoute('admin');
    }

    public function adminActivateComment(Request $request): Response
    {
        $id = $request->query->get('id');
        $comment = $this->getRepository(Comment::class)->findOneBy(['id' => $id]);
        $comment->setActive('1');
        /** @var ManagerInterface $manager */
        $manager = $this->getContainer()->get('manager');
        $manager->persist($comment);
        $manager->flush();

        $this->addFlash('success', 'Commentaire activé');

        return $this->redirectToRoute('admin');
    }
}
