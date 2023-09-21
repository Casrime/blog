<?php

declare(strict_types=1);

namespace App\Form;

use Framework\Form\FormType;
use Framework\Form\Type\TextareaType;

final class CommentType extends FormType
{
    public function buildForm(): void
    {
        $this->fieldCollection
            ->add('comment', TextareaType::class, [
                'label' => 'Commentaire',
                'placeholder' => 'Votre commentaire',
                'required' => true,
            ])
        ;
    }
}
