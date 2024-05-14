<?php

namespace App\Form\User;

use App\Entity\User;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\NotBlank;
use Vich\UploaderBundle\Form\Type\VichImageType;

class ProfilPersonnelType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('photoFile', VichImageType::class, [
                'label' => 'photo',
                'required'  =>  false,
                'allow_delete' =>  false,
                'download_label'     =>  false,
                'image_uri'     =>  false,
                'download_uri'     =>  false,
                'imagine_pattern'   =>  'largeavatar',
                'required' => false
            ])
            ->add('nom', TextType::class, [
                'label' => 'Nom',
                'attr' => [
                    'placeholder' => 'Entrez votre nom',
                ],
                'constraints' => [
                    new NotBlank(),
                ]
            ])
            ->add('prenom', TextType::class, [
                'label' => 'Prenom',
                'attr' => [
                    'placeholder' => 'Entrez votre prenom',
                    'class' => 'focus',
                ],
                'constraints' => [
                    new NotBlank(),
                ]
            ])
            ->add('localisation', TextType::class, [
                'label' => 'Ville de résidence',
                'help' => 'Facultatif',
                'attr' => [
                    'placeholder' => 'Ville de résidence',
                    'class' => 'location',
                ],
                'required' => false
            ])
            ->add('telephone', TextType::class, [
                'label' => 'Numéro de téléphone',
                'help' => 'Facultatif',
                'attr' => [
                    'placeholder' => 'Ex: 06 980 80 90',
                ],
                'required' => false
            ])
            ->add('apropo', TextareaType::class, [
                'label' => "Présentez-vous en quelques mots (Facultatif)",
                'attr' => [
                    'placeholder' => 'Présentez-vous en quelques mots textarea-style-1',
                    'class' => 'textarea-style-1 mce'
                ],
                'required' => false
            ])
            ->add('experiences', CollectionType::class, [
                'label' => "Parcours professionnel",
                'entry_type' => ExperienceType::class,
                'by_reference' => false,
                'allow_add' => true,
                'allow_delete' => true,
                'required' => false
            ])
            ->add('etudes', CollectionType::class, [
                'label' => "Etudes & Diplômes",
                'entry_type' => EtudeType::class,
                'by_reference' => false,
                'allow_add' => true,
                'allow_delete' => true,
                'required' => false
            ])
            /*->add('cvFile', FileType::class, [
                'label' => 'Votre CV',
                'required'  =>  false,
            ])*/;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => User::class,
            'step' => [],
        ]);
    }
}
