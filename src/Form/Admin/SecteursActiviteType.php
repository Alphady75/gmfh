<?php

namespace App\Form\Admin;

use App\Entity\SecteursActivite;
use App\Entity\SousSecteursActivite;
use Doctrine\ORM\EntityRepository;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\NotBlank;

class SecteursActiviteType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('name', TextType::class, [
                'label' => "Titre du secteur d'activité",
                'attr' => ['class' => 'focus', 'placeholder' => 'Ex: construction...'],
                'constraints' => [
                    new NotBlank()
                ]
            ])
            ->add('sousSecteursActivites', EntityType::class, [
                'label' => "Sous secteurs d'activité",
                'class' => SousSecteursActivite::class,
                'multiple' => true,
                'required' => false,
                'by_reference' => false,
                'query_builder' => function (EntityRepository $se) {
                    return $se->createQueryBuilder('s')->orderBy('s.name', 'ASC');
                },
                'attr' => [
                    'class' => 'js-select2-soussecteurs'
                ],
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => SecteursActivite::class,
        ]);
    }
}
