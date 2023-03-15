<?php

declare(strict_types=1);

namespace Framework\Slugger;

interface SluggerInterface
{
    public function slug(string $string, string $separator = '-'): string;
}
