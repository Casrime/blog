<?php

declare(strict_types=1);

namespace Framework\Form\Type;

final class TextType extends AbstractType
{
    public function generateHtml(): string
    {
        return
            $this->generateOpenBlock().
            $this->getLabel().
            $this->getInput().
            $this->getFirstError().
            $this->getHelpBlock().
            $this->generateCloseBlock()
        ;
    }

    public function getInput(): string
    {
        // TODO - duplicate code here
        if (0 < count($this->getErrors())) {
            $class = $this->getClass().' is-invalid';
        } else {
            $class = $this->getClass();
        }

        return '
            <input
                type="'.$this->getType().'"
                class="'.$class.'"
                id="'.$this->getName().'"
                name="'.$this->getName().'"
                value="'.$this->getValue().'"
                '.$this->getHelp().'
                '.$this->getPlaceholder().'
                '.$this->getRequired().'
            >'
        ;
    }

    private function getClass(): string
    {
        return $this->get('class') ?? 'form-control';
    }
}
