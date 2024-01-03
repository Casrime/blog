<?php

declare(strict_types=1);

namespace App\Form;

use App\Model\User;
use Framework\Form\FormType;
use Framework\Form\Type\EmailType;
use Framework\Form\Type\PasswordType;
use Framework\Security\Security;
use Framework\Validator\Constraints\Email;
use Framework\Validator\Constraints\Length;
use Framework\Validator\Constraints\NotBlank;
use Framework\Validator\Constraints\Password;
use Framework\Validator\Constraints\UniqueEntity;

final class RegisterType extends FormType
{
    public function __construct(private readonly Security $security)
    {
        parent::__construct();
    }

    public function buildForm(): void
    {
        $this->fieldCollection
            ->add('email', EmailType::class, [
                'constraints' => [
                    new NotBlank(),
                    new Length(['max' => 255]),
                    new Email(),
                    new UniqueEntity(User::class, $this->security),
                ],
                'label' => 'Email',
                'placeholder' => 'Votre email',
                'required' => true,
            ])
            ->add('password', PasswordType::class, [
                'constraints' => [
                    new NotBlank(),
                    new Length(['min' => 8, 'max' => 255]),
                    new Password(),
                ],
                'label' => 'Mot de passe',
                'placeholder' => 'Votre mot de passe',
                'required' => true,
            ])
        ;
    }
}
