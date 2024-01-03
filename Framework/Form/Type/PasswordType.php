<?php

declare(strict_types=1);

namespace Framework\Form\Type;

final class PasswordType extends InputType
{
    public function getType(): string
    {
        return 'password';
    }
}
