<?php

declare(strict_types=1);

namespace Framework\Form\Type;

// TODO - delete this type if it is not used
final class PasswordType extends AbstractType
{
    public function generateHtml(): string
    {
        return
            $this->generateOpenBlock().
            $this->getLabel().
            $this->getInput().
            $this->getHelpBlock().
            $this->generateCloseBlock()
            ;
    }

    public function getInput(): string
    {
        return '
            <input
                type="'.$this->getType().'"
                class="'.$this->getClass().'"
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
