<?php

namespace App\Form;

use App\Entity\User;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class UserType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('email')
            ->add('telephone')
            ->add('roles')
            ->add('password')
            ->add('isVerified')
            ->add('codeIsVerified')
            ->add('resetPasswordCode')
            ->add('compte')
            ->add('nom')
            ->add('prenom')
            ->add('logo')
            ->add('photo')
            ->add('niu')
            ->add('localisation')
            ->add('nomResponsable')
            ->add('siteWeb')
            ->add('cv')
            ->add('completed')
            ->add('apropo')
            ->add('nameSlug')
            ->add('societe')
            ->add('genre')
            ->add('qualification')
            ->add('anneeExperience')
            ->add('salaire')
            ->add('periodicite')
            ->add('devise')
            ->add('annuaire')
            ->add('created')
            ->add('updated')
            ->add('secteuractivite')
            ->add('soussecteuractivite')
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => User::class,
        ]);
    }
}
