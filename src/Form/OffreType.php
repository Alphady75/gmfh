<?php

namespace App\Form;

use App\Entity\Offre;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class OffreType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('name')
            ->add('slug')
            ->add('IntitulePoste')
            ->add('description')
            ->add('localisation')
            ->add('dateDebut')
            ->add('infosContrat')
            ->add('effectifRecrutement')
            ->add('lieuTravail')
            ->add('langues')
            ->add('typeContrat')
            ->add('complet')
            ->add('boosted')
            ->add('status')
            ->add('anneeExperience')
            ->add('salaire')
            ->add('qualification')
            ->add('periodicite')
            ->add('dateFin')
            ->add('devise')
            ->add('created')
            ->add('updated')
            ->add('user')
            ->add('secteuractivite')
            ->add('soussecteuractivite')
            ->add('booster')
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Offre::class,
        ]);
    }
}
