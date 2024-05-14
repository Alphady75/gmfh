<?php

namespace App\Form\Admin;

use App\Entity\User;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\Email;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\NotBlank;

class UserType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
        ->add('nom', TextType::class, [
            'label' => 'Nom(s)',
            'attr' => [
                'placeholder' => 'Nom(s)',
            ],
            'constraints' => [
                new NotBlank(),
            ]
        ])
        ->add('prenom', TextType::class, [
            'label' => 'Prénom(s)',
            'attr' => [
                'placeholder' => 'Prénom(s)',
            ],
            'constraints' => [
                new NotBlank(),
            ]
        ])
        ->add('email', EmailType::class, [
            'label' => "Email",
            'attr' => [
                'placeholder' => 'Email (Ex: email@gmail.com)'
            ],
            'constraints' => [
                new NotBlank(),
                new Email()
            ]
        ])
        ->add('compte', ChoiceType::class, [
            'placeholder' => '--Sélectionnez le type de compte--',
            'choices' => [
                'PERSONNEL / CLIENT' => 'PERSONNEL',
                'PARTICULIER' => 'PARTICULIER',
                'ADMINISTRATEUR' => 'ADMINISTRATEUR',
            ],
            'label' => 'Compte',
            'constraints' => [
                new NotBlank(),
            ],
        ])
        ->add('annuaire', CheckboxType::class, [
            'label' => "Visible sur la page annuaires",
            'help' => "Si vous cocher cette case le profil de l'utilisateur sera visible sur la page annuaire",
            'required' => false
        ])
        ->add('plainPassword', PasswordType::class, [
            // instead of being set onto the object directly,
            // this is read and encoded in the controller
            'label' => "Mot de passe",
            'mapped' => false,
            'attr' => ['autocomplete' => 'new-password', 'placeholder' => 'Mot de passe', 'class' => 'focus'],
            'constraints' => [
                new NotBlank([
                    'message' => 'Veuillez entrer un mot de passe',
                ]),
                new Length([
                    'min' => 6,
                    'minMessage' => 'Le mot de passe doit comporter au moins {{ limit }} caractères',
                    // max length allowed by Symfony for security reasons
                    'max' => 4096,
                ]),
            ],
        ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => User::class,
        ]);
    }
}
