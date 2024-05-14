<?php

namespace App\Form\Candidature;

use App\Entity\Candidature;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\DateTimeType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\NotBlank;

class EntretienType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('entretien', ChoiceType::class, [
                'label' => "Type d'entretien",
                'help' => "Indisponsable si vou êtes retenu pour le poste",
                'choices' => [
                    'Entretiens téléphoniques' => 'Téléphoniques',
                    'Entretiens en personne' => 'En personne',
                    'Entretiens par vidéo conférence' => 'Vidéo conférence',
                    'Autres' => 'Autre',
                ],
                'constraints' => [
                    new NotBlank(),
                ]
            ])
            ->add('dateEntretien', DateTimeType::class, [
                'label' => "Date et l'heure prévu pour l'entretien",
                'widget' => 'single_text',
                'constraints' => [
                    new NotBlank()
                ],
            ])->add('entretienMessage', TextareaType::class, [
                'label' => "Message d'invitation du candidat à l'entretien",
                'attr' => [
                    'placeholder' => "Message d'invitation du candidat à l'entretien",
                    'class' => "focus textarea-style-1"
                ],
                'constraints' => [
                    new NotBlank()
                ],
            ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Candidature::class,
        ]);
    }
}
