<?php

namespace App\Form;

use App\Entity\Candidature;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class CandidatureType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('cv')
            ->add('statut')
            ->add('etat')
            ->add('token')
            ->add('candidatPresentation')
            ->add('status')
            ->add('entretien')
            ->add('lettreMotivation')
            ->add('dateEntretien')
            ->add('dateSelection')
            ->add('dateTrie')
            ->add('entretienMessage')
            ->add('evaluationMessage')
            ->add('dateEvaluation')
            ->add('selectionMessage')
            ->add('dateRejet')
            ->add('rejetMessage')
            ->add('acceptationDate')
            ->add('acceptationMessage')
            ->add('statusColor')
            ->add('created')
            ->add('updated')
            ->add('user')
            ->add('offre')
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Candidature::class,
        ]);
    }
}
