<?php

declare(strict_types=1);

namespace Framework\Form\Type;

use Framework\Database\Model\ModelInterface;
use Framework\Security\UserInterface;

final class EntityType extends AbstractType
{
    private array $optionValues = [];
    private ModelInterface $model;

    public function generateHtml(): string
    {
        return
            $this->generateOpenBlock().
            $this->getLabel().
            $this->getSelect().
            $this->getFirstError().
            $this->getHelpBlock().
            $this->generateCloseBlock()
        ;
    }

    public function getSelect(): string
    {
        $values = $this->getValue();
        $choiceLabel = $this->getOptions()['choice_label'];
        foreach ($values as $value) {
            $this->optionValues[] = $value->{'get'.ucfirst($choiceLabel)}();
        }
        preg_match('/[A-Za-z]*$/', $this->getOptions()['entity'], $matches);
        $modelGetter = $this->model->{'get'.$matches[0]}();

        $htmlOptions = '';
        foreach ($this->optionValues as $optionValue) {
            // Check the value of the current model
            if (null !== $modelGetter && $modelGetter->{'get'.ucfirst($choiceLabel)}() === $optionValue) {
                $htmlOptions .= '<option value="'.$optionValue.'" selected>'.$optionValue.'</option>';
            } else {
                $htmlOptions .= '<option value="'.$optionValue.'">'.$optionValue.'</option>';
            }
        }

        // TODO - check help, placeholder and require
        return '
            <select
                class="form-select"
                name="'.$this->getName().'"
                id="'.$this->getName().'"
                '.$this->getHelp().'
                '.$this->getPlaceholder().'
                '.$this->getRequired().'
            >
              '.$htmlOptions.'
            </select>
            '
        ;
    }

    public function setModel(ModelInterface $model): void
    {
        $this->model = $model;
    }
}
