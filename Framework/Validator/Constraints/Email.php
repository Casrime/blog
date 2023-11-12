<?php

declare(strict_types=1);

namespace Framework\Validator\Constraints;

final class Email
{
    public string $message = 'This value is not a valid email address.';

    public function validate($value): bool
    {
        if (0 < preg_match('/^[a-z0-9._-]+@[a-z0-9._-]{1,}\.[a-z]{2,4}$/', $value)) {
            return false;
        }

        return true;
    }
}
