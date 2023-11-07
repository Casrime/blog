<?php

declare(strict_types=1);

namespace Framework\Form\Type;

final class TextareaType extends AbstractType
{
    public function generateHtml(): string
    {
        return
            $this->generateOpenBlock().
            $this->getLabel().
            $this->getTextarea().
            $this->getFirstError().
            $this->getHelpBlock().
            $this->generateCloseBlock()
        ;
    }

    public function getTextarea(): string
    {
        // TODO - duplicate code here
        if (0 < count($this->getErrors())) {
            $class = $this->getClass().' is-invalid';
        } else {
            $class = $this->getClass();
        }

        return '
            <textarea
                class="'.$class.'"
                id="'.$this->getName().'"
                name="'.$this->getName().'"
                '.$this->getHelp().'
                '.$this->getPlaceholder().'
                '.$this->getRequired().'
            >'.$this->getValue().'</textarea>'
        ;
    }

    private function getClass(): string
    {
        return $this->get('class') ?? 'form-control';
    }
}
