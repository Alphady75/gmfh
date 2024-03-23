<?php

namespace App\Form;

use App\Entity\Post;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class PostType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('name')
            ->add('slug')
            ->add('image')
            ->add('tarif')
            ->add('description')
            ->add('online')
            ->add('etat')
            ->add('negociable')
            ->add('livraison')
            ->add('promo')
            ->add('vedette')
            ->add('statut')
            ->add('tarifPromo')
            ->add('boosted')
            ->add('devise')
            ->add('urgent')
            ->add('isSelled')
            ->add('sellPlateform')
            ->add('created')
            ->add('updated')
            ->add('user')
            ->add('souscategorie')
            ->add('categorie')
            ->add('booster')
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Post::class,
        ]);
    }
}
