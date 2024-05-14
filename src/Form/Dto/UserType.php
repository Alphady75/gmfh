<?php

namespace App\Form\Dto;

use App\Entity\Competence;
use App\Entity\Dto\User;
use App\Entity\Langue;
use App\Entity\SecteursActivite;
use App\Entity\Service;
use App\Entity\Ville;
use App\Repository\CompetenceRepository;
use App\Repository\LangueRepository;
use App\Repository\ServiceRepository;
use App\Repository\VilleRepository;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class UserType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('query', TextType::class, [
                'label' => false,
                'attr' => [
                    'placeholder' => "Je cherche ?",
                    'class' => "bg-white",
                ],
                'required' => false,
            ])
            ->add('secteur', EntityType::class, [
                'label' => false,
                'placeholder'   =>  "Tous les secteurs...",
                'class' => SecteursActivite::class,
                'required' => false,
                'expanded' => true
            ])->add('localisation', TextType::class, [
                'label' => false,
                'required' => false,
                'attr'   =>  [
                    'placeholder'   =>  "Localisation...",
                    'class' => "bg-white",
                ],
            ])->add('langues', EntityType::class, [
                'label' => "Compétence linguistiques",
                'class' => Langue::class,
                'expanded' => true,
                'multiple' => true,
                'label' => false,
                'required' => false,
                'by_reference' => false,
                'query_builder' => function (LangueRepository $lang) {
                    return $lang->createQueryBuilder('l')->orderBy('l.id', 'DESC');
                },
                'attr' => [
                    'placeholder' => "Toute les langues",
                    'class' => ''
                ],
            ])->add('competences', EntityType::class, [
                'label' => "Compétences",
                'class' => Competence::class,
                'placeholder' => "Toute les compétences",
                'expanded' => true,
                'multiple' => true,
                'label' => false,
                'required' => false,
                'by_reference' => false,
                'query_builder' => function (CompetenceRepository $comp) {
                    return $comp->createQueryBuilder('c')->orderBy('c.id', 'DESC');
                },
                'attr' => [
                    'class' => ''
                ],
            ])
            ->add('services', EntityType::class, [
                'label' => "Services",
                'class' => Service::class,
                'placeholder' => "Tout les services",
                'expanded' => true,
                'multiple' => true,
                'label' => false,
                'required' => false,
                'by_reference' => false,
                'query_builder' => function (ServiceRepository $ser) {
                    return $ser->createQueryBuilder('s')->orderBy('s.id', 'DESC');
                },
                'attr' => [
                    'class' => ''
                ],
            ])
            ->add('villes', EntityType::class, [
                'label' => "Périmettre",
                'class' => Ville::class,
                'placeholder' => "Périmettre",
                'expanded' => true,
                'multiple' => true,
                'label' => false,
                'required' => false,
                'by_reference' => false,
                'query_builder' => function (VilleRepository $vil) {
                    return $vil->createQueryBuilder('v')->orderBy('v.id', 'DESC');
                },
                'attr' => [
                    'class' => ''
                ],
            ])
            ->add('limit', ChoiceType::class, [
                'placeholder' => false,
                'label' => false,
                'choices' => [
                    'Affichage de 25' => 25,
                    'Affichage de 30' => 30,
                    'Affichage de 40' => 40,
                    'Affichage de 50' => 50,
                    'Affichage de 60' => 60,
                ],
                'required' => false,
            ])
            ->add('compte', ChoiceType::class, [
                'placeholder' => "Tout les comptes",
                'label' => false,
                'expanded' => true,
                'choices' => [
                    'ADMINISTRATEUR' => 'ADMINISTRATEUR',
                    'PARTICULIER' => 'PARTICULIER',
                    'PERSONNEL (CLEINT)' => 'PERSONNEL',
                ],
                'required' => false,
            ])
            ->add('isVerified', ChoiceType::class, [
                'placeholder' => "Tout les comptes",
                'label' => false,
                'expanded' => true,
                'choices' => [
                    'Email non vérifiée' => 0,
                    'Email vérifiée' => 1,
                ],
                'required' => false,
            ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => User::class,
            'method' => 'GET',
            'csrf_protection' => false,
        ]);
    }
}
