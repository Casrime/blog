<?php

declare(strict_types=1);

namespace App\Form;

use Framework\Form\FormType;
use Framework\Form\Type\EmailType;
use Framework\Form\Type\PasswordType;

final class UserType extends FormType
{
    public function buildForm(): void
    {
        $this->fieldCollection
            ->add('email', EmailType::class, [
                'label' => 'Email',
                'placeholder' => 'Votre email',
                'required' => true,
            ])
            ->add('password', PasswordType::class, [
                'label' => 'Mot de passe',
                'placeholder' => 'Votre mot de passe',
                'required' => true,
            ])
        ;
    }
}
