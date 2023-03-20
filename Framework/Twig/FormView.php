<?php

declare(strict_types=1);

namespace Framework\Twig;

use Framework\Form\Form;
use Framework\Form\Type\AbstractType;
use Twig\TwigFunction;

final class FormView implements FormViewInterface
{
    public function renderView(): TwigFunction
    {
        return new TwigFunction('form', function (Form $form, array $options = []): void {
            // TODO - replace this by $form->generateHtml();
            // TODO - create an interface for generateHtml method ?
            $fieldContent = '';

            /** @var AbstractType $field */
            foreach ($form->getFieldCollection()->all() as $field) {
                $fieldContent .= $field->generateHtml();
            }

            // TODO - handle form action and method
            $form->setContent('<form method="POST">'.$fieldContent.'</form>');

            $form->createView();
        });
    }
}
