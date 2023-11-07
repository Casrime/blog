<?php

declare(strict_types=1);

namespace Framework\Validator\Constraints;

final class Url
{
    public string $message = 'The url must be start with http:// or https://.';

    public function validate($value): bool
    {
        if (str_starts_with($value, 'http://') || str_starts_with($value, 'https://')) {
            return false;
        }

        return true;
    }
}
