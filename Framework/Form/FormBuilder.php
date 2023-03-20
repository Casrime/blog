<?php

declare(strict_types=1);

namespace Framework\Form;

final class FormBuilder
{
    private FieldCollection $fieldCollection;

    public function __construct()
    {
        $this->fieldCollection = new FieldCollection();
    }

    public function getFields(): FieldCollection
    {
        return $this->fieldCollection;
    }
}
