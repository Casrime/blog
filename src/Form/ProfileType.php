<?php

declare(strict_types=1);

namespace App\Form;

use Framework\Form\FormType;
use Framework\Form\Type\FileType;
use Framework\Form\Type\TextareaType;
use Framework\Form\Type\TextType;
use Framework\Form\Type\UrlType;
use Framework\Validator\Constraints\Email;
use Framework\Validator\Constraints\Length;
use Framework\Validator\Constraints\NotBlank;

final class ProfileType extends FormType
{
    public function buildForm(): void
    {
        $this->fieldCollection
            ->add('lastname', TextType::class, [
                'constraints' => [
                    new NotBlank(),
                    new Length(['min' => 3, 'max' => 255]),
                ],
                'label' => 'Nom',
                'required' => true,
            ])
            ->add('firstname', TextType::class, [
                'constraints' => [
                    new NotBlank(),
                    new Length(['min' => 3, 'max' => 255]),
                ],
                'label' => 'Prénom',
                'required' => true,
            ])
            ->add('logo', FileType::class, [
                'label' => 'Logo',
            ])
            ->add('phrase', TextareaType::class, [
                'constraints' => [
                    new NotBlank(),
                ],
                'label' => 'Phrase d\'accroche',
                'required' => true,
            ])
            ->add('cv', UrlType::class, [
                'constraints' => [
                    new NotBlank(),
                ],
                'label' => 'CV',
                'required' => true,
            ])
            ->add('github', UrlType::class, [
                'constraints' => [
                    new NotBlank(),
                ],
                'label' => 'GitHub',
                'required' => true,
            ])
            ->add('linkedin', UrlType::class, [
                'constraints' => [
                    new NotBlank(),
                ],
                'label' => 'LinkedIn',
                'required' => true,
            ])
            ->add('twitter', UrlType::class, [
                'constraints' => [
                    new NotBlank(),
                ],
                'label' => 'Twitter',
            ])
        ;
    }
}
