<?php

declare(strict_types=1);

namespace App\Form;

use Framework\Form\FormType;
use Framework\Form\Type\TextareaType;
use Framework\Form\Type\TextType;
use Framework\Validator\Constraints\Length;
use Framework\Validator\Constraints\NotBlank;

final class ArticleType extends FormType
{
    public function buildForm(): void
    {
        $this->fieldCollection
            ->add('title', TextType::class, [
                'constraints' => [
                    new NotBlank(),
                    new Length(['min' => 3, 'max' => 255]),
                ],
                'label' => 'Titre',
                'required' => true,
            ])
            ->add('chapo', TextType::class, [
                'constraints' => [
                    new NotBlank(),
                    new Length(['min' => 3, 'max' => 255]),
                ],
                'label' => 'Chapô',
                'required' => true,
            ])
            ->add('content', TextareaType::class, [
                'constraints' => [
                    new NotBlank(),
                ],
                'label' => 'Contenu',
                'required' => true,
            ])
        ;
    }
}
