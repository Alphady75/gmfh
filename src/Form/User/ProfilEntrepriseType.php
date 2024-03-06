<?php

namespace App\Form\User;

use App\Entity\User;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\NotBlank;
use Vich\UploaderBundle\Form\Type\VichImageType;

class ProfilEntrepriseType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('logo', VichImageType::class, [
                'label' => 'logo',
                'attr' => [
                    'placeholder' => 'logo',
                ],
                'constraints' => [
                    new NotBlank(),
                ]
            ])
            ->add('photo', VichImageType::class, [
                'label' => 'photo',
                'attr' => [
                    'placeholder' => 'photo',
                ],
                'constraints' => [
                    new NotBlank(),
                ]
            ])
            ->add('niu', TextType::class, [
                'label' => "Numéro d'identification unique (NIU)",
                'attr' => [
                    'placeholder' => "Numéro d'identification unique (NIU)",
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
            ->add('realisations', CollectionType::class, [
                'label' => "Galeries : Les réalisations",
                'help' => 'Facultatif',
                'attr' => [
                    'placeholder' => "Galeries : Les réalisations",
                ],
                'required' => false
            ])
            ->add('villes', CollectionType::class, [
                'label' => "Périmètre (Dans quelles villes)",
                'attr' => [
                    'placeholder' => "Périmètre (Dans quelles villes)",
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
            ->add('localisation', TextType::class, [
                'label' => "Adresse de l'entreprise",
                'attr' => [
                    'placeholder' => "Adresse de l'entreprise",
                ],
                'constraints' => [
                    new NotBlank(),
                ]
            ])
            ->add('nomResponsable', TextType::class, [
                'label' => "Nom du responsable",
                'attr' => [
                    'placeholder' => "Nom du responsable",
                ],
                'constraints' => [
                    new NotBlank(),
                ]
            ])
            ->add('siteWeb', TextType::class, [
                'label' => "Site web",
                'attr' => [
                    'placeholder' => "Site web",
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
