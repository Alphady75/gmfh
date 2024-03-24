<?php

namespace App\Form\User;

use App\Entity\Competence;
use App\Entity\Service;
use App\Entity\User;
use App\Entity\Ville;
use Doctrine\ORM\EntityRepository;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\NotBlank;
use Vich\UploaderBundle\Form\Type\VichImageType;

class ProfilParticulierType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $step = $options['step'];

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
                ],
                'constraints' => [
                    new NotBlank(),
                ]
            ])
            ->add('localisation', TextType::class, [
                'label' => 'Ville de résidence',
                'attr' => [
                    'placeholder' => 'Ville de résidence',
                    'class' => 'focus',
                ],
                'constraints' => [
                    new NotBlank(),
                ]
            ])
            ->add('telephone', TextType::class, [
                'label' => 'Numéro de téléphone',
                'help' => 'Facultatif',
                'attr' => [
                    'placeholder' => 'Ex: 06 980 80 90',
                ],
                'required' => false
            ])
            ->add('competences', EntityType::class, [
                'label' => "Vos competences (Facultatif)",
                'class' => Competence::class,
                'multiple' => true,
                'required' => false,
                'by_reference' => false,
                'query_builder' => function(EntityRepository $er){
                    return $er->createQueryBuilder('c')->orderBy('c.name', 'ASC');
                },
                'attr' => [
                    'class' => 'js-select2-competences'
                ],
            ])
            ->add('services', EntityType::class, [
                'label' => "Services que vous souhaitez offrir",
                'class' => Service::class,
                'multiple' => true,
                'by_reference' => false,
                'query_builder' => function(EntityRepository $se){
                    return $se->createQueryBuilder('s')->orderBy('s.name', 'ASC');
                },
                'attr' => [
                    'class' => 'js-select2-services'
                ],
                'constraints' => [
                    new NotBlank(),
                ]
            ])
            ->add('villes', EntityType::class, [
                'label' => "Périmètre (Dans quelles villes)",
                'class' => Ville::class,
                'multiple' => true,
                'required' => false,
                'by_reference' => false,
                'query_builder' => function(EntityRepository $vil){
                    return $vil->createQueryBuilder('v')->orderBy('v.name', 'ASC');
                },
                'attr' => [
                    'class' => 'js-select2-villes'
                ],
            ])
            ->add('apropo', TextareaType::class, [
                'label' => "Présentez-vous en quelques mots (Facultatif)",
                'attr' => [
                    'placeholder' => 'Présentez-vous en quelques mots',
                    'class' => 'textarea-style-1'
                ],
                'required' => false
            ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => User::class,
            'step' => [],
        ]);
    }
}
