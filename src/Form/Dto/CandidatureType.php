<?php

namespace App\Form\Dto;

use App\Entity\Dto\Candidature;
use App\Entity\SecteursActivite;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class CandidatureType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('query', TextType::class, [
                'label' => false,
                'attr' => [
                    'placeholder' => "Que cherchez-vous ?"
                ],
                'required' => false,
            ])
            ->add('secteur', EntityType::class, [
                'label' => false,
                'placeholder'   =>  "Secteur d'activité...",
                'class' => SecteursActivite::class,
                'required' => false,
            ])->add('status', ChoiceType::class, [
                'label' => false,
                'placeholder'   =>  "Statut...",
                'choices' => [
                    'Actif' => 'Actif',
                    'Inactif' => 'Inactif'
                ],
                'required' => false,
                'expanded' => false
            ])->add('limit', ChoiceType::class, [
                'label' => false,
                'placeholder'   =>  "Affichage...",
                'choices' => [
                    5 => 5,
                    10 => 10,
                    100 => 100,
                ],
                'required' => false,
                'expanded' => false
            ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Candidature::class,
            'method' => 'GET',
            'csrf_protection' => false,
        ]);
    }
}
