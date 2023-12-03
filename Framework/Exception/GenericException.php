<?php

declare(strict_types=1);

namespace Framework\Exception;

class GenericException extends \Exception
{
    public function __construct(string $message = 'An error occured', int $code = 500)
    {
        parent::__construct($message, $code);
    }
}
