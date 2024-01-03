<?php

declare(strict_types=1);

namespace Framework\Form\Type;

class InputType extends AbstractType
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
        return '
            <input
                type="'.$this->getType().'"
                class="'.$this->getHTMLClass().'"
                id="'.$this->getName().'"
                name="'.$this->getName().'"
                value="'.$this->getValue().'"
                '.$this->getHelp().'
                '.$this->getPlaceholder().'
                '.$this->getRequired().'
            >'
        ;
    }

    protected function getClass(): string
    {
        return $this->get('class') ?? 'form-control';
    }

    protected function getHTMLClass(): string
    {
        if (0 < count($this->getErrors())) {
            return $this->getClass().' is-invalid';
        }

        return $this->getClass();
    }

    public function getType(): string
    {
        return 'text';
    }
}
