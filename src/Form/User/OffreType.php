<?php

namespace App\Form\User;

use App\Entity\Booster;
use App\Entity\Experiences;
use App\Entity\Horaires;
use App\Entity\Langue;
use App\Entity\Offre;
use App\Entity\Responsabilite;
use App\Entity\SecteursActivite;
use App\Entity\SousSecteursActivite;
use App\Repository\ExperiencesRepository;
use App\Repository\HorairesRepository;
use App\Repository\LangueRepository;
use App\Repository\ResponsabiliteRepository;
use App\Repository\SecteursActiviteRepository;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\NotBlank;

class OffreType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('intitulePoste', TextType::class, [
                'label' => "Titre du poste",
                'help' => "Ex: Assistante de direction",
                'attr' => [
                    'placeholder' => "Ex: Assistante de direction",
                    'class' => "",
                ],
                'constraints' => [
                    new NotBlank()
                ]
            ])
            ->add('typeContrat', ChoiceType::class, [
                'label' => "Type de contrat",
                'placeholder' => "Type de contrat",
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
                'attr' => [
                    'placeholder' => 'Ex: Assistante de Direction',
                ],
                'constraints' => [
                    new NotBlank()
                ]
            ])
            ->add('dateDebut', DateType::class, [
                'label' => "Date d'occupation du poste?",
                'help' => "Date debut de contrat",
                'widget' => 'single_text',
                'attr' => [
                    'class' => ""
                ],
                'required' => false,
            ])
            ->add('dateFin', DateType::class, [
                'label' => "Date de cloture des candidatures",
                'help' => "Date de cloture des candidatures",
                'widget' => 'single_text',
                'attr' => [
                    'class' => ""
                ],
                'required' => false,
            ])
            ->add('name', TextType::class, [
                'label' => "Titre de l'offre",
                'attr' => [
                    'placeholder' => "Ex: recrutement du personnel"
                ],
                'constraints' => [
                    new NotBlank()
                ]
            ])
            ->add('description', TextareaType::class, [
                'label' => "Description",
                'attr' => [
                    'class' => 'mce',
                    'placeholder' => 'Donnez plus de détails à votre offre...',
                ],
                'constraints' => [
                    new NotBlank()
                ]
            ])->add('experiences', EntityType::class, [
                'class' => Experiences::class,
                'placeholder'   =>  "Sélectionnez une option...",
                'multiple' => true,
                'label' => "Expériences/exigences requises pour le poste",
                'help' => "Ex: Maitrise de l'outil informatique...",
                'by_reference' => false,
                'query_builder' => function (ExperiencesRepository $exp) {
                    return $exp->createQueryBuilder('e')->orderBy('e.name', 'ASC');
                },
                'attr' => [
                    'class' => 'js-select2-experiences'
                ],
                'constraints' => [
                    new NotBlank()
                ],
            ])->add('responsabilites', EntityType::class, [
                'class' => Responsabilite::class,
                'placeholder'   =>  "Sélectionnez une option...",
                'multiple' => true,
                'label' => "Responsabilités pour le poste",
                'help' => "Ex: Elaborer des bilans...",
                'by_reference' => false,
                'query_builder' => function (ResponsabiliteRepository $res) {
                    return $res->createQueryBuilder('r')->orderBy('r.name', 'ASC');
                },
                'constraints' => [
                    new NotBlank()
                ],
                'attr' => [
                    'class' => 'js-select2-responsabilites'
                ],
            ])->add('horaires', EntityType::class, [
                'label' => "Horaires",
                'help' => "Ex: Du Lundi au Vendredi, Temps partiel, Quart de jour, Quart de soir,...",
                'class' => Horaires::class,
                'placeholder'   =>  "Sélectionnez une option...",
                'multiple' => true,
                'required' => false,
                'by_reference' => false,
                'query_builder' => function (HorairesRepository $exp) {
                    return $exp->createQueryBuilder('h')->orderBy('h.id', 'ASC');
                },
                'attr' => [
                    'class' => 'js-select2-horaires'
                ],
            ])
            ->add('localisation', TextType::class, [
                'label' => "Quelle est l'adresse postale de ce lieu? ",
                'required' => false,
                'attr' => [
                    'class' => "",
                    'placeholder' => "Quelle est l'adresse postale de ce lieu? "
                ],
            ])
            ->add('lieuTravail', ChoiceType::class, [
                'label' => false,
                'choices' => [
                    "En personne (emplacement précis)" => "En personne",
                    "Emplacement général (Dans une zone limité)" => "Emplacement général",
                    "Télétravail (Aucun travail sur place n'est demander)" => "Télétravail Le poste s'exerce à distance",
                    "Télétravail hybride (Le poste combine le travail sur site à une adresse précise et du télétravail)." => "Télétravail hybride",
                    "Déplacements fréquents (Le poste implique les déplacements reguliers)." => "Déplacements fréquents",
                ],
                'expanded' => true,
                'constraints' => [
                    new NotBlank()
                ],
                'attr' => [
                    'class' => "",
                    'placeholder' => 'Sélectionnez une option'
                ],
            ])
            ->add('genre', ChoiceType::class, [
                'label' => "Le poste concerne ?",
                'placeholder' => "Sélectionnez une option...",
                'choices' => [
                    "Les Hommes" => "Homme",
                    "Les Femmes" => "Femme",
                    "Les Hommes & Femmes" => "Hommes & Femmes",
                    "Autres genres" => "Autres",
                ],
                'attr' => [
                    'class' => "",
                    'placeholder' => 'Sélectionnez une option'
                ],
                'constraints' => [
                    new NotBlank()
                ],
            ])
            ->add('langues', EntityType::class, [
                'class' => Langue::class,
                'multiple' => true,
                'label' => "Exigences linguistiques pour le poste",
                'help' => "GAMFAH s'engage à aider les utilisateurs à trouver un emploi. Si la maîtrise de la langue n'est pas un critère obligatoire pour un poste, n'indiquez aucune langue.",
                'by_reference' => false,
                'query_builder' => function (LangueRepository $lag) {
                    return $lag->createQueryBuilder('l')->orderBy('l.name', 'ASC');
                },
                'constraints' => [
                    new NotBlank()
                ],
                'attr' => [
                    'class' => 'js-select2-langues'
                ],
            ])
            ->add('effectifRecrutement', TextType::class, [
                'label' => "Nombre de candidats",
                'constraints' => [
                    new NotBlank()
                ],
                'attr' => [
                    'class' => "",
                    'min' => 1,
                    'value' => 1,
                    'placeholder' => 'Nombre de personnes à recruter pour le poste...'
                ],
            ])
            ->add('infosContrat', TextareaType::class, [
                'label' => "Informations du contrat",
                'help' => "Fournir plus d'information du contrat (date de début, ville , etc…). ",
                'constraints' => [
                    new NotBlank()
                ],
                'attr' => [
                    'class' => "focus",
                    'placeholder' => 'Informations du contrat...'
                ],
            ])
            ->add('secteuractivite', EntityType::class, [
                'label' => "Secteur d'activité",
                'placeholder'   =>  "Sélectionnez une option...",
                'class' => SecteursActivite::class,
                'query_builder' => function (SecteursActiviteRepository $sect) {
                    return $sect->createQueryBuilder('s')->orderBy('s.name', 'DESC')->andWhere('s.complet = :complet')->setParameter('complet', 1);
                },
                'constraints' => [
                    new NotBlank()
                ]
            ])
            ->add('soussecteuractivite', EntityType::class, [
                'label' => "Sous secteur d'activité",
                'placeholder'   =>  "Sélectionnez une option...",
                'class' => SousSecteursActivite::class,
                'constraints' => [
                    new NotBlank()
                ]
            ])
            ->add('salaire', NumberType::class, [
                'label' => "Salaire moyen",
                'help' => "La prétention salariale peut être définie comme la rémunération à laquelle vous proposez pour le poste.",
                'required' => false,
                'attr' => [
                    'class' => "",
                    'placeholder' => "Salaire moyen (Ex: $2000; 1500€)"
                ],
            ])->add('devise', ChoiceType::class, [
                'label' => "Devise (monnaie)",
                'help' => "Devise (monnaie)",
                'placeholder' => "Sélectionnez une devise...",
                'choices' => [
                    '$' => '$',
                    '€' => '€',
                    'FCFA' => 'FCFA',
                ],
                'required' => false
            ])
            ->add('periodicite', ChoiceType::class, [
                'label' => "Periodicité",
                'help' => "Ex: rémunération par heure",
                'placeholder' => "Rémunération par...",
                'required' => false,
                'choices' => [
                    'Rémunération par heure' => 'Heure',
                    'Rémunération par jour' => 'Jour',
                    'Rémunération par mois' => 'Mois',
                    'Rémunération par année' => 'Année',
                ],
            ])
            ->add('qualification', TextType::class, [
                'label' => "Qualification pour le poste ?",
                'help' => "Facultatif",
                'required' => false,
                'attr' => [
                    'class' => "",
                    'placeholder' => "Ex: Doctorat..."
                ],
            ])
            ->add('anneeExperience', TextType::class, [
                'label' => "Nombre d'année d'expérience ",
                'help' => "Facultatif",
                'required' => false,
                'attr' => [
                    'class' => "",
                    'placeholder' => "Ex: 2ans..."
                ],
            ]);

        $builder->get('secteuractivite')->addEventListener(
            FormEvents::POST_SUBMIT,
            function (FormEvent $event) {

                $form = $event->getForm();

                $this->addSecteursFields($form->getParent(), $form->getData());
            }
        );

        $builder->addEventListener(
            FormEvents::POST_SET_DATA,
            function (FormEvent $event) {

                $data = $event->getData();
                /** @var SousSecteursActivite $soussecteuractivite  */
                $soussecteuractivite = $data->getSousSecteuractivite(); //dd($soussecteuractivite);

                $form = $event->getForm();

                if ($soussecteuractivite) {

                    $secteuractivite = $soussecteuractivite->getSecteursActivite();

                    $this->addSecteursFields($form, $secteuractivite);

                    $form->get('secteuractivite')->setData($secteuractivite);
                    $form->get('soussecteuractivite')->setData($soussecteuractivite);
                } else {
                    $this->addSecteursFields($form, null);
                }
            }
        );
    }

    /**
     * @param SecteursActivite $secteuractivite
     * @return void
     */
    private function addSecteursFields($form, ?SecteursActivite $secteuractivite)
    {

        $builder = $form->getConfig()->getFormFactory()->createNamedBuilder(
            'soussecteuractivite',
            EntityType::class,
            null,
            [
                'label' => "Sous secteur d'activité",
                'class' =>  SousSecteursActivite::class,
                'auto_initialize'   =>  false,
                'placeholder'   =>  "Sélectionnez une option...",
                'choices'   =>  $secteuractivite ? $secteuractivite->getSousSecteursActivites() : [],
                'choice_label' => 'name',
                'constraints' => [
                    new NotBlank(),
                ],
                'attr' => [
                    'class' => '',
                ]
            ]
        );

        $form->add($builder->getForm());
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Offre::class,
        ]);
    }
}
