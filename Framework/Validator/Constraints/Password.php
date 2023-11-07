<?php

declare(strict_types=1);

namespace Framework\Validator\Constraints;

final class Password
{
    public string $message = 'This value should have at least a minuscule character, a majuscule character, a number, a special character and 8 characters at least.';

    public function validate($value): bool
    {
        // Regex checks if the password contains at least one lowercase letter, one uppercase letter, one digit, one special character, and is at least 8 characters long.
        if (0 < preg_match('/^(?=.*[a-z])(?=.*[A-Z])(?=.*[0-9])(?=.*\W).{8,}$/', $value)) {
            return false;
        }

        return true;
    }
}
