<?php

namespace App\Form\User;

use App\Entity\Etude;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\NotBlank;

class EtudeType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('annee', TextType::class, [
                'label' => 'Année accademique',
                'attr' => ['placeholder' => 'Ex: 2023 à 2024'],
                'constraints' => [
                    new NotBlank()
                ]
            ])
            ->add('ecole', TextType::class, [
                'label' => 'Ecole',
                'attr' => ['placeholder' => 'Ex: Université de Paris'],
                'constraints' => [
                    new NotBlank()
                ]
            ])
            ->add('diplome', TextType::class, [
                'label' => 'Diplôme',
                'attr' => ['placeholder' => 'Ex: License en Génie Civile'],
                'constraints' => [
                    new NotBlank()
                ]
            ])
            ->add('description', TextareaType::class, [
                'label' => 'Description',
                'attr' => ['placeholder' => 'Ex: License en Génie Civile', 'class' => 'textarea-style-1'],
                'constraints' => [
                    new NotBlank()
                ]
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Etude::class,
        ]);
    }
}
