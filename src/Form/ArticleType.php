<?php

declare(strict_types=1);

namespace App\Form;

use Framework\Form\FormType;
use Framework\Form\Type\SubmitType;
use Framework\Form\Type\TextareaType;
use Framework\Form\Type\TextType;

final class ArticleType extends FormType
{
    public function buildForm(): void
    {
        $this->fieldCollection
            ->add('test')
            ->add('title', TextType::class, [
                'help' => 'Help me please',
                'label' => 'Titre',
                'placeholder' => 'Titre de votre article',
                'required' => true,
            ])
            ->add('empty', null, [
                'label' => 'i am empty',
            ])
            ->add('description', TextareaType::class, [
                'label' => 'description',
            ])
            ->add('submit', SubmitType::class, [
                'label' => 'Ajouter',
            ])
        ;
    }
}
