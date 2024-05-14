<?php

namespace App\Form\Base;

use App\Entity\Signaler;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\NotBlank;

class SignalerPostType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('abus', ChoiceType::class, [
                'label' => false,
                'choices' => [
                    'Annonce offensante ou discriminatoire' => 'Annonce offensante ou discriminatoire',
                    'Annonce potentiellement frauduleuse' => 'Annonce potentiellement frauduleuse',
                    'Annonce inexacte' => 'Offre inexacte',
                    "Il s'agit d'une publicité" => "Il s'agit d'une publicité",
                    'Autre' => 'Autre',
                ],
                'attr' => [
                    'class' => 'no-star',
                ],
                'constraints' => [
                    new NotBlank()
                ],
                'expanded' => true
            ])
            ->add('message', TextareaType::class, [
                'label' => "Informations complémentaires",
                'help' => '0 / 300',
                'attr' => [
                    'class' => 'textarea-style-1 focus',
                ],
                'constraints' => [
                    new NotBlank()
                ]
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Signaler::class,
        ]);
    }
}
