<?php

declare(strict_types=1);

namespace Framework\Form;

use Framework\Database\Model\ModelInterface;
use Framework\HttpFoundation\Request;

interface FormInterface
{
    public function createForm(FormTypeInterface $formType, ?ModelInterface $model = null): self;

    public function handleRequest(Request $request): self;

    public function isSubmitted(): bool;

    public function isValid();

    public function getData();

    public function getFieldCollection(): FieldCollection;
}
