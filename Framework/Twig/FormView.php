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
        return new TwigFunction('form', function (Form $form): void {
            $fieldContent = '';
            /** @var AbstractType $field */
            foreach ($form->getFieldCollection()->all() as $field) {
                $fieldContent .= $field->generateHtml();
            }
            $form->setContent('<form method="'.$form->getMethod().'">'.$fieldContent.'</form>');

            $form->createView();
        });
    }
}
