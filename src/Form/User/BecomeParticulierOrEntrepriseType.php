<?php

namespace App\Form\User;

use App\Entity\User;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\IsTrue;
use Symfony\Component\Validator\Constraints\NotBlank;

class BecomeParticulierOrEntrepriseType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('compte', ChoiceType::class, [
                'label' => false,
                'attr' => ['class' => 'statut'],
                'choices' => [
                    'PARTICULIER' => 'PARTICULIER',
                    'ENTREPRISE' => 'ENTREPRISE'
                ],
                'constraints' => [
                    new NotBlank()
                ]
            ])
            ->add('raisons', TextareaType::class, [
                'label' => false,
                'mapped' => false,
                'attr' => [
                    'placeholder' => 'Evoquez quelques raisons',
                    'class' => 'focus textarea-style-1',
                ],
                'constraints' => [
                    new NotBlank()
                ]
            ])
            ->add('agreeTerms', CheckboxType::class, [
                'mapped' => false,
                'required' => false,
                'label' => false,
                'constraints' => [
                    new IsTrue([
                        'message' => 'Vous devez accepter nos conditions.',
                    ]),
                ],
            ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => User::class,
        ]);
    }
}
