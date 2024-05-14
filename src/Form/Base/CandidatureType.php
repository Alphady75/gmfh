<?php

namespace App\Form\Base;

use App\Entity\Candidature;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\UX\Dropzone\Form\DropzoneType;

class CandidatureType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('candidatPresentation', TextareaType::class, [
                'label' => "Bref résumé de votre profil afin de vous présenter au recruteur",
                'attr' => [
                    'class' => 'large-area focus',
                    'placeholder' => 'Bref résumé de votre profil afin de vous présenter au recruteur',
                ],
                'constraints' => [
                    new NotBlank()
                ]
            ])
            ->add('cvFile', DropzoneType::class, [
                "label" => "Joindre votre cv",
                'attr' => [
                    'placeholder' => "Glissez deposez ou cliquez pour sélectionner votre cv",
                ],
                'constraints' => [
                    new NotBlank()
                ]
            ])
            ->add('lettreMotivationFile', DropzoneType::class, [
                "label" => "Joindre votre lettre de motivation",
                "help" => "Facultative pour certains postes, veuillez bien lire l'offre",
                'attr' => [
                    'placeholder' => "Glissez deposez ou cliquez pour sélectionner votre lettre de motivation",
                ],
                'required' => false
            ])
            ->add('entretien', ChoiceType::class, [
                'label' => "Quel est le type d'entretien qui vous convient le mieux ?",
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
            ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Candidature::class,
        ]);
    }
}
