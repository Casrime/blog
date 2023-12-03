<?php

declare(strict_types=1);

namespace Framework\Slugger;

final class Slugger implements SluggerInterface
{
    public function slug(string $string, string $separator = '-'): string
    {
        $string = preg_replace('/\s+/', '-', mb_strtolower(trim(strip_tags($string)), 'UTF-8'));
        $string = preg_replace('#à|á|â|ã|ä|å#', 'a', $string);
        $string = preg_replace('#ç#', 'c', $string);
        $string = preg_replace('#è|é|ê|ë#', 'e', $string);
        $string = preg_replace('#ì|í|î|ï#', 'i', $string);
        $string = preg_replace('#ð|ò|ó|ô|õ|ö#', 'o', $string);
        $string = preg_replace('#ù|ú|û|ü#', 'u', $string);
        $string = preg_replace('#ý|ÿ#', 'y', $string);
        return preg_replace('#|_|:|,|\'|"#', '', $string);
    }
}
