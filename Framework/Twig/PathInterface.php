<?php

declare(strict_types=1);

namespace Framework\Twig;

use Twig\TwigFunction;

interface PathInterface
{
    public function path(): TwigFunction;
}
