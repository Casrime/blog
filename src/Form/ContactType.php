<?php

declare(strict_types=1);

namespace App\Form;

use Framework\Form\FormType;
use Framework\Form\Type\EmailType;
use Framework\Form\Type\TextareaType;
use Framework\Form\Type\InputType;
use Framework\Validator\Constraints\Email;
use Framework\Validator\Constraints\Length;
use Framework\Validator\Constraints\NotBlank;

final class ContactType extends FormType
{
    public function buildForm(): void
    {
        $this->fieldCollection
            ->add('lastname', InputType::class, [
                'constraints' => [
                    new NotBlank(),
                    new Length(['min' => 3, 'max' => 255]),
                ],
                'label' => 'Nom',
                'placeholder' => 'Votre nom',
                'required' => true,
            ])
            ->add('firstname', InputType::class, [
                'constraints' => [
                    new NotBlank(),
                    new Length(['min' => 3, 'max' => 255]),
                ],
                'label' => 'Prénom',
                'placeholder' => 'Votre prénom',
                'required' => true,
            ])
            ->add('email', EmailType::class, [
                'constraints' => [
                    new NotBlank(),
                    new Email(),
                    new Length(['min' => 3, 'max' => 255]),
                ],
                'label' => 'Email',
                'placeholder' => 'Votre email',
                'required' => true,
            ])
            ->add('message', TextareaType::class, [
                'constraints' => [
                    new NotBlank(),
                ],
                'label' => 'Message',
                'placeholder' => 'Votre message',
                'required' => true,
            ])
        ;
    }
}
