<?php

declare(strict_types=1);

namespace Framework\Twig;

use Framework\Form\Form;
use Twig\TwigFunction;

final class FormEnd implements FormViewInterface
{
    public function renderView(): TwigFunction
    {
        return new TwigFunction('form_end', function (Form $form): void {
            $form->setContent('</form>');

            $form->createView();
        });
    }
}
