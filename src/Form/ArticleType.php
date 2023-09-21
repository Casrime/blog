<?php

declare(strict_types=1);

namespace App\Form;

use Framework\Form\FormType;
use Framework\Form\Type\TextareaType;
use Framework\Form\Type\TextType;

final class ArticleType extends FormType
{
    public function buildForm(): void
    {
        $this->fieldCollection
            ->add('title', TextType::class, [
                'label' => 'Titre',
            ])
            ->add('chapo', TextType::class, [
                'label' => 'Chapô',
            ])
            ->add('author', TextType::class, [
                'label' => 'Auteur',
            ])
            ->add('content', TextareaType::class, [
                'label' => 'Contenu',
            ])
        ;
    }
}
