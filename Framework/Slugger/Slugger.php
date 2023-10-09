<?php

declare(strict_types=1);

namespace Framework\Slugger;

final class Slugger implements SluggerInterface
{
    public function slug(string $string, string $separator = '-'): string
    {
        // TODO - handle special chars like é, è, ç, etc.
        return preg_replace('/\s+/', $separator, mb_strtolower(trim(strip_tags($string)), 'UTF-8'));
    }
}
