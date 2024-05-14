<?php

namespace App\Form\Base;

use App\Entity\Alert;
use App\Entity\SecteursActivite;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\NotBlank;

class AlertType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('query', TextType::class, [
                'label' => "Intitulé du poste",
                'attr' => [
                    'placeholder' => 'Ex: Développement de Logiciels',
                    'class' => 'focus'
                ],
                'constraints' => [
                    new NotBlank()
                ]
            ])
            ->add('localisation', TextType::class, [
                'attr' => [
                    'placeholder' => 'Ex: Paris'
                ],
                'constraints' => [
                    new NotBlank()
                ]
            ])
            ->add('secteurs', EntityType::class, [
                'label' => "Secteurs d'activité",
                'class' => SecteursActivite::class,
                'multiple' => true,
                'expanded' => true,
                'attr' => [
                    'class' => 'select2'
                ],
                'constraints' => [
                    new NotBlank()
                ]
            ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Alert::class,
        ]);
    }
}
