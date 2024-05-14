<?php

namespace App\Form\Admin;

use App\Entity\Categorie;
use App\Entity\SousCategorie;
use Doctrine\ORM\EntityRepository;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\NotBlank;

class CategorieType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('name', TextType::class, [
                'label' => 'Titre',
                'constraints' => [
                    new NotBlank()
                ],
                'attr' => [
                    'placeholder' => 'Ex: vêtements...',
                    'class' => 'focus',
                ]
            ])
            ->add('souscategories', EntityType::class, [
                'label' => "Sous catégories",
                'class' => SousCategorie::class,
                'multiple' => true,
                'required' => false,
                'by_reference' => false,
                'query_builder' => function (EntityRepository $se) {
                    return $se->createQueryBuilder('s')->orderBy('s.name', 'ASC');
                },
                'attr' => [
                    'class' => 'js-select2-souscategories'
                ],
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Categorie::class,
        ]);
    }
}
