<?php

namespace App\Form\Dto;

use App\Entity\Dto\Candidature;
use App\Entity\SecteursActivite;
use App\Repository\SecteursActiviteRepository;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class CandidatureType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('query', TextType::class, [
                'label' => false,
                'attr' => [
                    'placeholder' => "Je cherche...",
                    'class' => "bg-white",
                ],
                'required' => false,
            ])
            ->add('secteur', EntityType::class, [
                'label' => false,
                'placeholder'   =>  "Tout les Secteur d'activité",
                'class' => SecteursActivite::class,
                'required' => false,
                'expanded' => true,
                'query_builder' => function (SecteursActiviteRepository $sect) {
                    return $sect->createQueryBuilder('s')->orderBy('s.id', 'DESC')->andWhere('s.complet = :complet')->setParameter('complet', 1);
                },
            ])->add('status', ChoiceType::class, [
                'label' => false,
                'placeholder'   =>  "Toute les candidatures",
                'choices' => [
                    'En attente' => 'En attente',
                    "En cours d'évaluation" => "En cours d'évaluation",
                    'Convier en entretien' => 'Convier en entretien',
                    'Sélectionner' => 'Sélectionner',
                    'Rejeter' => 'Rejeter',
                ],
                'required' => false,
                'expanded' => true
            ])->add('limit', ChoiceType::class, [
                'placeholder' => false,
                'label' => false,
                'choices' => [
                    'Affichage de 12' => 12,
                    'Affichage de 20' => 20,
                    'Affichage de 30' => 30,
                    'Affichage de 40' => 40,
                    'Affichage de 50' => 50,
                    'Affichage de 60' => 60,
                ],
                'required' => false,
            ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Candidature::class,
            'method' => 'GET',
            'csrf_protection' => false,
        ]);
    }
}
