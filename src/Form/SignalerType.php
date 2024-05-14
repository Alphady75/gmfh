<?php

namespace App\Form;

use App\Entity\Signaler;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class SignalerType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('abus')
            ->add('message')
            ->add('created')
            ->add('updated')
            ->add('user')
            ->add('offre')
            ->add('post')
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Signaler::class,
        ]);
    }
}
