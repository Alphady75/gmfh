<?php

namespace App\Form\Dto;

use App\Entity\Categorie;
use App\Entity\Dto\Offre;
use App\Entity\Experiences;
use App\Entity\Horaires;
use App\Entity\Langue;
use App\Entity\SecteursActivite;
use App\Entity\SousCategorie;
use App\Repository\ExperiencesRepository;
use App\Repository\HorairesRepository;
use App\Repository\LangueRepository;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\NotBlank;

class VisiteOffreType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('query', TextType::class, [
                'label' => false,
                'attr' => [
                    'placeholder' => "Intitulé du poste, mots clés...",
                    'class' => "bg-white",
                ],
                'required' => false,
            ])
            ->add('secteur', EntityType::class, [
                'label' => false,
                'placeholder'   =>  "Tous les secteurs...",
                'attr'   =>  [
                    'class' => "bg-white",
                ],
                'class' => SecteursActivite::class,
                'required' => false,
                'expanded' => false,
            ])->add('localisation', TextType::class, [
                'label' => false,
                'required' => false,
                'attr'   =>  [
                    'placeholder'   =>  "Localisation...",
                ],
            ])
            ->add('typeContrat', ChoiceType::class, [
                'label' => false,
                'placeholder' => "Tout les types de contrats",
                'choices' => [
                    "Temps plein" => "Temps plein",
                    "Temps partiel" => "Temps partiel",
                    "Permanent" => "Permanent",
                    "Contrat à durée déterminée (CDD)" => "Contrat à durée déterminée (CDD)",
                    "Contrat à durée indéterminée (CDI)" => "Contrat à durée indéterminée (CDI)",
                    "Occasionnel" => "Occasionnel",
                    "Saisonnier" => "Saisonnier",
                    "Pigiste/Freelance" => "Pigiste/Freelance",
                    "Apprentissage" => "Apprentissage",
                    "Stage/Coop" => "Stage/Coop"
                ],
                'multiple' => false,
                'expanded' => true,
                'required' => false,
                'constraints' => [
                    new NotBlank()
                ]
            ])->add('horaires', EntityType::class, [
                'label' => "Horaires de travail",
                'class' => Horaires::class,
                'placeholder' => "Tout les horaires",
                'expanded' => true,
                'multiple' => true,
                'required' => false,
                'by_reference' => false,
                'query_builder' => function (HorairesRepository $hor) {
                    return $hor->createQueryBuilder('h')->orderBy('h.id', 'ASC');
                },
                'attr' => [
                    'class' => ''
                ],
            ])->add('experiences', EntityType::class, [
                'label' => "Toute les expériences",
                'placeholder' => "Toute les expériences",
                'class' => Experiences::class,
                'expanded' => true,
                'multiple' => true,
                'required' => false,
                'by_reference' => false,
                'query_builder' => function (ExperiencesRepository $exp) {
                    return $exp->createQueryBuilder('e')->orderBy('e.id', 'ASC');
                },
                'attr' => [
                    'class' => ''
                ],
            ])->add('langues', EntityType::class, [
                'label' => "Exigences linguistiques",
                'class' => Langue::class,
                'placeholder' => "Toute les langues",
                'expanded' => true,
                'multiple' => true,
                'required' => false,
                'by_reference' => false,
                'query_builder' => function (LangueRepository $exp) {
                    return $exp->createQueryBuilder('l')->orderBy('l.id', 'ASC');
                },
                'attr' => [
                    'class' => ''
                ],
            ])
            ->add('minSalaire', TextType::class, [
                'label' => "Salaire minimale",
                'attr' => [
                    'placeholder' => "Ex: 2000$",
                    'class' => "bg-white",
                ],
                'required' => false,
            ])
            ->add('maxSalaire', TextType::class, [
                'label' => "Salaire maximale",
                'attr' => [
                    'placeholder' => "Ex: 9000$",
                    'class' => "bg-white",
                ],
                'required' => false,
            ])
            /*->add('limit', ChoiceType::class, [
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
            ])*/
            ->add('periodicite', ChoiceType::class, [
                'label' => "Périodicité",
                'expanded' => true,
                'placeholder' => "Toute afficher...",
                'required' => false,
                'choices' => [
                    'Rémunération par heure' => 'Heure',
                    'Rémunération par jour' => 'Jour',
                    'Rémunération par mois' => 'Mois',
                    'Rémunération par année' => 'Année',
                ],
            ])
            ->add('lieuTravail', ChoiceType::class, [
                'label' => "Lieu de travail",
                'choices' => [
                    "En personne (emplacement précis)" => "En personne",
                    "Emplacement général" => "Emplacement général",
                    "Télétravail" => "Télétravail Le poste s'exerce à distance",
                    "Télétravail hybride" => "Télétravail hybride",
                    "Déplacements fréquents" => "Déplacements fréquents",
                ],
                'expanded' => true,
                'required' => false,
                'placeholder' => 'Tout lieu'
            ]);

        /*$builder->get('categorie')->addEventListener(
            FormEvents::POST_SUBMIT,
            function (FormEvent $event) {

                $form = $event->getForm();

                $this->addCategorieFields($form->getParent(), $form->getData());
            }
        );

        $builder->addEventListener(
            FormEvents::POST_SET_DATA,
            function (FormEvent $event) {

                $data = $event->getData();
                /** @var SousCategorie $souscategorie  *
                $souscategorie = $data->getSouscategorie(); //dd($souscategorie);

                $form = $event->getForm();

                if ($souscategorie) {

                    $categorie = $souscategorie->getCategorie();

                    $this->addCategorieFields($form, $categorie);

                    $form->get('categorie')->setData($categorie);
                    $form->get('souscategorie')->setData($souscategorie);
                } else {
                    $this->addCategorieFields($form, null);
                }
            }
        );*/
    }

    /**
     * @param Categorie $categorie
     * @return void
     */
    private function addCategorieFields($form, ?Categorie $categorie)
    {

        $builder = $form->getConfig()->getFormFactory()->createNamedBuilder(
            'souscategorie',
            EntityType::class,
            null,
            [
                'label' => "Sous-catégorie de le service",
                'class' =>  SousCategorie::class,
                'auto_initialize'   =>  false,
                'placeholder'   =>  "Sélectionnez une option...",
                'choices'   =>  $categorie ? $categorie->getSousCategories() : [],
                'choice_label' => 'name',
                'constraints' => [
                    new NotBlank(),
                ]
            ]
        );

        $form->add($builder->getForm());
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Offre::class,
            'method' => 'GET',
            'csrf_protection' => false,
        ]);
    }
}
