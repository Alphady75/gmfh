<?php

namespace App\Form\User;

use App\Entity\Experience;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\NotBlank;

class ExperienceType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('startAt', DateType::class, [
                'label' => 'Du',
                'widget' => 'single_text',
                'attr' => ['placeholder' => 'Date de début'],
                'help' => "Date de début",
                'constraints' => [
                    new NotBlank()
                ]
            ])
            ->add('endAt', DateType::class, [
                'label' => 'Au',
                'widget' => 'single_text',
                'attr' => ['placeholder' => 'Date de fin'],
                'help' => "Date de fin",
                'constraints' => [
                    new NotBlank()
                ]
            ])
            ->add('entreprise', TextType::class, [
                'help' => "Nom de l'entreprise/structure",
                'attr' => [
                    'placeholder' => 'Ex: Gamfah compagnie'
                ],
                'constraints' => [
                    new NotBlank()
                ]
            ])
            ->add('poste', TextType::class, [
                'attr' => [
                    'placeholder' => 'Ex: Développeur Web'
                ],
                'constraints' => [
                    new NotBlank()
                ]
            ])
            ->add('description', TextareaType::class, [
                'label' => "Description du poste, missions...",
                'attr' => [
                    'placeholder' => 'Ex: Chef de projet', 'class' => 'textarea-style-1'
                ],
                'constraints' => [
                    new NotBlank()
                ]
            ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Experience::class,
        ]);
    }
}
