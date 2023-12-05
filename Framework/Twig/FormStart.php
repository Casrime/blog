<?php

declare(strict_types=1);

namespace Framework\Twig;

use Framework\Form\Form;
use Twig\TwigFunction;

final class FormStart implements FormViewInterface
{
    public function renderView(): TwigFunction
    {
        return new TwigFunction('form_start', function (Form $form, array $options = []): void {
            if (isset($options['action'])) {
                $form->setAction($options['action']);
            }

            if (isset($options['method'])) {
                $form->setMethod($options['method']);
            }

            if (null !== $form->getAction()) {
                $form->setAction('action="'.$form->getAction().'"');
            } else {
                $form->setAction(null);
            }

            $form->setContent('<form '.$form->getAction().' method="'.$form->getMethod().'">');

            $form->createView();
        });
    }
}
