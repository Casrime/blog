<?php

declare(strict_types=1);

namespace Framework\Form\Type;

final class ButtonType extends AbstractType
{
    public function generateHtml(): string
    {
        return $this->generateOpenBlock().'
            <label for="'.$this->getName().'" class="form-label">'.$this->get('label').'</label>
            <input type="'.$this->getType().'" class="form-control" id="'.$this->getName().'" aria-describedby="'.$this->getName().'Help">
            <div id="'.$this->getName().'Help" class="form-text">Help text</div>
            <input type="submit" class="btn btn-primary" value="Submit">
        '.$this->generateCloseBlock()
        ;
    }
}
