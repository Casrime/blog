<?php

declare(strict_types=1);

namespace Framework\Validator\Constraints;

final class NotBlank
{
    public string $message = 'This value should not be blank.';

    public function validate($value): bool
    {
        return '' === $value;
    }
}
