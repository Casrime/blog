<?php

declare(strict_types=1);

namespace App\Form;

use Framework\Form\FormType;
use Framework\Form\Type\FileType;
use Framework\Form\Type\TextareaType;
use Framework\Form\Type\TextType;
use Framework\Form\Type\UrlType;

final class ProfileType extends FormType
{
    public function buildForm(): void
    {
        $this->fieldCollection
            ->add('lastname', TextType::class, [
                'label' => 'Nom',
            ])
            ->add('firstname', TextType::class, [
                'label' => 'Prénom',
            ])
            ->add('logo', FileType::class, [
                'label' => 'Logo',
            ])
            ->add('phrase', TextareaType::class, [
                'label' => 'Phrase d\'accroche',
            ])
            ->add('cv', UrlType::class, [
                'label' => 'CV',
            ])
            ->add('github', UrlType::class, [
                'label' => 'GitHub',
            ])
            ->add('linkedin', UrlType::class, [
                'label' => 'LinkedIn',
            ])
            ->add('twitter', UrlType::class, [
                'label' => 'Twitter',
            ])
        ;
    }
}
