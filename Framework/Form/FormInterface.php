<?php

declare(strict_types=1);

namespace Framework\Form;

use Framework\HttpFoundation\Request;

interface FormInterface
{
    public function handleRequest(Request $request): self;

    public function isSubmitted(): bool;

    public function isValid();

    public function getData();
}
