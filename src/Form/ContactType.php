<?php

declare(strict_types=1);

namespace App\Form;

use Framework\Form\FormType;
use Framework\Form\Type\EmailType;
use Framework\Form\Type\TextareaType;
use Framework\Form\Type\TextType;

final class ContactType extends FormType
{
    public function buildForm(): void
    {
        $this->fieldCollection
            ->add('lastname', TextType::class, [
                'label' => 'Nom',
                'placeholder' => 'Votre nom',
                'required' => true,
            ])
            ->add('firstname', TextType::class, [
                'label' => 'Prénom',
                'placeholder' => 'Votre prénom',
                'required' => true,
            ])
            ->add('email', EmailType::class, [
                'label' => 'Email',
                'placeholder' => 'Votre email',
                'required' => true,
            ])
            ->add('message', TextareaType::class, [
                'label' => 'Message',
                'placeholder' => 'Votre message',
                'required' => true,
            ])
        ;
    }
}
