<?php

declare(strict_types=1);

namespace App\Repository;

use App\Model\Article;

final class ArticleRepository extends ServiceRepository
{
    public function __construct()
    {
        //parent::__construct(Article::class);
    }
}
