<?php

declare(strict_types=1);

namespace Framework\HttpFoundation;

class RedirectResponse extends Response
{
    public function __construct(string $url, int $status = 302, array $headers = [])
    {
        parent::__construct('', $status, $headers);

        $this->setTargetUrl($url);
    }

     public function setTargetUrl($url): void
     {
         header('Location: '.$url);
     }
}
