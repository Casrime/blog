<?php

declare(strict_types=1);

namespace Framework\Form\Type;

final class UrlType extends InputType
{
    public function getType(): string
    {
        return 'url';
    }
}
