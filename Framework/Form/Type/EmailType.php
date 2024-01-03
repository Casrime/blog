<?php

declare(strict_types=1);

namespace Framework\Form\Type;

final class EmailType extends InputType
{
    public function getType(): string
    {
        return 'email';
    }
}
