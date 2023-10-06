<?php

declare(strict_types=1);

namespace Framework\Form\Type;

interface AbstractTypeInterface
{
    public function getName(): string;

    public function setValue(?string $value): void;
}
