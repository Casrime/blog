<?php

namespace Framework\Form;

abstract class FormType implements FormTypeInterface
{
    protected FieldCollection $fieldCollection;

    public function __construct()
    {
        $this->fieldCollection = new FieldCollection();
    }

    public function getFields(): FieldCollection
    {
        return $this->fieldCollection;
    }
}
