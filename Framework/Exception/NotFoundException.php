<?php

declare(strict_types=1);

namespace Framework\Exception;

class NotFoundException extends \Exception
{
    public function __construct(string $message = 'Page not found', int $code = 404)
    {
        parent::__construct($message, $code);
    }
}
