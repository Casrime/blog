<?php

declare(strict_types=1);

namespace App\Form;

use Framework\Form\FormType;
use Framework\Form\Type\EmailType;
use Framework\Form\Type\PasswordType;
use Framework\Form\Type\SubmitType;

final class RegisterType extends FormType
{
    public function buildForm(): void
    {
        $this->fieldCollection
            ->add('email', EmailType::class, [
                'label' => 'Email',
                'placeholder' => 'Votre email',
                'required' => true,
            ])
            // TODO - replace this by a RepeatedType ?
            ->add('password', PasswordType::class, [
                'label' => 'Mot de passe',
                'placeholder' => 'Votre mot de passe',
                'required' => true,
            ])
            ->add('submit', SubmitType::class, [
                'label' => 'Inscription',
            ])
        ;
    }
}
