<?php

declare(strict_types=1);

namespace Framework\Exception;

class AccessDeniedException extends \Exception
{
    public function __construct(string $message = 'You are not allowed to access this page', int $code = 403)
    {
        parent::__construct($message, $code);
    }
}
