<?php

namespace Framework\Form;

interface FormTypeInterface
{
    public function buildForm(): void;

    public function getFields(): FieldCollection;
}
