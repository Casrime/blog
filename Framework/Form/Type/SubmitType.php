<?php

declare(strict_types=1);

namespace Framework\Form\Type;

final class SubmitType extends AbstractType
{
    public function generateHtml(): string
    {
        return
            $this->generateOpenBlock().
            $this->getInput().
            $this->generateCloseBlock()
        ;
    }

    private function getInput(): string
    {
        return '
            <input
                type="'.$this->getType().'"
                class="'.$this->getClass().'"
                value="'.$this->get('label').'"
            >'
        ;
    }

    private function getClass(): string
    {
        return $this->get('class') ?? 'btn btn-primary';
    }
}
