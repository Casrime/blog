<?php

declare(strict_types=1);

namespace App\Repository;

use App\Model\Comment;

final class CommentRepository extends ServiceRepository
{
    public function __construct()
    {
        parent::__construct(Comment::class);
    }
}
