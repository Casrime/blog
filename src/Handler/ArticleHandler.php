<?php

declare(strict_types=1);

namespace App\Handler;

use App\Model\Article;
use Framework\Core\ContainerInterface;
use Framework\Database\ManagerInterface;
use Framework\Slugger\SluggerInterface;

final class ArticleHandler
{
    public function __construct(private readonly ContainerInterface $container)
    {
    }

    public function add(Article $article): void
    {
        $this->slug($article);
        $article->setUser($this->container->get('session')->get('user'));
        $this->save($article);
    }

    public function edit(Article $article): void
    {
        $this->slug($article);
        //$article->setUser($this->container->get('session')->get('user'));
        $article->setUpdatedAt(new \DateTime());
        $this->save($article);
    }

    public function delete(): void
    {
    }

    private function save(Article $article): void
    {
        /** @var ManagerInterface $manager */
        $manager = $this->container->get('manager');
        $manager->persist($article);
        $manager->flush();
    }

    private function slug(Article $article): void
    {
        /** @var SluggerInterface $slugger */
        $slugger = $this->container->get('slugger');
        $article->setSlug($slugger->slug($article->getTitle()));
    }
}
