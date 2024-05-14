<?php

namespace App\Form\Admin;

use App\Entity\Composants;
use App\Entity\Stripe;
use Doctrine\ORM\EntityRepository;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\NotBlank;

class StripeType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('name', TextType::class, [
                'label' => "Titre du produit",
                'attr' => [
                    'placeholder' => 'Ex: offre basique'
                ],
                'constraints' => [
                    new NotBlank()
                ]
            ])
            ->add('tarif', IntegerType::class, [
                'constraints' => [
                    new NotBlank()
                ],
                'attr' => [
                    'placeholder' => 'Ex: 90'
                ],
            ])->add('devise', ChoiceType::class, [
                'label' => "Devise (monnaie)",
                'help' => "Devise (monnaie)",
                'placeholder' => "Sélectionnez une devise...",
                'choices' => [
                    '$' => '$',
                    '€' => '€',
                    'FCFA' => 'FCFA',
                ],
                'required' => false
            ])
            ->add('typeTarification', ChoiceType::class, [
                'label' => "Type de tarification",
                'choices' => [
                    'Mensuelle' => 'mois',
                    'Annuelle' => 'ans',
                ],
                'attr' => [
                    'class' => 'focus',
                    'placeholder' => "Copier et coller l'ID de ce produit Stripe"
                ]
            ])
            ->add('stripeKey', TextType::class, [
                'label' => "Clé du produit stripe",
                'help' => "Copier et coller l'ID de ce produit Stripe",
                'attr' => [
                    'class' => 'focus',
                    'placeholder' => "Copier et coller l'ID de ce produit Stripe"
                ]
            ])
            ->add('composants', EntityType::class, [
                'label' => "Ajouter des composants à votre abonnement",
                'class' => Composants::class,
                'multiple' => true,
                'required' => false,
                'by_reference' => false,
                'query_builder' => function (EntityRepository $co) {
                    return $co->createQueryBuilder('c')->orderBy('c.name', 'ASC');
                },
                'attr' => [
                    'class' => 'js-select2-composants'
                ],
            ])
            ->add('recommanded', CheckboxType::class, [
                'label' => "Recommander ce plan d'abonnement aux utilisateurs",
                'help' => "Cocher si vous souhaitez recommander cette offres aux utilisateurs",
                'required' => false,
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Stripe::class,
        ]);
    }
}
