<?php

namespace App\Form\User;

use App\Entity\User;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\NotBlank;

class ProfilParticulierType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('nom', TextType::class, [
                'label' => 'Entrez votre nom',
                'attr' => [
                    'placeholder' => 'Entrez votre nom',
                ],
                'constraints' => [
                    new NotBlank(),
                ]
            ])
            ->add('prenom', TextType::class, [
                'label' => 'Entrez votre prenom',
                'attr' => [
                    'placeholder' => 'Entrez votre prenom',
                ],
                'constraints' => [
                    new NotBlank(),
                ]
            ])
            ->add('villeResidence', TextType::class, [
                'label' => 'Ville de résidence',
                'help' => 'Facultatif',
                'attr' => [
                    'placeholder' => 'Ville de résidence',
                ],
                'constraints' => [
                    new NotBlank(),
                ]
            ])
            ->add('telephone', TextType::class, [
                'label' => 'Numéro de téléphone',
                'help' => 'Facultatif',
                'attr' => [
                    'placeholder' => 'Ex: 06 980 80 90',
                ],
                'required' => false
            ])
            ->add('competences', CollectionType::class, [
                'label' => "competences",
                'attr' => [
                    'placeholder' => "competences",
                ],
                'constraints' => [
                    new NotBlank(),
                ]
            ])
            ->add('services', CollectionType::class, [
                'label' => "services",
                'attr' => [
                    'placeholder' => "services",
                ],
                'constraints' => [
                    new NotBlank(),
                ]
            ])
            ->add('villes', CollectionType::class, [
                'label' => "Périmètre (Dans quelles villes).",
                'attr' => [
                    'placeholder' => "Périmètre (Dans quelles villes).",
                ],
                'constraints' => [
                    new NotBlank(),
                ]
            ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => User::class,
        ]);
    }
}
