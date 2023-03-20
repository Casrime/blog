<?php

declare(strict_types=1);

namespace Framework\Form\Type;

// TODO - delete this type if it is not used
final class FileType extends AbstractType
{
    public function generateHtml(): string
    {
        return 'file';
    }
}
