<?php

namespace App\Form\Candidature;

use App\Entity\Candidature;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\UX\Dropzone\Form\DropzoneType;

class PostulerType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
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
            ->add('candidatPresentation', TextareaType::class, [
                'label' => "Bref résumé de votre profil afin de vous présenter au recruteur",
                'help' => "0/500 (Ce message sera envoyer au recruteur après la soumission)",
                'attr' => [
                    'class' => "focus textarea-style-1",
                    'placeholder' => 'Bref résumé de votre profil afin de vous présenter au recruteur',
                ],
                'constraints' => [
                    new NotBlank()
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
