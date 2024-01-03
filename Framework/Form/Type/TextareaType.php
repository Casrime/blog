<?php

declare(strict_types=1);

namespace Framework\Form\Type;

final class TextareaType extends InputType
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
        return '
            <textarea
                class="'.$this->getHTMLClass().'"
                id="'.$this->getName().'"
                name="'.$this->getName().'"
                '.$this->getHelp().'
                '.$this->getPlaceholder().'
                '.$this->getRequired().'
            >'.$this->getValue().'</textarea>'
        ;
    }
}
