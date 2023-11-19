<?php

declare(strict_types=1);

namespace App\Form;

use Framework\Form\FormType;
use Framework\Form\Type\EmailType;
use Framework\Form\Type\PasswordType;
use Framework\Validator\Constraints\Email;
use Framework\Validator\Constraints\Length;
use Framework\Validator\Constraints\NotBlank;

final class LoginType extends FormType
{
    public function buildForm(): void
    {
        $this->fieldCollection
            ->add('email', EmailType::class, [
                'constraints' => [
                    new NotBlank(),
                    new Length(['max' => 255]),
                    new Email(),
                ],
                'label' => 'Email',
                'placeholder' => 'Votre email',
                'required' => true,
            ])
            ->add('password', PasswordType::class, [
                'constraints' => [
                    new NotBlank(),
                    new Length(['min' => 8, 'max' => 255]),
                ],
                'label' => 'Mot de passe',
                'placeholder' => 'Votre mot de passe',
                'required' => true,
            ])
        ;
    }
}
